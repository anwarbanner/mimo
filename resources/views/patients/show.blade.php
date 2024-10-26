@extends('layouts.app')

@section('contents')
<div class="container mx-auto p-6 max-w-4xl xl:max-w-6xl">
    <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Détails du Patient</h1>

    <form action="{{ route('patients.update', $patient->id) }}" method="POST" 
          class="bg-white shadow-md rounded-lg p-6 md:p-10 lg:p-12 xl:p-14 space-y-4">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-sm font-semibold mb-2" for="nom">Nom:</label>
            <input type="text" id="nom" name="nom" value="{{ $patient->nom }}" 
                   class="border border-gray-300 p-3 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-400" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-semibold mb-2" for="prenom">Prénom:</label>
            <input type="text" id="prenom" name="prenom" value="{{ $patient->prenom }}" 
                   class="border border-gray-300 p-3 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-400" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-semibold mb-2" for="email">Email:</label>
            <input type="email" id="email" name="email" value="{{ $patient->email }}" 
                   class="border border-gray-300 p-3 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-400" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-semibold mb-2" for="telephone">Téléphone:</label>
            <input type="tel" id="telephone" name="telephone" value="{{ $patient->telephone }}" 
                   class="border border-gray-300 p-3 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-400" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-semibold mb-2" for="adresse">Adresse:</label>
            <input type="text" id="adresse" name="adresse" value="{{ $patient->adresse }}" 
                   class="border border-gray-300 p-3 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-semibold mb-2" for="date_naissance">Date de Naissance:</label>
            <input type="date" id="date_naissance" name="date_naissance" value="{{ $patient->date_naissance }}" 
                   class="border border-gray-300 p-3 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-400">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-semibold mb-2" for="sexe">Sexe:</label>
            <select id="sexe" name="sexe" 
                    class="border border-gray-300 p-3 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-400">
                <option value="M" {{ $patient->sexe == 'M' ? 'selected' : '' }}>Masculin</option>
                <option value="F" {{ $patient->sexe == 'F' ? 'selected' : '' }}>Féminin</option>
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-semibold mb-2" for="observations">Observations:</label>
            <textarea id="observations" name="observations" rows="4"
                      class="border border-gray-300 p-3 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-400">{{ $patient->observations }}</textarea>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
            <button type="submit" 
                    class="w-full md:w-auto bg-blue-500 hover:bg-blue-600 text-white font-bold py-3 px-6 rounded-lg shadow-md transition duration-300">
                modifier
            </button>

            <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" 
                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce patient ?');" class="w-full md:w-auto">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="w-full md:w-auto bg-red-500 hover:bg-red-600 text-white font-bold py-3 px-6 rounded-lg shadow-md transition duration-300">
                    Supprimer
                </button>
            </form>
        </div>
    </form>
</div>
@endsection
