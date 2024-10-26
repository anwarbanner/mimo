<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Patients</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Custom styles for hover effects */
        .table-row:hover {
            background-color: #f1f5f9; /* Light gray on hover */
        }
    </style>
</head>
<body class="bg-gray-100 p-10">

    <div class="container mx-auto">
        <h1 class="text-5xl font-extrabold text-center text-blue-700 mb-8">Liste des Patients</h1>
        
        @if (session('success'))
            <div class="bg-green-600 text-white p-4 rounded-lg shadow-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        <!-- Champ de recherche -->
        <div class="flex justify-end mb-6">
            <input type="text" id="patient_search" placeholder="Chercher un patient"
                   class="py-3 px-5 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent shadow-md">
        </div>

        <table class="w-full table-auto bg-white shadow-lg rounded-lg overflow-hidden border border-gray-300">
            <thead class="bg-blue-700 text-white">
                <tr>
                    <th class="px-4 py-3 text-left">ID</th>
                    <th class="px-4 py-3 text-left">Nom</th>
                    <th class="px-4 py-3 text-left">Prénom</th>
                    <th class="px-4 py-3 text-left">Email</th>
                    <th class="px-4 py-3 text-left">Téléphone</th>
                    <th class="px-4 py-3 text-left">Sexe</th>
                    <th class="px-4 py-3 text-left">Date de Naissance</th>
                    <th class="px-4 py-3 text-left">Observations</th>
                    <th class="px-4 py-3 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-700" id="patient_list">
                @foreach ($patients as $patient)
                    <tr class="patient-row table-row" data-name="{{ $patient->nom }} {{ $patient->prenom }}">
                        <td class="border px-4 py-2">{{ $patient->id }}</td>
                        <td class="border px-4 py-2">{{ $patient->nom }}</td>
                        <td class="border px-4 py-2">{{ $patient->prenom }}</td>
                        <td class="border px-4 py-2">{{ $patient->email }}</td>
                        <td class="border px-4 py-2">{{ $patient->telephone }}</td>
                        <td class="border px-4 py-2">{{ $patient->sexe == 'M' ? 'Masculin' : 'Féminin' }}</td>
                        <td class="border px-4 py-2">{{ \Carbon\Carbon::parse($patient->date_naissance)->format('d/m/Y') }}</td>
                        <td class="border px-4 py-2">{{ $patient->observations ?? 'Aucune' }}</td>
                        <td class="border px-4 py-2 text-center">
                            <div class="flex space-x-2 justify-center">
                                <a href="{{ route('patients.show', $patient->id) }}" 
                                   class="bg-blue-600 hover:bg-blue-800 text-white py-2 px-4 rounded-lg transition duration-200">Voir</a>
                                <a href="{{ route('patients.edit', $patient->id) }}" 
                                   class="bg-yellow-600 hover:bg-yellow-800 text-white py-2 px-4 rounded-lg transition duration-200">Modifier</a>
                                <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" 
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce patient ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="bg-red-600 hover:bg-red-800 text-white py-2 px-4 rounded-lg transition duration-200">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>


    
    <script>
        document.getElementById('patient_search').addEventListener('input', function() {
            var searchValue = this.value.toLowerCase();
            var patientRows = document.querySelectorAll('.patient-row');

            patientRows.forEach(function(row) {
                var patientName = row.getAttribute('data-name').toLowerCase();
                if (patientName.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>
