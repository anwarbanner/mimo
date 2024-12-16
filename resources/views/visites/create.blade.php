<x-app-layout>
    <div class="max-w-4xl mx-auto p-6 bg-white shadow-lg rounded-lg my-6">
        <h1 class="text-2xl lg:text-3xl font-bold text-gray-800 mb-6">
            Créer une visite pour {{ $rdv->patient->nom }} {{ $rdv->patient->prenom }}
        </h1>

        <!-- Gestion des erreurs -->
        @if ($errors->any())
            <div class="bg-red-50 border border-red-300 text-red-800 px-4 py-3 rounded-md">
                <strong class="font-bold">Erreur :</strong>
                <p class="mt-2">Veuillez corriger les champs suivants :</p>
                <ul class="mt-2 list-disc pl-5 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('visites.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <input type="hidden" name="id_rdv" value="{{ $rdv->id }}">
            <!-- Section Photos -->
            <div class="mb-6">
                <label class="block text-lg font-semibold text-gray-800 mb-3">
                    Prendre des photos de la langue <small class="text-sm font-normal text-gray-600">(maximum 4)</small>
                </label>
                <div id="images" class="space-y-4">
                    <div class="flex items-center space-x-4">
                        <label for="image" class="block text-sm font-medium text-gray-700">
                            Image
                        </label>
                        <input type="file" name="images[]" id="image"
                            class="w-full p-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>
                <button type="button" onclick="addImage()"
                    class="mt-4 inline-flex items-center px-4 py-2 bg-blue-500 text-white text-sm font-medium rounded-lg shadow-sm hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    Ajouter une image
                </button>
                <p class="text-sm text-gray-600 mt-2">
                    Vous pouvez ajouter jusqu'à <span class="font-semibold">4 images</span>.
                </p>
            </div>


            <!-- Section Questionnaire -->
            <div class="w-full max-w-7xl bg-white shadow-lg rounded-lg p-8">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Questionnaire</h2>
                <div class="w-full max-w-7xl bg-white shadow-lg rounded-lg p-8">
                    @php
                        $chunks = $questions->chunk(ceil($questions->count() / 3)); // Diviser en 3 parties
                    @endphp

                    <!-- Accordéon -->
                    <div class="space-y-4">
                        @foreach ($chunks as $index => $chunk)
                            <div class="border border-gray-200 rounded-lg">
                                <button type="button"
                                    class="w-full flex justify-between items-center px-4 py-3 text-gray-800 font-medium text-lg focus:outline-none"
                                    onclick="toggleAccordion('accordion-{{ $index }}')">
                                    Partie {{ $index + 1 }}
                                    <svg id="icon-accordion-{{ $index }}" xmlns="http://www.w3.org/2000/svg"
                                        class="h-6 w-6 transition-transform transform" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <div id="accordion-{{ $index }}"
                                    class="max-h-0 overflow-hidden transition-all duration-300 ease-in-out">
                                    <div class="p-4 bg-gray-50">
                                        @foreach ($chunk as $question)
                                            <div class="mb-6">
                                                <h3 class="text-lg font-medium text-gray-800 mb-2">
                                                    {{ $question->texte }}</h3>

                                                <!-- Input caché pour question_id -->
                                                <input type="hidden"
                                                    name="questions[{{ $question->id }}][question_id]"
                                                    value="{{ $question->id }}">

                                                <!-- Options selon le type de question -->
                                                @if ($question->type === 'texte')
                                                    <input type="text"
                                                        name="questions[{{ $question->id }}][reponse]"
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
                                                            <label for="choix_{{ $choix->id }}"
                                                                class="ml-2 text-gray-700">{{ $choix->texte }}</label>
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
                                                            <label for="choix_{{ $choix->id }}"
                                                                class="ml-2 text-gray-700">{{ $choix->texte }}</label>
                                                        </div>
                                                    @endforeach
                                                @endif

                                                <!-- Champ pour les informations supplémentaires -->
                                                <textarea name="questions[{{ $question->id }}][informationSup]" id="info_{{ $question->id }}"
                                                    placeholder="Ajoutez des informations supplémentaires (facultatif)"
                                                    class="mt-2 border border-gray-300 rounded-lg p-4 w-full focus:ring-2 focus:ring-blue-500 hidden"></textarea>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>


                </div>

            </div>

            <!-- Observation -->
            <div>
                <label for="observation" class="block text-gray-700 font-medium mb-2">Observation</label>
                <textarea name="observation" id="observation" rows="3"
                    class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Écrivez vos observations ici..."></textarea>
            </div>

            <!-- Section Soins -->
            <div>
                <label class="block text-gray-700 font-medium mb-2 h2">Soins</label>
                <div id="soins" class="space-y-4">
                    <div class="flex space-x-4">
                        <select name="soins[0][id]" class="w-2/3 p-2 border rounded-lg">
                            @foreach ($soins as $soin)
                                <option value="{{ $soin->id }}">{{ $soin->name }}</option>
                            @endforeach
                        </select>
                        <input type="number" name="soins[0][quantity]" placeholder="Quantité"
                            class="w-1/3 p-2 border rounded-lg">
                    </div>
                </div>
                <button type="button" onclick="addSoin()" class="text-blue-500 hover:text-blue-700 mt-4">+ Ajouter
                    un
                    soin</button>
            </div>

            <!-- Section Produits -->
            <div>
                <label class="block text-gray-700 font-medium mb-2 h2">Produits</label>
                <div id="products" class="space-y-4">
                    <div class="flex space-x-4">
                        <select name="products[0][id]" class="w-2/3 p-2 border rounded-lg">
                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                            @endforeach
                        </select>
                        <input type="number" name="products[0][quantity]" placeholder="Quantité"
                            class="w-1/3 p-2 border rounded-lg">
                        <button type="button" onclick="deleteProduct(this)"
                            class="text-red-500 hover:text-red-700">Supprimer</button>
                    </div>
                </div>
                <button type="button" onclick="addProduct()" class="text-blue-500 hover:text-blue-700 mt-4">+
                    Ajouter un
                    produit</button>
            </div>

            <button type="submit" class="w-full py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Soumettre la visite
            </button>
        </form>
    </div>


    <!-- Scripts -->
    <script>
        let productIndex = 1;
        let soinIndex = 1;

        function addProduct() {
            const productDiv = document.createElement('div');
            productDiv.classList.add('flex', 'flex-col', 'sm:flex-row', 'space-y-4', 'sm:space-y-0', 'sm:space-x-4',
                'product-item');
            productDiv.innerHTML = `
        <select name="products[${productIndex}][id]" class="w-full sm:w-2/3 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            @foreach ($products as $product)
                <option value="{{ $product->id }}">{{ $product->name }}</option>
            @endforeach
        </select>
        <input type="number" name="products[${productIndex}][quantity]" placeholder="Quantité" value="1"
            class="w-full sm:w-1/3 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        <button type="button" onclick="deleteProduct(this)"
            class="mt-2 text-sm text-red-500 hover:text-red-700 focus:outline-none">Supprimer</button>
    `;
            document.getElementById('products').appendChild(productDiv);
            productIndex++;
        }

        function addSoin() {
            const soinDiv = document.createElement('div');
            soinDiv.classList.add('flex', 'flex-col', 'sm:flex-row', 'space-y-4', 'sm:space-y-0', 'sm:space-x-4',
                'soin-item');
            soinDiv.innerHTML = `
        <select name="soins[${soinIndex}][id]" class="w-full sm:w-2/3 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            @foreach ($soins as $soin)
                <option value="{{ $soin->id }}">{{ $soin->name }}</option>
            @endforeach
        </select>
        <input type="number" name="soins[${soinIndex}][quantity]" placeholder="Quantité" value="1"
            class="w-full sm:w-1/3 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        <button type="button" onclick="deleteSoin(this)"
            class="mt-2 text-sm text-red-500 hover:text-red-700 focus:outline-none">Supprimer</button>
    `;
            document.getElementById('soins').appendChild(soinDiv);
            soinIndex++;
        }
        // <input type="timer" name="soins[${soinIndex}][timer]" class="w-full sm:w-1/3 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Timer (MM:SS)">
        // <button type="button" onclick="startTimer(${soinIndex})" class="mt-2 bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Start Timer</button>

        function deleteProduct(button) {
            button.parentElement.remove();
        }

        function deleteSoin(button) {
            button.parentElement.remove();
        }

        function startTimer(index) {
            const timerInput = document.querySelector(`input[name="soins[${index}][timer]"]`);
            const timerValue = timerInput.value.split(':');
            let minutes = parseInt(timerValue[0], 10);
            let seconds = parseInt(timerValue[1], 10);

            let countdown = minutes * 60 + seconds;
            const timerPopup = document.createElement('div');
            timerPopup.classList.add('fixed', 'top-1/2', 'left-1/2', 'transform', '-translate-x-1/2', '-translate-y-1/2',
                'bg-white', 'p-6', 'shadow-lg', 'rounded-lg', 'z-50');
            const timerDisplay = document.createElement('p');
            timerDisplay.classList.add('text-2xl', 'font-bold', 'text-black');

            const timerSound = new Audio('/mp3/Danger Alarm Sound Effect.mp3'); // Replace with actual sound file path

            const countdownInterval = setInterval(function() {
                countdown--;

                if (countdown <= 0) {
                    clearInterval(countdownInterval);
                    timerSound.play();
                }
            }, 1000);

            timerPopup.appendChild(timerDisplay);
            document.body.appendChild(timerPopup);
        }
        let imageCount = 1;

        function addImage() {
            if (imageCount >= 4) {
                alert('Vous ne pouvez ajouter que 4 images.');
                return;
            }

            const imageDiv = document.createElement('div');
            imageDiv.classList.add('flex', 'items-center', 'space-x-4', 'image-item');
            imageDiv.innerHTML = `
            <input type="file" name="images[]"
                   class="p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                   accept="image/*" />
            <button type="button" onclick="deleteImage(this)"
                    class="text-sm text-red-500 hover:text-red-700 focus:outline-none">Supprimer</button>
        `;
            document.getElementById('images').appendChild(imageDiv);
            imageCount++;
        }

        function deleteImage(button) {
            button.parentElement.remove();
            imageCount--;
        }

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
                const isYesChecked = Array.from(checkboxes).some(checkbox => checkbox.value.toLowerCase() === 'oui' &&
                    checkbox.checked);

                if (isYesChecked) {
                    infoField.classList.remove('hidden');
                } else {
                    infoField.classList.add('hidden');
                }
            }
        }
    </script>
</x-app-layout>
