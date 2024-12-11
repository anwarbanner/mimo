<title>Patients</title>
<x-app-layout>
    <div class="container mx-auto p-4 lg:p-10 max-w-6xl">
        <h1 class="text-4xl lg:text-5xl font-extrabold text-center text-blue-700 mb-6 lg:mb-8">Liste des Patients</h1>

        <!-- Button to add a new patient -->
        <div class="flex justify-between mb-4 lg:mb-6">
            <a href="{{ route('patients.create') }}"
               class="text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300"
               style="background-color: #3EB489; hover:bg-opacity-80;">
                Ajouter un nouveau patient
            </a>
            <!-- Champ de recherche -->
            <input type="text" id="patient_search" placeholder="Chercher par ID, nom ou prénom"
                   class="py-2 px-3 lg:py-3 lg:px-5 mt-4 sm:mt-0 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent shadow-md w-full sm:w-auto">
        </div>

        <!-- Message de succès -->
        @if (session('success'))
            <div class="bg-green-600 text-white p-3 lg:p-4 rounded-lg shadow-lg mb-4 lg:mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Table responsive -->
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white shadow-lg rounded-lg border border-gray-300 text-sm">
                <thead class="bg-blue-700 text-white">
                    <tr>
                        <th class="px-2 py-2 lg:py-4 text-left w-12">ID</th>
                        <th class="px-4 py-2 lg:py-3 text-left w-1/6">Nom</th>
                        <th class="px-4 py-2 lg:py-3 text-left w-1/6 hidden sm:table-cell">Prénom</th>
                        <th class="px-2 py-2 lg:py-3 text-left w-12">Sexe</th>
                        <th class="px-4 py-2 lg:py-3 text-left w-1/6 hidden md:table-cell">Date de Naissance</th>
                        <th class="px-4 py-2 lg:py-3 text-center w-1/6">Actions</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700" id="patient_list">
                    @foreach ($patients as $patient)
                        <tr class="patient-row border-b" data-id="{{ $patient->id }}" data-name="{{ $patient->nom }} {{ $patient->prenom }}">
                            <td class="px-2 py-2 lg:py-4">{{ $patient->id }}</td>
                            <td class="px-4 py-2 lg:py-4">{{ $patient->nom }}</td>
                            <td class="px-4 py-2 lg:py-4 hidden sm:table-cell">{{ $patient->prenom }}</td>
                            <td class="px-2 py-2">{{ $patient->sexe == 'M' ? 'M' : 'F' }}</td>
                            <td class="px-4 py-2 hidden md:table-cell">{{ \Carbon\Carbon::parse($patient->date_naissance)->format('d/m/Y') }}</td>
                            <td class="px-4 py-2 text-center">
                                <!-- Actions sur PC -->
                                <div class="hidden sm:flex sm:space-x-4 justify-center sm:mt-0">
                                    <a href="{{ route('patients.show', $patient->id) }}"
                                       class="bg-blue-600 hover:bg-blue-400 text-white py-2 px-4 rounded-md transition duration-200 text-xs w-full sm:w-auto text-center">
                                       Voir
                                    </a>
                                     <a href="{{ route('patients.startQuestionnaire', $patient->id) }}" 
                                       class="bg-green-600 hover:bg-blue-800 text-white py-2 px-3 lg:py-4 lg:px-4 rounded-md transition duration-200 text-xs">Questionnaire</a>
                                    <a href="{{ route('patients.edit', $patient->id) }}"
                                       class="bg-yellow-600 hover:bg-yellow-400 text-white py-2 px-4 rounded-md transition duration-200 text-xs w-full sm:w-auto text-center">
                                       Modifier
                                    </a>
                                    <form action="{{ route('patients.destroy', $patient->id) }}" method="POST"
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce patient ?');" class="inline w-full sm:w-auto">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="bg-red-600 hover:bg-red-800 text-white py-2 px-4 rounded-md transition duration-200 text-xs w-full sm:w-auto text-center">
                                            Supprimer
                                        </button>
                                    </form>
                                </div>
                                <!-- Bouton Détails sur Mobile -->
                                <button onclick="openDetails({{ $patient->id }})" 
                                        class="sm:hidden bg-blue-600 hover:bg-blue-800 text-white py-2 px-3 rounded-md transition duration-200 text-xs">
                                    Détails
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Fenêtre modale pour les actions -->
        <div id="details-modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
            <div class="bg-white rounded-lg shadow-lg p-6 w-11/12 max-w-sm">
                <h2 class="text-lg font-bold text-gray-800 mb-4">Actions pour le patient</h2>
                <div id="modal-actions" class="flex flex-col space-y-4">
                    <!-- Les boutons seront insérés dynamiquement -->
                </div>
                <button onclick="closeDetails()" 
                        class="mt-4 w-full bg-red-600 hover:bg-red-800 text-white py-2 px-4 rounded-md">
                    Fermer
                </button>
            </div>
        </div>

        <!-- Script -->
        <script>
            function openDetails(patientId) {
                const modal = document.getElementById('details-modal');
                const modalActions = document.getElementById('modal-actions');

                // Réinitialiser le contenu des actions
                modalActions.innerHTML = `
                    <a href="/patients/${patientId}" class="bg-blue-600 hover:bg-blue-800 text-white py-2 px-4 rounded-md">Voir</a>
                    <a href="/patients/${patientId}/edit" class="bg-yellow-600 hover:bg-yellow-800 text-white py-2 px-4 rounded-md">Modifier</a>
                    <form action="/patients/${patientId}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce patient ?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-600 hover:bg-red-800 text-white py-2 px-4 rounded-md">Supprimer</button>
                    </form>
                `;

                // Afficher la modale
                modal.classList.remove('hidden');
            }

            function closeDetails() {
                const modal = document.getElementById('details-modal');
                modal.classList.add('hidden');
            }
            document.getElementById('patient_search').addEventListener('input', function() {
                var searchValue = this.value.toLowerCase();
                var patientRows = document.querySelectorAll('.patient-row');

                patientRows.forEach(function(row) {
                    var patientID = row.getAttribute('data-id').toLowerCase();
                    var patientName = row.getAttribute('data-name').toLowerCase();
                    if (patientID.includes(searchValue) || patientName.includes(searchValue)) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
            
        </script>
    </div>
</x-app-layout>
