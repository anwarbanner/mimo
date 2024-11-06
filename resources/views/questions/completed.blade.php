<!-- resources/views/questions/completed.blade.php -->
<x-app-layout>
    <x-slot name="title">Review Your Responses</x-slot>

    <div class="container mx-auto mt-8 p-4">
        <h1 class="text-2xl font-bold mb-6">Review Your Responses</h1>

        <form action="{{ route('questions.confirmResponses') }}" method="POST">
            @csrf
            @foreach ($questionsWithResponses as $entry)
                <div class="mb-6">
                    <h2 class="text-lg font-semibold">{{ $entry['question']->texte }}</h2>

                    @if ($entry['question']->type === 'texte')
                        <input type="text" name="responses[{{ $entry['question']->id }}]" 
                               value="{{ $entry['reponse'] }}" 
                               class="border border-gray-300 rounded-lg p-2 w-full mt-2" />
                    @elseif ($entry['question']->type === 'choix_unique')
                        @foreach ($entry['question']->choix as $choix)
                            <div class="flex items-center mb-2">
                                <input type="radio" id="choix_{{ $choix->id }}" 
                                       name="responses[{{ $entry['question']->id }}]" 
                                       value="{{ $choix->texte }}" 
                                       {{ $choix->texte === $entry['reponse'] ? 'checked' : '' }} 
                                       class="form-radio text-blue-600">
                                <label for="choix_{{ $choix->id }}" class="ml-2">{{ $choix->texte }}</label>
                            </div>
                        @endforeach
                    @elseif ($entry['question']->type === 'choix_multiple')
                        @foreach ($entry['question']->choix as $choix)
                            <div class="flex items-center mb-2">
                                <input type="checkbox" id="choix_{{ $choix->id }}" 
                                       name="responses[{{ $entry['question']->id }}][]" 
                                       value="{{ $choix->texte }}" 
                                       {{ in_array($choix->texte, explode(',', $entry['reponse'])) ? 'checked' : '' }} 
                                       class="form-checkbox text-blue-600">
                                <label for="choix_{{ $choix->id }}" class="ml-2">{{ $choix->texte }}</label>
                            </div>
                        @endforeach
                    @endif
                </div>
            @endforeach

            <!-- Confirm Button -->
            <button type="submit" 
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded">
                Confirm Responses
            </button>
        </form>
    </div>
</x-app-layout>
