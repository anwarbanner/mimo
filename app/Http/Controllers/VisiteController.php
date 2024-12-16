<?php

namespace App\Http\Controllers;

use App\Models\Visite;
use App\Models\VisiteImage;
use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Product;
use App\Models\Question;
use App\Models\Rdv;
use App\Models\Soin;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Exception;

class VisiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */


    public function index(Request $request)
    {
        // Get the filter and search parameters
        $filter = $request->input('filter', 'today');
        $search = $request->input('search');

        // Base query with patient relationship
        $query = Rdv::with('patient');

        // Apply filter for 'today', 'month', or 'all'
        if ($filter === 'today') {
            $query->whereDate('start', now()->toDateString());
        } elseif ($filter === 'month') {
            $query->whereMonth('start', now()->month)->whereYear('start', now()->year);
        }

        // Apply search by patient ID or motif
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")->orWhereHas('patient', function ($query) use ($search) {
                    $query->where('id', 'like', "%$search%")->orWhere('nom', 'like', "%$search%")->orWhere('prenom', 'like', "%$search%");
                });
            });
        }

        // Retrieve filtered and/or searched RDVs
        $rdvs = Rdv::paginate(10);

        // Ensure that the start field is parsed as a Carbon instance and check for existing visits
        foreach ($rdvs as $rdv) {
            $rdv->start = Carbon::parse($rdv->start);
            $rdv->visite_exists = Visite::where('id_rdv', $rdv->id)->exists();
        }

        // Pass data to the view
        return view('visites.index', compact('rdvs'));
    }


    public function create(Request $request)
    {
        $id_rdv = $request->query('id_rdv');
        $rdv = Rdv::with('patient')->where('id', $id_rdv)->first();
        // Pass necessary data to the view
        $products = Product::all();
        $soins = Soin::all();
        $questions = Question::with('choix')->orderBy('ordre', 'asc')->get();

        return view('visites.create', compact('rdv', 'products', 'soins', 'questions'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'id_rdv' => 'required|exists:rdvs,id',
            'observation' => 'nullable|string',
            'products' => 'array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'soins' => 'array',
            'soins.*.id' => 'required|exists:soins,id',
            'soins.*.quantity' => 'required|integer|min:1',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg', // Image validation
        ]);

        // Check if a Visite already exists for the given RDV
        if (Visite::where('id_rdv', $validated['id_rdv'])->exists()) {
            return redirect()->back()->withErrors(['id_rdv' => 'A Visite for this RDV already exists.']);
        }

        // Begin a database transaction to ensure all operations are atomic
        DB::beginTransaction();

        try {
            // Retrieve the RDV and its associated patient ID
            $rdv = Rdv::findOrFail($validated['id_rdv']);
            $patientId = $rdv->patient_id; // Assumes 'patient_id' exists in the rdvs table

            // Create the Visite
            $visite = Visite::create([
                'id_rdv' => $validated['id_rdv'],
                'observation' => $validated['observation'] ?? '',
            ]);
            $rdv->update(['etat' => 'passé']);  // Update RDV status

            // Create the Invoice and associate it with the Visite
            $invoice = Invoice::create([
                'visite_id' => $visite->id,
                'patient_id' => $patientId, // Assign the patient ID
                'total_amount' => 0,
            ]);

            // Attach products and soins to the invoice
            $totalAmount = 0;
            foreach ($validated['products'] ?? [] as $product) {
                $invoice->products()->attach($product['id'], ['quantity' => $product['quantity']]);
                $totalAmount += Product::find($product['id'])->price * $product['quantity'];
            }

            foreach ($validated['soins'] ?? [] as $soin) {
                $invoice->soins()->attach($soin['id'], ['quantity' => $soin['quantity']]);
                $totalAmount += Soin::find($soin['id'])->price * $soin['quantity'];
            }

            $invoice->update(['total_amount' => $totalAmount]);

            // Handle image uploads and store them
            if ($request->hasFile('images')) {
                // Debugging: Log file details
                Log::info('Images uploaded:', [
                    'image_count' => count($request->file('images')),
                    'files' => $request->file('images')
                ]);

                foreach ($request->file('images') as $image) {
                    // Validate the image file
                    if (!$image->isValid()) {
                        Log::error('Invalid image file:', [
                            'image' => $image->getClientOriginalName(),
                            'error' => $image->getErrorMessage()
                        ]);
                        return redirect()->back()->withErrors(['images' => 'One or more image files are invalid.']);
                    }

                    // Check if file is too large or invalid format
                    if ($image->getSize() > 2048000) { // 2MB size limit
                        return redirect()->back()->withErrors(['images' => 'One of the images exceeds the maximum size of 2MB.']);
                    }

                    // Get the image content as a string
                    $imageContent = file_get_contents($image->getRealPath());

                    // Create a VisiteImage record to associate the image with the Visite
                    VisiteImage::create([
                        'visite_id' => $visite->id,
                        'images' => $imageContent,  // Store the raw image data
                        'description' => 'Image for visite', // Modify this if needed
                    ]);
                }
            }
            $responses = $request->input('questions', []); // Récupérer les réponses

            foreach ($responses as $response) {
                
                DB::table('reponses')->insert([
                    'valeur' => isset($response['reponse'])
                        ? (is_array($response['reponse']) ? implode(',', $response['reponse']) : $response['reponse'])
                        : null,
                    'informationSup' => $response['informationSup'] ?? null,
                    'date_reponse' => now(),
                    'question_id' => $response['question_id'],
                    'patient_id' => $patientId,
                ]);
            }
            // Commit the transaction if everything is successful
            DB::commit();

            // Redirect with success message
            return redirect()->route('visites.index')->with('success', 'Visite submitted successfully!');
        } catch (Exception $e) {
            // If any exception occurs, rollback the transaction
            DB::rollBack();

            // Log the error for debugging with more details
            Log::error('Visite creation failed', [
                'error' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
                'input_data' => $request->all(),
            ]);

            // Return back with a more detailed error message
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }




    // public function today(Request $request)
    // {
    //     $query = Rdv::with('patient') // Eager load patient relationship
    //         ->whereDate('start', Carbon::today());

    //     if ($request->filled('search')) {
    //         $search = $request->search;
    //         $query->where(function ($q) use ($search) {
    //             $q->where('title', 'like', "%$search%")
    //                 ->orWhereHas('patient', function ($query) use ($search) {
    //                     $query->where('nom', 'like', "%$search%")
    //                         ->orWhere('prenom', 'like', "%$search%");
    //                 });
    //         });
    //     }

    //     $rdvs = $query->get();

    //     return view('visites.today', compact('rdvs'));
    // }

    /**
     * Display the specified resource.
     */
    public function show(Visite $visite)
    {
        // Load related RDV, patient, products, and soins
        $visite->load('rdv.patient', 'invoice.products', 'invoice.soins', 'visiteImages');

        return view('visites.show', compact('visite'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Fetch the visite by ID
        $visite = Visite::with('rdv', 'invoice.products', 'invoice.soins', 'visiteImages')->findOrFail($id);

        // Fetch all products and soins from the database
        $products = Product::all(); // Ensure you have a Product model
        $soins = Soin::all();       // Ensure you have a Soin model

        // Return view with data
        return view('visites.edit', compact('visite', 'products', 'soins'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Validate the request data
        $validated = $request->validate([
            'observation' => 'nullable|string',
            'products' => 'array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'soins' => 'array',
            'soins.*.id' => 'required|exists:soins,id',
            'soins.*.quantity' => 'required|integer|min:1',
        ]);

        // Find the visite and invoice by ID
        $visite = Visite::findOrFail($id);
        $invoice = $visite->invoice;

        // Begin a database transaction to ensure all operations are atomic
        DB::beginTransaction();

        try {
            // Update the observation field
            $visite->update([
                'observation' => $validated['observation'] ?? $visite->observation,
            ]);

            // Detach the existing products and soins to prevent orphaned records
            $invoice->products()->detach();
            $invoice->soins()->detach();

            // Reattach the new products and soins with their quantities
            $totalAmount = 0;

            foreach ($validated['products'] ?? [] as $product) {
                $invoice->products()->attach($product['id'], ['quantity' => $product['quantity']]);
                $totalAmount += Product::find($product['id'])->price * $product['quantity'];
            }

            foreach ($validated['soins'] ?? [] as $soin) {
                $invoice->soins()->attach($soin['id'], ['quantity' => $soin['quantity']]);
                $totalAmount += Soin::find($soin['id'])->price * $soin['quantity'];
            }
            // Handle image uploads and store them
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $image) {
                    if (!$image->isValid()) {
                        Log::error('Invalid image file:', [
                            'image' => $image->getClientOriginalName(),
                            'error' => $image->getErrorMessage()
                        ]);
                        return redirect()->back()->withErrors(['images' => 'One or more image files are invalid.']);
                    }

                    $imageContent = file_get_contents($image->getRealPath());

                    // Create a VisiteImage record to associate the image with the Visite
                    VisiteImage::create([
                        'visite_id' => $visite->id,
                        'images' => $imageContent,  // Store the raw image data
                        'description' => 'Image for visite',  // Modify this as per your needs
                    ]);
                }
            }
            // Update the total amount of the invoice
            $invoice->update(['total_amount' => $totalAmount]);

            // Commit the transaction if everything is successful
            DB::commit();

            // Redirect with success message
            return redirect()->route('visites.index')->with('success', 'Visite updated successfully!');
        } catch (Exception $e) {
            // If any exception occurs, rollback the transaction
            DB::rollBack();

            // Log the error for debugging with more details
            Log::error('Visite update failed', [
                'error' => $e->getMessage(),
                'stack_trace' => $e->getTraceAsString(),
                'input_data' => $request->all(),
            ]);

            // Return back with a more detailed error message
            return redirect()->back()->withErrors(['error' => $e->getMessage()]);
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Visite $visite)
    {
        $visite->delete();

        return redirect()->route('visites.index')->with('success', 'Visite et facture supprimées avec succès.');
    }
}
