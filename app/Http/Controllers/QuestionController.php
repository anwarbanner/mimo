<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Choix;
use App\Models\Reponse;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index(Request $request)
{
    // Récupérer la dernière question affichée ou la première si aucune
    $currentQuestionId = $request->session()->get('currentQuestionId');

    // Si nous n'avons pas d'ID de question actuel, récupérons le premier choix
    if (!$currentQuestionId) {
        $choix = Choix::with('question')->first();
        $currentQuestion = $choix ? $choix->question : null;
    } else {
        $currentQuestion = Question::with('choix')->find($currentQuestionId);
    }

    // Si aucune question n'est trouvée, gérer ce cas
    if (!$currentQuestion) {
        return redirect()->route('questions.completed')->with('message', 'Vous avez terminé le questionnaire.');
    }

    // Récupérer tous les choix pour cette question
    $choices = $currentQuestion->choix;

    return view('questions.index', compact('currentQuestion', 'choices'));
}

    


    /**
     * Show the form for creating a new question.
     */
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
    return redirect()->route('questions.index')->with('success', 'Question créée avec succès.');
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

        return view('questions.show', compact('questions'));
    }

    public function show($id)
    {
        $question = Question::with('choix')->findOrFail($id);
        return view('questions.show', compact('question'));
    }

    

    public function storeResponses(Request $request)
    {
        // Valider la réponse
        $request->validate([
            'reponse' => 'nullable', // Valider que la réponse est requise
            'question_id' => 'required|exists:questions,id',
        ]);

        // Obtenez les réponses actuelles stockées dans la session
        $responses = $request->session()->get('responses', []);

        // Ajouter la réponse actuelle au tableau des réponses
        $responses[] = [
            'valeur' => is_array($request->reponse) ? implode(',', $request->reponse) : $request->reponse,
            'date_reponse' => now(),
            'question_id' => $request->question_id,
        ];

        // Mettre à jour le tableau des réponses dans la session
        $request->session()->put('responses', $responses);

        // Passez à la question suivante
        $currentQuestion = Question::find($request->question_id);
        $nextQuestion = Question::where('ordre', '>', $currentQuestion->ordre)->first();

        if ($nextQuestion) {
            $request->session()->put('currentQuestionId', $nextQuestion->id);
        } else {
            // Si c'était la dernière question, passer à la méthode qui enregistrera toutes les réponses
            return redirect()->route('questions.completed');
        }

        return redirect()->route('questions.index');
    }

    public function saveAllResponses(Request $request)
    {
        // Récupérer toutes les réponses stockées dans la session
        $responses = $request->session()->get('responses', []);

        // Insérer toutes les réponses en une seule fois dans la base de données
        foreach ($responses as $response) {
            Reponse::create($response);
        }

        // Vider les réponses de la session
        $request->session()->forget('responses');
        $request->session()->forget('currentQuestionId');

        // Rediriger vers une page de confirmation ou d'accueil
        return redirect()->route('questions.completed')->with('message', 'Vous avez terminé le questionnaire et vos réponses ont été enregistrées.');
    }

    public function completed(Request $request)
    {
        // Retrieve the questions and responses from the session
        $responses = $request->session()->get('responses', []);

        // Get all questions with their current responses if they exist in the session
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






    


    



}
