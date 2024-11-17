<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Choix;
use App\Models\Reponse;
use App\Models\Patient;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;

class QuestionController extends Controller
{
    private function getCurrentPatientId()
    {
        return session('currentPatientId');
    }
    private function setSessionData($key, $value)
    {
        session([$key => $value]);
    }
    // public function startForPatient($patientId)
    // { 
    //     session(['currentPatientId' => $patientId]);
    //     // Vérifier si le patient existe
    //     $patient = Patient::findOrFail($patientId);

    //     // Initialiser le questionnaire pour le patient
    //     session(['currentPatientId' => $patient->id, 'currentQuestionId' => null, 'responses' => []]);

    //     // Rediriger vers la première question
    //     return redirect()->route('questions.index');
    // }
    public function startForPatient($patientId)
    {
        $patient = Patient::findOrFail($patientId);
        $this->setSessionData('currentPatientId', $patient->id);
        $this->setSessionData('currentQuestionId', null);
        $this->setSessionData('responses', []);

        return redirect()->route('questions.index');
    }


    
    public function index(Request $request)
    {
        $currentPatientId = $this->getCurrentPatientId();
        if (!$currentPatientId) {
            return redirect()->route('patients.index')->with('error', 'Aucun patient sélectionné.');
        }

        $currentQuestion = $this->getNextQuestion();
        if (!$currentQuestion) {
            return redirect()->route('questions.completed')->with('message', 'Vous avez terminé le questionnaire.');
        }

        $choices = $currentQuestion->choix()->orderBy('ordre', 'asc')->get();

        return view('questions.index', compact('currentQuestion', 'choices', 'currentPatientId'));
    }

//     public function index(Request $request)
// {
//     $currentPatientId = session('currentPatientId');
//     if (!$currentPatientId) {
//         return redirect()->route('patients.index')->with('error', 'Aucun patient sélectionné.');
//     }

//     // Appeler la méthode getNextQuestion
//     $response = $this->getNextQuestion($request);

//     // Vérifier si le questionnaire est terminé
//     if ($response->getData()->status === 'completed') {
//         $request->session()->forget('currentQuestionId');
//         return redirect()->route('questions.completed')->with('message', 'Vous avez terminé le questionnaire.');
//     }

//     // Préparer les données pour la vue
//     $questionData = $response->getData()->question;

//     return view('questions.index', [
//         'currentQuestion' => (object) [
//             'id' => $questionData->id,
//             'texte' => $questionData->texte,
//             'type' => $questionData->type,
//         ],
//         'choices' => $questionData->choices,
//         'currentPatientId' => $currentPatientId,
//     ]);
// }



   
    public function create()
    {
        return view('questions.create');
    }

    /**
     * Store a newly created question in storage.
     */
    public function store(Request $request)
{
    // Validate the incoming data
    $validatedData = $request->validate([
        'texte' => 'required|string|max:255',
        'type' => 'required|in:texte,choix_unique,choix_multiple',
        'sexe' => 'required|in:Homme,Femme,Les deux',
        'ordre' => 'required|integer|min:1',
        'choix' => 'array', // Ensure that choix is an array when provided
        'choix.*' => 'string|max:255', // Validate each choice as a string with a max length
    ]);

    // Create the question
    $question = Question::create([
        'texte' => $validatedData['texte'],
        'type' => $validatedData['type'],
        'sexe' => $validatedData['sexe'],
        'ordre' => $validatedData['ordre'],
    ]);

    // If the question type is "choix_unique" or "choix_multiple", add choices
    if (in_array($validatedData['type'], ['choix_unique', 'choix_multiple']) && isset($validatedData['choix'])) {
        foreach ($validatedData['choix'] as $index => $choixTexte) {
            $question->choix()->create([
                'texte' => $choixTexte,
                'ordre' => $index + 1, // Optional: save the choice order
            ]);
        }
    }

    // Redirect with a success message
    return redirect()->route('questions.create')->with('success', 'Question créée avec succès.');
}

    /**
     * Show the form for editing the specified question.
     */
    public function edit($id)
    {
        $question = Question::with('choix')->findOrFail($id);
        return view('questions.edit', compact('question'));
    }

    /**
     * Update the specified question in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'texte' => 'required|string|max:255',
            'type' => 'required|in:text,select,checkbox,radio',
            'ordre' => 'required|integer',
            'choix' => 'array',
            'choix.*' => 'string|max:255',
        ]);

        // Trouver et mettre à jour la question
        $question = Question::findOrFail($id);
        $question->update($validatedData);

        // Mettre à jour les choix (si applicables)
        if (isset($validatedData['choix']) && in_array($validatedData['type'], ['select', 'checkbox', 'radio'])) {
            // Supprimer les anciens choix et recréer les nouveaux
            $question->choix()->delete();
            foreach ($validatedData['choix'] as $ordre => $texte) {
                $question->choix()->create([
                    'texte' => $texte,
                    'ordre' => $ordre + 1,
                ]);
            }
        }

        return redirect()->route('questions.index')->with('success', 'Question mise à jour avec succès.');
    }

    /**
     * Remove the specified question from storage.
     */
    public function destroy($id)
    {
        $question = Question::findOrFail($id);
        $question->choix()->delete();
        $question->delete();

        return redirect()->route('questions.index')->with('success', 'Question supprimée avec succès.');
    }

    public function showQuestions(Request $request)
    {
        $patientSexe = $request->input('sexe'); // Récupérer le sexe du patient
        $questions = Question::where(function($query) use ($patientSexe) {
            $query->where('sexe', 'Les deux') // Questions pour les deux
                ->orWhere('sexe', $patientSexe); // Questions spécifiques au sexe
        })->get();

        return view('questions.index', compact('questions'));
    }

    public function show($id)
    {
        $question = Question::with('choix')->findOrFail($id);
        return view('questions.show', compact('question'));
    }
    
    


    public function storeResponses(Request $request)
    {
        $currentPatientId = $this->getCurrentPatientId();
        if (!$currentPatientId) {
            return redirect()->route('patients.index')->with('error', 'Aucun patient sélectionné.');
        }

        $validated = $request->validate([
            'reponse' => 'nullable',
            'question_id' => 'required|exists:questions,id',
        ]);

        $responses = session('responses', []);
        $responses[] = [
            'valeur' => is_array($validated['reponse']) ? implode(',', $validated['reponse']) : $validated['reponse'],
            'date_reponse' => now(),
            'question_id' => $validated['question_id'],
            'patient_id' => $currentPatientId,
        ];
        session(['responses' => $responses]);

        $nextQuestion = $this->getNextQuestion();
        if ($nextQuestion) {
            $this->setSessionData('currentQuestionId', $nextQuestion->id);
            return redirect()->route('questions.index');
        }

        return redirect()->route('questions.completed');
    }

    public function saveAllResponses(Request $request)
    {
        $currentPatientId = $this->getCurrentPatientId();
        $responses = session('responses', []);

        foreach ($responses as $response) {
            Reponse::create($response + ['patient_id' => $currentPatientId]);
        }

        session()->forget(['responses', 'currentQuestionId', 'currentPatientId']);

        return redirect()->route('questions.completed')->with('message', 'Vos réponses ont été enregistrées.');
    }

    public function completed(Request $request)
    {
        $responses = session('responses', []);
        $questionsWithResponses = [];

        foreach ($responses as $response) {
            $question = Question::with('choix')->find($response['question_id']);
            $questionsWithResponses[] = [
                'question' => $question,
                'reponse' => $response['valeur']
            ];
        }

        return view('questions.completed', compact('questionsWithResponses'));
    }

    public function confirmResponses(Request $request)
    {
        $validatedResponses = $request->input('responses', []);

        // Retrieve stored responses from the session
        $responses = $request->session()->get('responses', []);

        // Map session responses to updated responses from the form
        foreach ($validatedResponses as $questionId => $responseValue) {
            foreach ($responses as &$response) {
                if ($response['question_id'] == $questionId) {
                    $response['valeur'] = is_array($responseValue) ? implode(',', $responseValue) : $responseValue;
                    break;
                }
            }
        }

        // Save each updated response in the database
        foreach ($responses as $response) {
            Reponse::create($response);
        }

        // Clear session responses after confirmation
        $request->session()->forget('responses');
        $request->session()->forget('currentQuestionId');

        // Redirect to a thank you page
        return redirect()->route('questions.merci')->with('message', 'Your responses have been saved.');
    }
    // public function getNextQuestion(Request $request)
    // {
    //     $currentPatientId = session('currentPatientId');
    //     if (!$currentPatientId) {
    //         return response()->json(['status' => 'error', 'message' => 'Aucun patient sélectionné.'], 400);
    //     }

    //     $currentQuestionId = $request->session()->get('currentQuestionId');
    //     if (!$currentQuestionId) {
    //         $choix = Choix::with('question')->first();
    //         $currentQuestion = $choix ? $choix->question : null;
    //     } else {
    //         $currentQuestion = Question::with('choix')->find($currentQuestionId);
    //     }

    //     if (!$currentQuestion) {
    //         return response()->json(['status' => 'completed', 'message' => 'Vous avez terminé le questionnaire.']);
    //     }

    //     // Préparer les données de la question et des choix
    //     $questionData = [
    //         'id' => $currentQuestion->id,
    //         'texte' => $currentQuestion->texte,
    //         'type' => $currentQuestion->type,
    //         'choices' => $currentQuestion->choix->map(function ($choix) {
    //             return ['id' => $choix->id, 'texte' => $choix->texte];
    //         })
    //     ];

    //     // Enregistrer la question actuelle dans la session
    //     $request->session()->put('currentQuestionId', $currentQuestion->id);

    //     return response()->json(['status' => 'success', 'question' => $questionData]);
    // }

    private function getNextQuestion()
    {
        $currentQuestionId = session('currentQuestionId');
        if (!$currentQuestionId) {
            return Question::orderBy('ordre', 'asc')->first();
        }

        $currentQuestion = Question::find($currentQuestionId);
        return Question::where('ordre', '>', $currentQuestion->ordre)
            ->orderBy('ordre', 'asc')
            ->first();
    }








    


    



}
