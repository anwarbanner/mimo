<?php

namespace App\Http\Controllers;

use App\Models\Visite;
use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Product;
use App\Models\Rdv;
use App\Models\Soin;
use App\Models\VisiteImage;
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


    public function index()
    {
        // Get the current date
        $currentDate = now()->toDateString();

        // Retrieve rdvs of the current day with their associated patient information
        $rdvs = Rdv::with('patient')->whereDate('start', $currentDate)->get();

        // Ensure that the start field is parsed as a Carbon instance for formatting in the view
        foreach ($rdvs as $rdv) {
            $rdv->start = Carbon::parse($rdv->start);
            $rdv->visite_exists = Visite::where('id_rdv', $rdv->id)->exists();
        }

        // Pass the data to the view
        return view('visites.index', compact('rdvs'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $id_rdv = $request->query('id_rdv');
        $rdv = Rdv::with('patient')->where('id', $id_rdv)->first();
        // Pass necessary data to the view
        $products = Product::all();
        $soins = Soin::all();
        return view('visites.create', compact('rdv', 'products', 'soins'));
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




    public function today(Request $request)
    {
        $query = Rdv::with('patient') // Eager load patient relationship
            ->whereDate('start', Carbon::today());

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%$search%")
                    ->orWhereHas('patient', function ($query) use ($search) {
                        $query->where('nom', 'like', "%$search%")
                            ->orWhere('prenom', 'like', "%$search%");
                    });
            });
        }

        $rdvs = $query->get();

        return view('visites.today', compact('rdvs'));
    }

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
    public function edit(Visite $visite)
    {
        return view('visites.edit', compact('visite'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Visite $visite)
    {
        $validated = $request->validate([
            'observation' => 'nullable|string',
        ]);

        $visite->update($validated);

        return redirect()
            ->route('visites.index')
            ->with('success', 'Visite updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Visite $visite)
    {
        $visite->delete();

        return redirect()
            ->route('visites.index')
            ->with('success', 'Visite deleted successfully.');
    }
}
