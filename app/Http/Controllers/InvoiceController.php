<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Product;
use App\Models\Soin;
use Illuminate\Http\Request;
use PDF; // Import for generating PDFs

class InvoiceController extends Controller
{
    /**
     * Display a listing of the invoices.
     */
    public function index(Request $request)
    {
        $query = Invoice::with('patient')->orderBy('id', 'desc'); // Load related patient data
        
        if ($request->has('search') && $request->search != '') {
            $query->where('id', $request->search); // Filter by invoice ID
        }
    
        $invoices = $query->get();
    
        return view('invoices.index', compact('invoices'));
    }
    

    /**
     * Show the form for creating a new invoice.
     */
    public function create()
    {
        $patients = Patient::all();
        $products = Product::all();
        $soins = Soin::all();
        return view('invoices.create', compact('patients', 'products', 'soins'));
    }

    /**
     * Store a newly created invoice in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'products' => 'nullable|array',
            'soins' => 'nullable|array',
            'products.*' => 'exists:products,id',
            'soins.*' => 'exists:soins,id',
            'consultation_price' => 'nullable|numeric|min:0',
        ]);
    
        $products = $validated['products'] ?? [];
        $soins = $validated['soins'] ?? [];
        $consultationPrice = $validated['consultation_price'] ?? 0;
    
        $totalProducts = Product::whereIn('id', $products)->sum('price');
        $totalSoins = Soin::whereIn('id', $soins)->sum('price');
        $totalAmount = $totalProducts + $totalSoins + $consultationPrice;
    
        // Create the invoice without setting visites_id
        $invoice = Invoice::create([
            'patient_id' => $validated['patient_id'],
            'total_amount' => $totalAmount,
            'consultation_price' => $consultationPrice,
        ]);
    
        // Sync products and soins with quantities set to 1
        $invoice->products()->sync(array_fill_keys($products, ['quantity' => 1]));
        $invoice->soins()->sync(array_fill_keys($soins, ['quantity' => 1]));
    
        return redirect()->route('invoices.index')->with('success', 'Invoice created successfully.');
    }
    

    

    

    /**
     * Display the specified invoice.
     */
    public function show($id)
    {
        $invoice = Invoice::with(['patient', 'products', 'soins'])->findOrFail($id);
        return view('invoices.show', compact('invoice'));
    }

    /**
     * Show the form for editing the specified invoice.
     */
    public function edit($id)
    {
        $invoice = Invoice::findOrFail($id);
        $patients = Patient::all();
        $products = Product::all();
        $soins = Soin::all();
        
        return view('invoices.edit', compact('invoice', 'patients', 'products', 'soins'));
    }

    /**
     * Update the specified invoice in storage.
     */
    public function update(Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->update([
            'patient_id' => $request->patient_id,
            'total_amount' => $request->total_amount,
        ]);

        $invoice->products()->sync($request->products);
        $invoice->soins()->sync($request->soins);

        return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully');
    }

    /**
     * Remove the specified invoice from storage.
     */
    public function destroy($id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->delete();

        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully');
    }

    /**
     * Generate PDF for the specified invoice.
     */
    public function downloadPDF($id)
    {
        $invoice = Invoice::with(['patient', 'products', 'soins'])->findOrFail($id);
        
        $pdf = PDF::loadView('invoices.pdf', compact('invoice'));
        return $pdf->download("Invoice_{$invoice->id}.pdf");
    }
}
