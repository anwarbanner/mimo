<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use App\Models\Rdv;
use Illuminate\Http\Request;

class RdvController extends Controller
{
    // Display a listing of the resource
    public function index()
    {

        $rdvs = Rdv::all();
        return view('rdvs.index', compact('rdvs')); // Make sure to create this view
    }

    // Show the form for creating a new resource
    public function create()
    {
        $patients = Patient::all();
        return view('rdvs.create', compact('patients')); // Create this view for the form
    }

    // Store a newly created resource in storage
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'patient_id' => 'nullable|exists:patients,id',
            'title' => 'required|string|max:255',
            'start' => 'required|date',
            'end' => 'required|date|after:start',
        ]);

        Rdv::create([
            'patient_id' => $validatedData['patient_id'],
            'title' => $validatedData['title'],
            'start' => $validatedData['start'],
            'end' => $validatedData['end'],
            'etat' => 'ouvert', // Default state
        ]);

        return redirect()->route('rdvs.index')->with('success', 'Rendez-vous créé avec succès');
    }





    // Display the specified resource
    public function show($id)
    {
        $rdv = Rdv::findOrFail($id);
        return view('rdvs.show', compact('rdv')); // Create this view to display the RDV
    }

    // Show the form for editing the specified resource
    public function edit($id)
    {
        $rdv = Rdv::findOrFail($id);
        return view('rdvs.edit', compact('rdv')); // Create this view for editing
    }

    // Update the specified resource in storage
    public function update(Request $request, $id)
    {
        $rdv = Rdv::findOrFail($id);

        $request->validate([
            'motif' => 'sometimes|required|string|max:255',
            'date' => 'sometimes|required|date',
            'heure_debut' => 'sometimes|required|date_format:H:i:s',
            'heure_fin' => 'sometimes|date_format:H:i:s',
            'etat' => 'sometimes|nullable|string|max:255',
        ]);

        $rdv->update($request->all());

        return redirect()->route('rdvs.index')->with('success', 'Rendez-vous updated successfully.');
    }

    // Remove the specified resource from storage
    public function destroy($id)
    {
        $rdv = Rdv::findOrFail($id);
        $rdv->delete();

        return redirect()->route('rdvs.index')->with('success', 'Rendez-vous deleted successfully.');
    }
}
