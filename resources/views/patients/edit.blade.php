@extends('layouts.app')

@section('contents')
<div class="container mx-auto p-6 max-w-4xl xl:max-w-6xl">
    <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Modifier le Patient</h1>

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

    <form action="{{ route('patients.update', $patient->id) }}" method="POST" 
          class="bg-white shadow-md rounded-lg p-6 md:p-10 lg:p-12 xl:p-14 space-y-4">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-sm font-semibold mb-2" for="nom">Nom:</label>
            <input type="text" id="nom" name="nom" value="{{ old('nom', $patient->nom) }}" 
                   class="border border-gray-300 p-3 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-400" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-semibold mb-2" for="prenom">Prénom:</label>
            <input type="text" id="prenom" name="prenom" value="{{ old('prenom', $patient->prenom) }}" 
                   class="border border-gray-300 p-3 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-400" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-semibold mb-2" for="email">Email:</label>
            <input type="email" id="email" name="email" value="{{ old('email', $patient->email) }}" 
                   class="border border-gray-300 p-3 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-400" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-semibold mb-2" for="telephone">Téléphone:</label>
            <input type="tel" id="telephone" name="telephone" value="{{ old('telephone', $patient->telephone) }}" 
                   class="border border-gray-300 p-3 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-400" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-semibold mb-2" for="adresse">Adresse:</label>
            <textarea id="adresse" name="adresse" rows="3"
                      class="border border-gray-300 p-3 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-400">{{ old('adresse', $patient->adresse) }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-semibold mb-2" for="date_naissance">Date de Naissance:</label>
            <input type="date" id="date_naissance" name="date_naissance" value="{{ old('date_naissance', $patient->date_naissance) }}" 
                   class="border border-gray-300 p-3 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-400" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-semibold mb-2" for="sexe">Sexe:</label>
            <select id="sexe" name="sexe" 
                    class="border border-gray-300 p-3 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-400">
                <option value="M" {{ old('sexe', $patient->sexe) == 'M' ? 'selected' : '' }}>Masculin</option>
                <option value="F" {{ old('sexe', $patient->sexe) == 'F' ? 'selected' : '' }}>Féminin</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-semibold mb-2" for="observations">Observations:</label>
            <textarea id="observations" name="observations" rows="4"
                      class="border border-gray-300 p-3 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-400">{{ old('observations', $patient->observations) }}</textarea>
        </div>

        <div class="flex justify-between mt-6">
            <a href="{{ route('patients.index') }}" 
               class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                Annuler
            </a>
            <button type="submit" 
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300">
                Mettre à jour
            </button>
        </div>
    </form>
</div>
@endsection
