<title>Patients</title>
<x-app-layout>
    <div class="container mx-auto p-4 lg:p-10 max-w-6xl">
        <h1 class="text-4xl lg:text-5xl font-extrabold text-center text-blue-700 mb-6 lg:mb-8">Liste des Patients</h1>
        <div class="flex justify-between mb-4 lg:mb-6">
            <a href="{{ route('patients.create') }}"
               class="text-white font-bold py-2 px-4 rounded-lg shadow-md transition duration-300"
               style="background-color: #3EB489; hover:bg-opacity-80;">
                Ajouter un nouveau patient
            </a>
            <!-- Champ de recherche -->
            <input type="text" id="patient_search" placeholder="Chercher par ID, nom ou prénom"
                   class="py-2 px-3 lg:py-3 lg:px-5 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent shadow-md w-full md:w-1/2 lg:w-1/3">
        </div>

        <!-- Message de succès -->
        @if (session('success'))
            <div class="bg-green-600 text-white p-3 lg:p-4 rounded-lg shadow-lg mb-4 lg:mb-6">
                {{ session('success') }}
            </div>
        @endif

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
                            <div class="flex flex-col space-y-2 sm:flex-row sm:space-y-0 sm:space-x-2 justify-center">
                                <a href="{{ route('patients.edit', $patient->id) }}"
                                   class="bg-yellow-600 hover:bg-yellow-800 text-white py-2 px-3 lg:py-2 lg:px-6 rounded-md transition duration-200 text-xs">Voir</a>
                                <a href="{{ route('patients.startQuestionnaire', $patient->id) }}" class="btn btn-primary">Démarrer le questionnaire</a>

                                <form action="{{ route('patients.destroy', $patient->id) }}" method="POST"
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce patient ?');" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="bg-red-600 hover:bg-red-800 text-white py-2 px-3 lg:py-2 lg:px-6 rounded-md transition duration-200 text-xs">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Script de recherche -->
        <script>
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
