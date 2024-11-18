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

        // Pass necessary data to the view
        return view('visites.create', compact('id_rdv'));
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
        ]);

        // Create a new visite record
        Visite::create($validated);

        // Redirect to the patient's detail page (adjust as needed)
        $rdv = Rdv::find($request->id_rdv);
        return redirect()
            ->route('patients.show', $rdv->patient_id)
            ->with('success', 'Visite created successfully.');
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
