<x-app-layout>
    <x-slot name="title">Créer Patient</x-slot>
    <div class="container mx-auto p-4 sm:p-8 lg:p-10">
        <h1 class="text-3xl font-bold text-center text-blue-600 mb-8">Ajouter un Patient</h1>

        <!-- Affichage des erreurs de validation -->
        @if ($errors->any())
            <div class="bg-red-500 text-white p-4 rounded mb-6">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Formulaire avec enctype pour les fichiers -->
        <form action="{{ route('patients.store') }}" method="POST" enctype="multipart/form-data" 
              class="bg-white p-6 rounded-lg shadow-md space-y-6">
            @csrf

            <div class="mb-4">
                <label for="nom" class="block text-gray-700 font-semibold mb-2">Nom :</label>
                <input type="text" name="nom" id="nom" value="{{ old('nom') }}"
                       class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" required>
            </div>

            <div class="mb-4">
                <label for="prenom" class="block text-gray-700 font-semibold mb-2">Prénom :</label>
                <input type="text" name="prenom" id="prenom" value="{{ old('prenom') }}"
                       class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" required>
            </div>

            <div class="mb-4">
                <label for="email" class="block text-gray-700 font-semibold mb-2">Email :</label>
                <input type="email" name="email" id="email" value="{{ old('email') }}"
                       class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" required>
            </div>

            <div class="mb-4">
                <label for="telephone" class="block text-gray-700 font-semibold mb-2">Téléphone :</label>
                <input type="text" name="telephone" id="telephone" value="{{ old('telephone') }}"
                       class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" required>
            </div>

            <div class="mb-4">
                <label for="adresse" class="block text-gray-700 font-semibold mb-2">Adresse :</label>
                <textarea name="adresse" id="adresse" rows="3"
                          class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" required>{{ old('adresse') }}</textarea>
            </div>

            <div class="mb-4">
                <label for="date_naissance" class="block text-gray-700 font-semibold mb-2">Date de Naissance :</label>
                <input type="date" name="date_naissance" id="date_naissance" value="{{ old('date_naissance') }}"
                       class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" required>
            </div>

            <div class="mb-4">
                <label for="sexe" class="block text-gray-700 font-semibold mb-2">Sexe :</label>
                <select name="sexe" id="sexe"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" required>
                    <option value="">Sélectionner</option>
                    <option value="M" {{ old('sexe') == 'M' ? 'selected' : '' }}>Masculin</option>
                    <option value="F" {{ old('sexe') == 'F' ? 'selected' : '' }}>Féminin</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="observations" class="block text-gray-700 font-semibold mb-2">Observations :</label>
                <textarea name="observations" id="observations" rows="3"
                          class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">{{ old('observations') }}</textarea>
            </div>

            <!-- Nouveau champ pour l'image -->
            <div class="mb-4">
                <label for="image" class="block text-gray-700 font-semibold mb-2">Image :</label>
                <input type="file" name="image" id="image"
                       class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
            </div>

            <div class="flex justify-between mt-6">
                <a href="{{ route('patients.index') }}"
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-all duration-300">
                    Annuler
                </a>
                <button type="submit"
                        class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-all duration-300">
                    Ajouter
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
