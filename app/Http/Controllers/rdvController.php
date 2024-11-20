<?php

namespace App\Http\Controllers;
use Carbon\Carbon;
use App\Mail\AppointmentConfirmationMail;
use Illuminate\Support\Facades\Mail;
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

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'patient_id' => 'nullable|exists:patients,id',
            'title' => 'required|string|max:255',
            'start' => 'required|date',
            'end' => 'required|date|after:start',  // Ensure 'end' is after 'start'
        ]);

        // Check if start and end are on the same day
        $startDate = \Carbon\Carbon::parse($validatedData['start']);
        $endDate = \Carbon\Carbon::parse($validatedData['end']);

        if ($startDate->toDateString() !== $endDate->toDateString()) {
            return back()->withErrors(['end' => 'La date de fin doit être le même jour que la date de début.'])->withInput();
        }

        // Create the rendez-vous record with validated data
        Rdv::create([
            'patient_id' => $validatedData['patient_id'],
            'title' => $validatedData['title'],
            'start' => $validatedData['start'],
            'end' => $validatedData['end'],
            'etat' => 'ouvert', // Default state
        ]);

        return redirect()->route('rdvs.index')->with('success', 'Rendez-vous créé avec succès');
    }

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

        // Validation logic for update
        $request->validate([
            'motif' => 'sometimes|required|string|max:255',
            'date' => 'sometimes|required|date',
            'heure_debut' => 'sometimes|required|date_format:H:i:s',
            'heure_fin' => 'sometimes|date_format:H:i:s',
            'etat' => 'sometimes|nullable|string|max:255',
        ]);

        // Update the rendez-vous record with validated data
        $rdv->update($request->all());

        return redirect()->route('rdvs.index')->with('success', 'Rendez-vous modifié avec succée.');
    }

    // Remove the specified resource from storage
    public function destroy($id)
    {
        $rdv = Rdv::findOrFail($id);
        $rdv->delete();

        return redirect()->route('rdvs.index')->with('success', 'Rendez-vous supprimé avec succée.');
    }


    public function sendConfirmationEmail($appointmentId)
    {
        // Find the appointment by ID
        $appointment = Rdv::find($appointmentId);

        if ($appointment) {
            // Ensure the date is a Carbon instance
            $appointment->date = Carbon::parse($appointment->date);

            // Send confirmation email to the patient
            Mail::to($appointment->patient->email)->send(new AppointmentConfirmationMail($appointment));

            // Return success response
            return response()->json(['message' => 'Email envoyé avec succès']);
        } else {
            // Return error if the appointment is not found
            return response()->json(['message' => 'Rendez-vous non trouvé'], 404);
        }
    }

}
