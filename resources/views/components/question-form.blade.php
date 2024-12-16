<div class="min-h-screen flex flex-col items-center justify-center bg-gray-50 py-10">
    <h1 class="text-4xl font-bold text-gray-800 mb-8 text-center">Questionnaire</h1>

    <!-- Formulaire -->
    <form method="POST" action="{{ route('questions.storeResponses') }}">
        @csrf
        <div class="w-full max-w-7xl bg-white shadow-lg rounded-lg p-8">
            @php
                $chunks = $questions->chunk(ceil($questions->count() / 3)); // Diviser en 3 parties
            @endphp

            <!-- Accordéon -->
            <div class="space-y-4">
                @foreach ($chunks as $index => $chunk)
                    <div class="border border-gray-200 rounded-lg">
                        <button
                            type="button"
                            class="w-full flex justify-between items-center px-4 py-3 text-gray-800 font-medium text-lg focus:outline-none"
                            onclick="toggleAccordion('accordion-{{ $index }}')">
                            Partie {{ $index + 1 }}
                            <svg id="icon-accordion-{{ $index }}" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 transition-transform transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                        <div id="accordion-{{ $index }}" class="max-h-0 overflow-hidden transition-all duration-300 ease-in-out">
                            <div class="p-4 bg-gray-50">
                                @foreach ($chunk as $question)
                                    <div class="mb-6">
                                        <h3 class="text-lg font-medium text-gray-800 mb-2">{{ $question->texte }}</h3>

                                        <!-- Input caché pour question_id -->
                                        <input type="hidden" name="questions[{{ $question->id }}][question_id]" value="{{ $question->id }}">

                                        <!-- Options selon le type de question -->
                                        @if ($question->type === 'texte')
                                            <input type="text" name="questions[{{ $question->id }}][reponse]"
                                                   class="border border-gray-300 rounded-lg p-4 w-full focus:ring-2 focus:ring-blue-500"
                                                   placeholder="Entrez votre réponse ici">
                                        @elseif ($question->type === 'choix_unique')
                                            @foreach ($question->choix as $choix)
                                                <div class="flex items-center mb-2">
                                                    <input type="radio" id="choix_{{ $choix->id }}"
                                                           name="questions[{{ $question->id }}][reponse]"
                                                           value="{{ $choix->texte }}"
                                                           class="form-radio h-5 w-5 text-blue-600"
                                                           onclick="toggleInfoField(this, '{{ $question->id }}')">
                                                    <label for="choix_{{ $choix->id }}" class="ml-2 text-gray-700">{{ $choix->texte }}</label>
                                                </div>
                                            @endforeach
                                        @elseif ($question->type === 'choix_multiple')
                                            @foreach ($question->choix as $choix)
                                                <div class="flex items-center mb-2">
                                                    <input type="checkbox" id="choix_{{ $choix->id }}"
                                                           name="questions[{{ $question->id }}][reponse][]"
                                                           value="{{ $choix->texte }}"
                                                           class="form-checkbox h-5 w-5 text-blue-600"
                                                           onclick="toggleInfoField(this, '{{ $question->id }}')">
                                                    <label for="choix_{{ $choix->id }}" class="ml-2 text-gray-700">{{ $choix->texte }}</label>
                                                </div>
                                            @endforeach
                                        @endif

                                        <!-- Champ pour les informations supplémentaires -->
                                        <textarea name="questions[{{ $question->id }}][informationSup]"
                                                  id="info_{{ $question->id }}"
                                                  placeholder="Ajoutez des informations supplémentaires (facultatif)"
                                                  class="mt-2 border border-gray-300 rounded-lg p-4 w-full focus:ring-2 focus:ring-blue-500 hidden"></textarea>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Bouton de soumission -->
            <div class="flex justify-end mt-6">
                <button type="submit" class="bg-blue-600 text-white py-3 px-6 rounded-lg hover:bg-blue-700 transition">
                    Enregistrer et Soumettre
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Script JavaScript -->
<script>
    function toggleAccordion(id) {
        const accordion = document.getElementById(id);
        const icon = document.getElementById(`icon-${id}`);

        // Ferme tous les autres accordéons
        document.querySelectorAll('[id^="accordion-"]').forEach(otherAccordion => {
            if (otherAccordion.id !== id) {
                otherAccordion.style.maxHeight = null; // Rétracte les autres accordéons
                const otherIcon = document.getElementById(`icon-${otherAccordion.id}`);
                otherIcon.classList.remove('rotate-180');
            }
        });

        // Ouvre ou ferme l'accordéon sélectionné
        if (accordion.style.maxHeight) {
            accordion.style.maxHeight = null; // Rétracte l'accordéon
            icon.classList.remove('rotate-180');
        } else {
            accordion.style.maxHeight = accordion.scrollHeight + "px"; // Déploie l'accordéon
            icon.classList.add('rotate-180');
        }
    }

    function toggleInfoField(input, questionId) {
        const infoField = document.getElementById(`info_${questionId}`);

        if (input.type === 'radio') {
            if (input.value.toLowerCase() === 'oui' && input.checked) {
                infoField.classList.remove('hidden');
            } else {
                infoField.classList.add('hidden');
            }
        } else if (input.type === 'checkbox') {
            const checkboxes = document.querySelectorAll(`input[name="questions[${questionId}][reponse][]"]`);
            const isYesChecked = Array.from(checkboxes).some(checkbox => checkbox.value.toLowerCase() === 'oui' && checkbox.checked);

            if (isYesChecked) {
                infoField.classList.remove('hidden');
            } else {
                infoField.classList.add('hidden');
            }
        }
    }
</script>
