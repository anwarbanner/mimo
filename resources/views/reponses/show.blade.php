<x-app-layout>
    <x-slot name="title">Réponses de {{ $patient->nom }}</x-slot>
    <div class="container mx-auto p-6 max-w-7xl">
        <h1 class="text-2xl font-bold mb-6 text-center">Réponses de {{ $patient->nom }} {{ $patient->prenom }}</h1>
        
        <!-- Message de succès -->
        @if (session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-2 rounded-lg mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if ($reponses->isNotEmpty())
            <form method="POST" action="{{ route('reponses.update', $patient->id) }}">
                @csrf
                @method('PUT')

                <!-- Champ de recherche -->
                <div class="mb-4">
                    <input 
                        type="text" 
                        id="searchInput" 
                        placeholder="Rechercher une question..." 
                        class="border border-gray-300 p-3 rounded-lg w-full focus:ring-2 focus:ring-blue-400"
                    >
                </div>

                <!-- Table des réponses -->
                <div class="overflow-x-auto">
                    <table id="reponsesTable" class="min-w-full border-collapse border border-gray-300 shadow-md rounded-lg overflow-hidden">
                        <thead>
                            <tr class="bg-gray-100 text-left text-gray-800">
                                <th class="border border-gray-300 px-4 py-2 text-sm font-semibold">Question</th>
                                <th class="border border-gray-300 px-4 py-2 text-sm font-semibold">Réponse</th>
                                <th class="border border-gray-300 px-4 py-2 text-sm font-semibold">Informations Supplémentaires</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reponses as $reponse)
                                <tr class="bg-white hover:bg-gray-50 transition">
                                    <!-- Question -->
                                    <td class="border border-gray-300 px-4 py-3 text-gray-700 text-sm question-text">
                                        {{ $reponse->question->texte ?? 'Question supprimée' }}
                                    </td>

                                    <!-- Réponse (Editable) -->
                                    <td class="border border-gray-300 px-4 py-3 text-gray-700 text-sm">
                                        <input 
                                            type="text" 
                                            name="responses[{{ $reponse->question->id }}][reponse]" 
                                            value="{{ $reponse->valeur ?? '' }}" 
                                            class="border border-gray-300 p-2 rounded w-full focus:ring-2 focus:ring-blue-400"
                                            placeholder="Réponse"
                                        >
                                    </td>

                                    <!-- Informations Supplémentaires (Editable) -->
                                    <td class="border border-gray-300 px-4 py-3 text-gray-700 text-sm">
                                        <textarea 
                                            name="responses[{{ $reponse->question->id }}][informationSup]" 
                                            rows="2"
                                            class="border border-gray-300 p-2 rounded w-full focus:ring-2 focus:ring-blue-400"
                                            placeholder="Informations supplémentaires (facultatif)"
                                        >{{ $reponse->informationSup ?? '' }}</textarea>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Boutons de soumission -->
                <div class="mt-6 flex justify-end space-x-4">
                    <a href="{{ route('patients.show', $patient->id) }}" 
                       class="px-6 py-3 bg-gray-500 text-white rounded-lg shadow-md hover:bg-gray-600 transition">
                        Annuler
                    </a>
                    <button type="submit" 
                            class="px-6 py-3 bg-green-500 text-white rounded-lg shadow-md hover:bg-green-600 transition">
                        Enregistrer les Modifications
                    </button>
                </div>
            </form>
        @else
            <p class="text-gray-600 mt-4 text-center">Aucune réponse enregistrée pour ce patient.</p>
        @endif
    </div>

    <!-- Script de recherche -->
    <script>
        document.getElementById('searchInput').addEventListener('input', function () {
            const searchValue = this.value.toLowerCase();
            const rows = document.querySelectorAll('#reponsesTable tbody tr');

            rows.forEach(row => {
                const questionText = row.querySelector('.question-text').textContent.toLowerCase();
                if (questionText.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</x-app-layout>
