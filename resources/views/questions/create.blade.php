<x-app-layout>
    <x-slot name="title">Créer une Question</x-slot>
    <div class="container mx-auto p-4 max-w-3xl">
        <h1 class="text-2xl font-bold mb-6">Créer une Nouvelle Question</h1>

        @if ($errors->any())
            <div class="bg-red-500 text-white p-4 rounded mb-6">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('questions.store') }}" method="POST">
            @csrf

            <!-- Texte de la question -->
            <div class="mb-4">
                <label for="texte" class="block text-sm font-semibold">Texte de la question</label>
                <input type="text" id="texte" name="texte" required
                       class="border border-gray-300 rounded-lg p-2 w-full" value="{{ old('texte') }}">
            </div>

            <!-- Type de question -->
            <div class="mb-4">
                <label for="type" class="block text-sm font-semibold">Type de question</label>
                <select id="type" name="type" required class="border border-gray-300 rounded-lg p-2 w-full">
                    <option value="">Sélectionnez un type</option>
                    <option value="texte">Texte</option>
                    <option value="choix_unique">Choix unique</option>
                    <option value="choix_multiple">Choix multiple</option>
                </select>
            </div>

            <!-- Sexe -->
            <div class="mb-4">
                <label for="sexe" class="block text-sm font-semibold">Sexe</label>
                <select id="sexe" name="sexe" required class="border border-gray-300 rounded-lg p-2 w-full">
                    <option value="Les deux">Les deux</option>
                    <option value="Homme">Homme</option>
                    <option value="Femme">Femme</option>
                </select>
            </div>

            <!-- Ordre de la question -->
            <div class="mb-4">
                <label for="ordre" class="block text-sm font-semibold">Ordre</label>
                <input type="number" id="ordre" name="ordre" min="1" required
                       class="border border-gray-300 rounded-lg p-2 w-full" value="{{ old('ordre') }}">
            </div>

            <!-- Zone de choix -->
            <div id="choix_zone" class="mb-4 hidden">
                <label class="block text-sm font-semibold mb-2">Proposition de réponse</label>
                <div id="choix_container">
                    <!-- Choix seront ajoutés ici -->
                </div>
                <button type="button" onclick="ajouterChoix()"
                        class="bg-blue-500 text-white py-2 px-4 mt-2 rounded">
                    Ajouter une option
                </button>
            </div>

            <button type="submit"
                    class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 mt-6 rounded-lg">
                Enregistrer la question
            </button>
        </form>
    </div>

    <script>
        // Show or hide choices area based on question type
        document.getElementById('type').addEventListener('change', function () {
            const choixZone = document.getElementById('choix_zone');
            if (this.value === 'choix_unique' || this.value === 'choix_multiple') {
                choixZone.classList.remove('hidden');
            } else {
                choixZone.classList.add('hidden');
                document.getElementById('choix_container').innerHTML = '';
            }
        });

        // Function to add a new choice input
        function ajouterChoix() {
            const choixContainer = document.getElementById('choix_container');
            const choixDiv = document.createElement('div');
            choixDiv.classList.add('flex', 'items-center', 'mb-2');

            const input = document.createElement('input');
            input.type = 'text';
            input.name = 'choix[]';
            input.placeholder = 'Texte de l’option';
            input.required = true;
            input.classList.add('border', 'border-gray-300', 'p-2', 'rounded', 'w-full');

            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.classList.add('text-red-500', 'ml-2');
            removeButton.innerHTML = 'Supprimer';
            removeButton.onclick = function () {
                choixDiv.remove();
            };

            choixDiv.appendChild(input);
            choixDiv.appendChild(removeButton);
            choixContainer.appendChild(choixDiv);
        }
    </script>
</x-app-layout>
