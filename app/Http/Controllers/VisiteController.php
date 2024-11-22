<?php

namespace App\Http\Controllers;

use App\Models\Visite;
use App\Models\VisiteImage;
use App\Models\Invoice;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Product;
use App\Models\Rdv;
use App\Models\Soin;
use Carbon\Carbon;
use Illuminate\Support\Facades\Crypt;
  

class VisiteController extends Controller
{
    public function index()
    {
        $currentDate = now()->toDateString();
        $rdvs = Rdv::with('patient')->whereDate('start', $currentDate)->get();

        foreach ($rdvs as $rdv) {
            $rdv->start = Carbon::parse($rdv->start);
            $rdv->visite_exists = Visite::where('id_rdv', $rdv->id)->exists();
        }

        return view('visites.index', compact('rdvs'));
    }

    public function create(Request $request)
    {
        $id_rdv = $request->query('id_rdv');
        $rdv = Rdv::with('patient')->where('id', $id_rdv)->first();
        $products = Product::all();
        $soins = Soin::all();
        return view('visites.create', compact('rdv', 'products', 'soins'));
    }

 
public function store(Request $request)
{
   $validated = $request->validate([
        'id_rdv' => 'required|exists:rdvs,id',
        'observation' => 'nullable|string',
        'products.*.id' => 'nullable|exists:products,id',
        'products.*.quantity' => 'nullable|integer|min:1',
        'soins.*.id' => 'nullable|exists:soins,id',
        'soins.*.quantity' => 'nullable|integer|min:1',
        'soins.*.timer' => 'nullable|string',
        'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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

    // Handle Images
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $image) {
            // Store as base64 text
            $base64Image = base64_encode(file_get_contents($image->getRealPath()));

            VisiteImage::create([
                'visite_id' => $visite->id,
                'image' => $base64Image,
            ]);
        }
    }

    return redirect()->route('visites.index')->with('success', 'Visite créée avec succès.');
}


    public function show(Visite $visite)
    {
        $visite->load('rdv.patient', 'invoice.products', 'invoice.soins');
        return view('visites.show', compact('visite'));
    }

    public function edit($id)
{
    // Assuming 'Visite' is the model for your visits
    $visite = Visite::findOrFail($id);

    // Get all products and soins from the database
    $products = Product::all();
    $soins = Soin::all();

    // Pass these variables to the view
    return view('visites.edit', [
        'visite' => $visite,
        'products' => $products,
        'soins' => $soins
    ]);
}



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Visite $visite)
{
    $validated = $request->validate([
        'observation' => 'nullable|string',
        'products.*.id' => 'nullable|exists:products,id',
        'products.*.quantity' => 'nullable|integer|min:1',
        'soins.*.id' => 'nullable|exists:soins,id',
        'soins.*.quantity' => 'nullable|integer|min:1',
    ]);

    // Update the Visite
    $visite->update($validated);

    // Update the Invoice
    $invoice = $visite->invoice;
    $totalAmount = 0;

    // Remove previous products and soins
    $invoice->products()->detach();
    $invoice->soins()->detach();

    // Attach new products
    foreach ($validated['products'] ?? [] as $product) {
        $invoice->products()->attach($product['id'], ['quantity' => $product['quantity']]);
        $totalAmount += Product::find($product['id'])->price * $product['quantity'];
    }

    // Attach new soins
    foreach ($validated['soins'] ?? [] as $soin) {
        $invoice->soins()->attach($soin['id'], ['quantity' => $soin['quantity']]);
        $totalAmount += Soin::find($soin['id'])->price * $soin['quantity'];
    }

    // Update the total amount of the invoice
    $invoice->update(['total_amount' => $totalAmount]);

    return redirect()->route('visites.index')->with('success', 'Visite mise à jour avec succès.');
}

public function destroy(Visite $visite)
{
    // Delete the associated products and soins from the invoice
    $visite->invoice->products()->detach();
    $visite->invoice->soins()->detach();

    // Delete the invoice itself
    $visite->invoice->delete();

    // Delete the Visite
    $visite->delete();

    return redirect()->route('visites.index')->with('success', 'Visite et facture supprimées avec succès.');
}

}
