<?php

namespace App\Http\Controllers;

use App\Models\Visite;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Rdv;


class VisiteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve all rdvs with their associated patient information
        $rdvs = Rdv::with('patient')->get();

        // Pass the rdvs to the view
        return view('visites.index', compact('rdvs'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
{
    $id_patient = $request->query('id_patient');
    $id_rdv = $request->query('id_rdv');

    // You can use these IDs to show the relevant data or create a new visite
    return view('visites.create', compact('id_patient', 'id_rdv'));
}

   
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_patient' => 'required|exists:patients,id',
            'id_rdv' => 'required|exists:rdvs,id',
            'section1_timer' => 'nullable|numeric',
            'section2_timer' => 'nullable|numeric',
            'section3_timer' => 'nullable|numeric',
        ]);
    
        Visite::create($validated);
    
        return redirect()->route('patients.show', $request->id_patient) ->with('success', 'Visite created successfully.');
    }
    

    /**
     * Display the specified resource.
     */
    public function show(Visite $visite)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Visite $visite)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Visite $visite)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Visite $visite)
    {
        //
    }
}
