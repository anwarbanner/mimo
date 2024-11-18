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

class VisiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $rdvs = Rdv::with('patient')->get();

        // Pass the data to the view
        return view('rdvs.index', compact('rdvs'));
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

        // Create the Visite
        $visite = Visite::create([
            'id_rdv' => $validated['id_rdv'],
            'observation' => $validated['observation'] ?? '',
        ]);

        // Create the Invoice and associate it with the Visite
        $invoice = Invoice::create([
            'visites_id' => $visite->id,
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

    /**
     * Display the specified resource.
     */
    public function show(Visite $visite)
    {
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
