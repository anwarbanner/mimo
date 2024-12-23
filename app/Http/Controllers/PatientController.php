<?php

namespace App\Http\Controllers;
use App\Models\Patient;
use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Product;
use Carbon\Carbon;
use App\Models\Rdv;
class PatientController extends Controller
{

    public function index(Request $request)
{
    // Initialize the query builder
    $patients = Patient::query();

    // Apply filters if search parameters are provided
    if ($request->filled('search')) {
        $search = $request->input('search');
        
        $patients->where(function ($query) use ($search) {
            $query->where('id', 'like', "%$search%")
                  ->orWhere('nom', 'like', "%$search%")
                  ->orWhere('prenom', 'like', "%$search%")
                  ->orWhere('telephone', 'like', "%$search%");
        });
    }

    // Paginate results
    $patients = $patients->paginate(10);

    // Return the view with the patients data
    return view('patients.index', compact('patients'));
}


    public function create()
    {
        return view('patients.create');
    }

    public function showInvoices($patientId)
{
    $patient = Patient::with('invoices')->findOrFail($patientId);

    return view('patients.invoices', compact('patient'));
}


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:patients',
            'telephone' => 'required|string|max:20',
            'adresse' => 'required|string',
            'date_naissance' => 'required|date',
            'sexe' => 'required|in:M,F',
            'image' => 'nullable|image|max:2048'
        ]);
        $patient = new Patient();
        $patient->nom = $request->nom;
        $patient->prenom = $request->prenom;
        $patient->email = $request->email;
        $patient->telephone = $request->telephone;
        $patient->adresse = $request->adresse;
        $patient->date_naissance = $request->date_naissance;
        $patient->sexe = $request->sexe;

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageData = base64_encode(file_get_contents($image->path()));
            $patient->image = $imageData;
        }

        $patient->save();

        // Patient::create($validatedData);

        return redirect()->route('patients.index')->with('success', 'Patient ajouté avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $patient = Patient::findOrFail($id);
        return view('patients.show', compact('patient'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $patient = Patient::findOrFail($id);
        return view('patients.edit', compact('patient'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'telephone' => 'required|string|max:15',
            'adresse' => 'nullable|string',
            'date_naissance' => 'nullable|date',
            'sexe' => 'nullable|string|in:M,F',
            'image' => 'nullable|image|max:2048', // Validation de l'image
        ]);

        $patient = Patient::findOrFail($id);
        $patient->nom = $request->nom;
        $patient->prenom = $request->prenom;
        $patient->email = $request->email;
        $patient->telephone = $request->telephone;
        $patient->adresse = $request->adresse;
        $patient->date_naissance = $request->date_naissance;
        $patient->sexe = $request->sexe;

        // Gestion de l'image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageData = base64_encode(file_get_contents($image->path()));
            $patient->image = $imageData; // Stockage de l'image en base64
        }

        $patient->save();

        return redirect()->route('patients.index')->with('success', 'Patient mis à jour avec succès !');
    }


    

public function dashboard()
{
    // Get the count of patients, invoices, and products
    $patientCount = Patient::count();
    $invoiceCount = Invoice::count();
    $productCount = Product::count();

    // Get today's date
    $today = Carbon::today();

    // Count today's appointments
    $todayRdvCount = Rdv::whereDate('start', $today)->count();

    // Pass all counts to the view
    return view('dashboard', compact('patientCount', 'invoiceCount', 'productCount', 'todayRdvCount'));
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->delete();

        return redirect()->route('patients.index')->with('success', 'Patient supprimé avec succès.');
    }
}
