<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>
    <div class="container mx-auto p-4 sm:p-6 max-w-md md:max-w-lg lg:max-w-xl xl:max-w-4xl 2xl:max-w-6xl">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-800 mb-4 sm:mb-6 text-center">Détails du Patient</h1>
    
        <form id="updateForm" action="{{ route('patients.update', $patient->id) }}" method="POST" 
              class="bg-white shadow-md rounded-lg p-4 sm:p-6 md:p-8 lg:p-10 xl:p-12 space-y-4">
            @csrf
            @method('PUT')

            @if($patient->image)
            <div class="flex justify-center mb-6">
                <img src="data:image/jpeg;base64,{{ $patient->image }}" alt="Image du Patient"
                     class="h-32 w-32 max-w-full rounded-full border border-gray-300 shadow-lg object-cover">
            </div>
            @endif

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
                          class="border border-gray-300 p-3 rounded-lg w-full focus:outline-none focus:ring-2 focus:ring-blue-400 break-words">{{ $patient->observations }}</textarea>
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('patients.index') }}" 
                   class="inline-block px-4 py-2 bg-blue-500 text-white rounded-lg shadow-md hover:bg-blue-600 transition duration-300">
                    Retour
                </a>
                <button type="submit" class="px-4 py-2 bg-green-500 text-white rounded-lg shadow-md hover:bg-green-600 transition duration-300">
                    Enregistrer
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
