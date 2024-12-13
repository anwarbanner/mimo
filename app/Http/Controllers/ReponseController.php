<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Reponse;
class ReponseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
{
    // Charger les réponses du patient avec les questions associées
    $reponses = $patient->reponses()->with('question')->get();

    // Retourner la vue des réponses
    return view('reponses.show', compact('patient', 'reponses'));
}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $patientId)
{
    $validatedData = $request->validate([
        'responses' => 'array', // Les réponses sont un tableau
        'responses.*.reponse' => 'nullable|string', // Permet des réponses nulles
        'responses.*.informationSup' => 'nullable|string', // Permet des infos supplémentaires nulles
    ]);

    // Récupérer les réponses liées au patient
    foreach ($validatedData['responses'] as $questionId => $responseData) {
        // Trouver ou créer une réponse
        Reponse::updateOrCreate(
            [
                'question_id' => $questionId,
                'patient_id' => $patientId,
            ],
            [
                'valeur' => $responseData['reponse'] ?? null,
                'informationSup' => $responseData['informationSup'] ?? null,
                'date_reponse' => now(),
            ]
        );
    }

    return redirect()->route('reponses.show', $patientId)->with('success', 'Modifications enregistrées avec succès.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
