<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use App\Models\Invoice;
use App\Models\Patient;
use App\Models\Product;
use App\Models\Soin;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the invoices.
     */
    public function index(Request $request)
    {
        // Start building the query
        $query = Invoice::with('patient')->orderBy('id', 'desc');
    
        // Check if there's a search term
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
    
            $query->where(function ($q) use ($search) {
                $q->where('id', $search) // Search by Invoice ID
                  ->orWhereHas('patient', function ($query) use ($search) {
                      $query->where('nom', 'like', "%$search%") // Search by Patient's Nom
                            ->orWhere('prenom', 'like', "%$search%"); // Search by Patient's Prenom
                  });
            });
        }
    
        // Apply pagination to the query
        $invoices = $query->paginate(10);
    
        // Return the view with the filtered invoices
        return view('invoices.index', compact('invoices'));
    }
    

    /**
     * Generate and Share PDF for the specified invoice.
     */
    public function generateAndSharePDF($invoiceId)
    {
        // Fetch the invoice
        $invoice = Invoice::findOrFail($invoiceId);
    
        // Generate the PDF
        $pdf = PDF::loadView('invoices.pdf', ['invoice' => $invoice]);
    
        // Define the file name and save the file to storage
        $fileName = 'invoice_' . $invoiceId . '.pdf';
        $filePath = 'invoices/' . $fileName;
        Storage::disk('public')->put($filePath, $pdf->output());
    
        // Generate the public URL
        $publicUrl = asset('storage/' . $filePath);
    
        // Return the URL where the PDF can be accessed
        return response()->json(['url' => $publicUrl]);
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
            'products.*.id' => 'exists:products,id',
            'products.*.quantity' => 'integer|min:1',
            'soins' => 'nullable|array',
            'soins.*.id' => 'exists:soins,id',
            'soins.*.quantity' => 'integer|min:1',
            'consultation_price' => 'nullable|numeric|min:0',
        ]);
    
        // Extract product and soin IDs
        $productIds = collect($request->input('products'))->pluck('id')->toArray();
        $soinIds = collect($request->input('soins'))->pluck('id')->toArray();
    
        // Calculate total for products and soins
        $totalProducts = Product::whereIn('id', $productIds)->sum('price');
        $totalSoins = Soin::whereIn('id', $soinIds)->sum('price');
    
        // Calculate total invoice amount
        $totalAmount = $totalProducts + $totalSoins + ($request->input('consultation_price') ?? 0);
    
        // Create the invoice
        $invoice = Invoice::create([
            'patient_id' => $request->input('patient_id'),
            'consultation_price' => $request->input('consultation_price'),
            'total_amount' => $totalAmount,
        ]);
    
        // Attach products and soins with quantities
        if (!empty($productIds)) {
            foreach ($request->input('products') as $product) {
                $invoice->products()->attach($product['id'], ['quantity' => $product['quantity']]);
            }
        }
    
        if (!empty($soinIds)) {
            foreach ($request->input('soins') as $soin) {
                $invoice->soins()->attach($soin['id'], ['quantity' => $soin['quantity']]);
            }
        }
    
        return redirect()->route('invoices.index')->with('success', 'Facture créée avec succès.');
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
    
        // Check if the invoice has an associated visite
        if ($invoice->visite) {
            $invoice->visite->delete(); // Delete the associated visite
        }
    
        $invoice->delete(); // Delete the invoice
    
        return redirect()->route('invoices.index')->with('success', 'Invoice and associated visit deleted successfully.');
    }

    /**
     * Generate and download PDF for the specified invoice.
     */
    public function downloadPdf($invoiceId)
{
    $invoice = Invoice::findOrFail($invoiceId);

    // Load the Blade view into the PDF
    $pdf = PDF::loadView('invoices.pdf', compact('invoice'));

    // Download the generated PDF
    return $pdf->download('facture-' . $invoice->id . '.pdf');
}
    
}
