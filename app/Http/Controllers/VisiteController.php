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
use Carbon\Carbon;

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
        $rdv->start = Carbon::parse($rdv->start);$rdv->visite_exists = Visite::where('id_rdv', $rdv->id)->exists();
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
        $rdv = Rdv::with('patient')->where('id',$id_rdv)->first();
        // Pass necessary data to the view
        $products=Product::all();
        $soins=Soin::all();
        return view('visites.create', compact('rdv','products','soins'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_rdv' => 'required|exists:rdvs,id',
            'observation' => 'nullable|string',
            'products' => 'array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'soins' => 'array',
            'soins.*.id' => 'required|exists:soins,id',
            'soins.*.quantity' => 'required|integer|min:1',
        ]);
    
        // Check if a Visite already exists for the given rdv
        if (Visite::where('id_rdv', $validated['id_rdv'])->exists()) {
            return redirect()->back()->withErrors(['id_rdv' => 'A Visite for this RDV already exists.']);
        }
    
        // Retrieve the RDV and its associated patient ID
        $rdv = Rdv::findOrFail($validated['id_rdv']);
        $patientId = $rdv->patient_id; // Assumes 'patient_id' exists in the rdvs table
    
        // Create the Visite
        $visite = Visite::create(['id_rdv' => $validated['id_rdv'],'observation' => $validated['observation'] ?? '',
        ]);
    
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
    
        return redirect()->route('visites.index')->with('success', 'Visite submitted successfully!');
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
    $visite->load('rdv.patient', 'invoice.products', 'invoice.soins');

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
