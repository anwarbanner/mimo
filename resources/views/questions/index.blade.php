<title>Questionnaire</title>
<x-app-layout>
    @if (session('status') == 'already_passed')
        <div class="alert alert-warning">
            Vous avez déjà remplis le questionnaire pour ce patient veuillez voire les reponse.
        </div>
        <button onclick="window.location='{{ route('reponses.show', session('patientId')) }}'"
            class="inline-block px-4 py-2 sm:px-6 sm:py-3 bg-green-600 text-white rounded-lg shadow-md hover:bg-green-700 transition duration-300 ease-in-out w-full sm:w-auto mb-4 sm:mb-0">
            <i class="fas fa-file-alt mr-2"></i> Voir Questionnaire
        </button>
    @elseif(session('status') == 'test_started')
        <x-question-form :questions="$questions" />
    @endif


</x-app-layout>
