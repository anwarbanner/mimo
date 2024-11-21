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
    
    public function startForPatient($patientId)
    {
        $patient = Patient::findOrFail($patientId);
        $this->setSessionData('currentPatientId', $patient->id);
        $this->setSessionData('currentQuestionId', null);
        $this->setSessionData('responses', []);

        $this->preloadQuestions();

        return redirect()->route('questions.index');
    }





    
    // public function index(Request $request)
    // {
    //     $currentPatientId = $this->getCurrentPatientId();
    //     if (!$currentPatientId) {
    //         return redirect()->route('patients.index')->with('error', 'Aucun patient sélectionné.');
    //     }

    //     $currentQuestion = $this->getNextQuestion();
    //     if (!$currentQuestion) {
    //         return redirect()->route('questions.completed')->with('message', 'Vous avez terminé le questionnaire.');
    //     }

    //     $choices = $currentQuestion->choix()->orderBy('ordre', 'asc')->get();

    //     return view('questions.index', compact('currentQuestion', 'choices', 'currentPatientId'));
    // }

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

        // $choices = collect($currentQuestion['choix'])->sortBy('ordre')->values();
        $choices = collect($currentQuestion['choix'])->sortBy('ordre')->map(function ($choix) {
            return (object) $choix; // Convertir chaque choix en objet
        })->values();
        

        return view('questions.index', [
            'currentQuestion' => (object) $currentQuestion,
            'choices' => $choices,
            'currentPatientId' => $currentPatientId,
        ]);
    }




    public function create()
    {
        return view('questions.create');
    }

   



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

    



    
    public function edit($id)
    {
        $question = Question::with('choix')->findOrFail($id);
        return view('questions.edit', compact('question'));
    }

    





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

   



    
    public function destroy($id)
    {
        $question = Question::findOrFail($id);
        $question->choix()->delete();
        $question->delete();

        return redirect()->route('questions.index')->with('success', 'Question supprimée avec succès.');
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
            'informationSup' => 'nullable|string|max:500',
        ]);

        $responses = session('responses', []);
        $responses[] = [
            'valeur' => is_array($validated['reponse']) ? implode(',', $validated['reponse']) : $validated['reponse'],
            'informationSup' => $validated['informationSup'] ?? null, // Ajouter l'information supplémentaire
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

        // foreach ($responses as $response) {
        //     Reponse::create($response + ['patient_id' => $currentPatientId]);
        // }
        foreach ($responses as $response) {
            Reponse::create([
                'valeur' => $response['valeur'],
                'informationSup' => $response['informationSup'] ?? null, // Sauvegarder l'information supplémentaire
                'question_id' => $response['question_id'],
                'patient_id' => $currentPatientId,
                'date_reponse' => $response['date_reponse'],
            ]);
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
                'reponse' => $response['valeur'],
                'informationSup' => $response['informationSup'] ?? null, // Ajouter à la vue
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
    





    // private function getNextQuestion()
    // {
    //     $currentQuestionId = session('currentQuestionId');
    //     if (!$currentQuestionId) {
    //         return Question::orderBy('ordre', 'asc')->first();
    //     }

    //     $currentQuestion = Question::find($currentQuestionId);
    //     return Question::where('ordre', '>', $currentQuestion->ordre)
    //         ->orderBy('ordre', 'asc')
    //         ->first();
    // }

    private function getNextQuestion()
    {
        $currentQuestionId = session('currentQuestionId');
        
        if (!$currentQuestionId) {
            // Récupérer la première question selon l'ordre
            return Question::orderBy('ordre', 'asc')->first();
        }
    
        // Récupérer la question actuelle
        $currentQuestion = Question::find($currentQuestionId);
    
        if ($currentQuestion) {
            // Récupérer la prochaine question selon l'ordre
            return Question::where('ordre', '>', $currentQuestion->ordre)
                ->orderBy('ordre', 'asc')
                ->first();
        }
    
        return null; // Si aucune question n'est trouvée
    }
    

        private function preloadQuestions()
    {
        // Charger toutes les questions avec leurs choix
        $questions = Question::with('choix')->orderBy('ordre', 'asc')->get();

        // Transformer les questions et leurs choix en un tableau simple
        $questionsArray = $questions->map(function ($question) {
            return [
                'id' => $question->id,
                'texte' => $question->texte,
                'type' => $question->type,
                'ordre' => $question->ordre,
                'choix' => $question->choix->map(function ($choix) {
                    return [
                        'id' => $choix->id,
                        'texte' => $choix->texte,
                        'ordre' => $choix->ordre,
                    ];
                })->toArray(),
            ];
        })->toArray();

        // Stocker les questions dans la session
        session(['questions' => $questionsArray]);
    }

    private function getPreloadedQuestions()
    {
        // Récupérer les questions depuis la session
        return session('questions', []);
    }


    
}
