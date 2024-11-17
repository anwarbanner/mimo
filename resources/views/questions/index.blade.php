<x-app-layout>
    <x-slot name="title">Questionnaire</x-slot>

    <div class="min-h-screen flex items-start justify-center bg-gradient-to-b from-blue-50 to-blue-100 mt-20">
        <!-- Conteneur principal -->
        <div class="bg-white shadow-xl rounded-xl p-10 w-full sm:w-4/5 md:w-2/3 lg:w-1/2">
            
                <!-- Question -->
            <h1 class="text-4xl font-bold text-gray-800 mb-8 text-center">{{ $currentQuestion->texte }}</h1>
            <form action="{{ route('questions.storeResponses') }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="question_id" value="{{ $currentQuestion->id }}">
                <input type="hidden" name="patient_id" value="{{ session('currentPatientId') }}">
                <!-- Affichage des options selon le type de question -->
                @if ($currentQuestion->type === 'texte')
                    <!-- Champ de texte -->
                    <div>
                        <label for="reponse" class="block text-lg font-medium text-gray-700">Votre réponse</label>
                        <input type="text" id="reponse" name="reponse" required
                               class="border border-gray-300 rounded-lg p-4 w-full mt-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Entrez votre réponse ici">
                    </div>
                @elseif ($currentQuestion->type === 'choix_unique')
                    <!-- Boutons radio -->
                    <div>
                        <span class="block text-lg font-medium text-gray-700 mb-2">Choisissez une option</span>
                        @foreach ($choices as $choice)
                            <div class="flex items-center space-x-3 mb-4"> <!-- Augmentation de l'espacement ici -->
                                <input type="radio" id="choix_{{ $choice->id }}" name="reponse" value="{{ $choice->texte }}" required
                                       class="form-radio h-5 w-5 text-blue-600 focus:ring-2 focus:ring-blue-500">
                                <label for="choix_{{ $choice->id }}" class="text-lg text-gray-700">{{ $choice->texte }}</label>
                            </div>
                        @endforeach
                    </div>
                @elseif ($currentQuestion->type === 'choix_multiple')
                    <!-- Cases à cocher -->
                    <div>
                        <span class="block text-lg font-medium text-gray-700 mb-2">Sélectionnez vos réponses</span>
                        @foreach ($choices as $choice)
                            <div class="flex items-center space-x-3 mb-4"> <!-- Augmentation de l'espacement ici -->
                                <input type="checkbox" id="choix_{{ $choice->id }}" name="reponse[]"
                                       value="{{ $choice->texte }}"
                                       class="form-checkbox h-5 w-5 text-blue-600 focus:ring-2 focus:ring-blue-500">
                                <label for="choix_{{ $choice->id }}" class="text-lg text-gray-700">{{ $choice->texte }}</label>
                            </div>
                        @endforeach
                    </div>
                @endif

                <!-- Boutons de navigation -->
                <div class="flex justify-end space-x-4 mt-8">
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-6 rounded-lg shadow-md transition duration-200 transform hover:scale-105">
                        Suivant
                    </button>
                </div>
            </form>
            
            <div id="progress-bar" class="w-full bg-gray-200 h-2 rounded mt-6">
                <div id="progress" class="h-2 bg-blue-600 rounded" style="width: 0%;"></div>
            </div>
        </div>
    </div>
    {{-- <script>
        document.addEventListener("DOMContentLoaded", () => {
            const form = document.querySelector("form");
    
            form.addEventListener("submit", async (e) => {
                e.preventDefault();
    
                const formData = new FormData(form);
    
                try {
                    // Envoyer les données de réponse
                    await fetch("{{ route('questions.storeResponses') }}", {
                        method: "POST",
                        body: formData,
                        headers: {
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        }
                    });
    
                    // Obtenir la prochaine question via AJAX
                    const response = await fetch("{{ route('questions.getNextQuestion') }}");
                    const data = await response.json();
    
                    if (data.status === "completed") {
                        window.location.href = "{{ route('questions.completed') }}";
                    } else {
                        // Mettre à jour la question et les choix
                        document.querySelector("h1").textContent = data.question.texte;
                        const choicesContainer = document.querySelector("#choices-container");
                        choicesContainer.innerHTML = "";
    
                        data.question.choices.forEach((choice) => {
                            const choiceElement = document.createElement("div");
                            choiceElement.innerHTML = `
                                <input type="radio" id="choice_${choice.id}" name="reponse" value="${choice.texte}" required>
                                <label for="choice_${choice.id}">${choice.texte}</label>
                            `;
                            choicesContainer.appendChild(choiceElement);
                        });
                    }
                } catch (error) {
                    console.error("Erreur:", error);
                }
            });
        });
    </script> --}}

    
</x-app-layout>
