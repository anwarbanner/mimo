<div class="min-h-screen flex flex-col items-center justify-center bg-gray-50 py-10">
    <h1 class="text-4xl font-bold text-gray-800 mb-8 text-center">Questionnaire</h1>

    <!-- Conteneur principal -->
    <div class="w-full max-w-6xl bg-white shadow-lg rounded-lg p-8 grid grid-cols-1 md:grid-cols-3 gap-8">
        @php
            $chunks = $questions->chunk(ceil($questions->count() / 3)); // Diviser en 3 parties
        @endphp

        <!-- Afficher les sections -->
        @foreach ($chunks as $index => $chunk)
            <div class="bg-gray-100 p-4 rounded-lg shadow-md">
                <h2 class="text-2xl font-semibold text-gray-700 mb-4 text-center">Partie {{ $index + 1 }}</h2>
                
                <!-- Afficher les questions de cette section -->
                @foreach ($chunk as $question)
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-800 mb-2">{{ $question->texte }}</h3>
                        
                        <!-- Options selon le type de question -->
                        @if ($question->type === 'texte')
                            <input type="text" name="responses[{{ $question->id }}]" 
                                   class="border border-gray-300 rounded-lg p-4 w-full focus:ring-2 focus:ring-blue-500" 
                                   placeholder="Entrez votre réponse ici">
                        @elseif ($question->type === 'choix_unique')
                            @foreach ($question->choix as $choix)
                                <div class="flex items-center mb-2">
                                    <input type="radio" id="choix_{{ $choix->id }}" 
                                           name="responses[{{ $question->id }}]" 
                                           value="{{ $choix->texte }}" 
                                           class="form-radio h-5 w-5 text-blue-600">
                                    <label for="choix_{{ $choix->id }}" 
                                           class="ml-2 text-gray-700">{{ $choix->texte }}</label>
                                </div>
                            @endforeach
                        @elseif ($question->type === 'choix_multiple')
                            @foreach ($question->choix as $choix)
                                <div class="flex items-center mb-2">
                                    <input type="checkbox" id="choix_{{ $choix->id }}" 
                                           name="responses[{{ $question->id }}][]" 
                                           value="{{ $choix->texte }}" 
                                           class="form-checkbox h-5 w-5 text-blue-600">
                                    <label for="choix_{{ $choix->id }}" 
                                           class="ml-2 text-gray-700">{{ $choix->texte }}</label>
                                </div>
                            @endforeach
                        @endif
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>

    <!-- Bouton de soumission -->
    <div class="flex justify-end mt-6 w-full max-w-6xl">
        <button type="submit" 
                class="bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 transition">
            Enregistrer et Soumettre
        </button>
    </div>
</div>
