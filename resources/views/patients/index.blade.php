<x-app-layout>
<x-slot name="title">Dashboard</x-slot>
<div class="container mx-auto p-10 max-w-6xl">
    <h1 class="text-5xl font-extrabold text-center text-blue-700 mb-8">Liste des Patients</h1>


    <!-- Message de succès -->
    @if (session('success'))
        <div class="bg-green-600 text-white p-4 rounded-lg shadow-lg mb-6">
            {{ session('success') }}
        </div>
    @endif

    <!-- Champ de recherche -->
    <div class="flex justify-end mb-6">
        <input type="text" id="patient_search" placeholder="Chercher un patient"
               class="py-3 px-5 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:border-transparent shadow-md w-full md:w-1/3">
    </div>

    <table class="min-w-full bg-white shadow-lg rounded-lg border border-gray-300 text-sm">
        <thead class="bg-blue-700 text-white">
            <tr>
                <th class="px-2 py-4 text-left w-12">ID</th>
                <th class="px-4 py-3 text-left w-1/6">Nom</th>
                <th class="px-4 py-3 text-left w-1/6">Prénom</th>
                <th class="px-4 py-3 text-left w-1/5">Email</th>
                <th class="px-4 py-3 text-left w-1/6">Téléphone</th>
                <th class="px-2 py-3 text-left w-12">Sexe</th>
                <th class="px-4 py-3 text-left w-1/6">Date de Naissance</th>
                <th class="px-4 py-3 text-left w-1/6">Observations</th>
                <th class="px-4 py-3 text-center w-1/6">Actions</th>
            </tr>
        </thead>
        <tbody class="text-gray-700" id="patient_list">
            @foreach ($patients as $patient)
                <tr class="patient-row border-b" data-name="{{ $patient->nom }} {{ $patient->prenom }}">
                    <td class="px-2 py-4">{{ $patient->id }}</td>
                    <td class="px-4 py-4">{{ $patient->nom }}</td>
                    <td class="px-4 py-4">{{ $patient->prenom }}</td>
                    <td class="px-4 py-4">{{ $patient->email }}</td>
                    <td class="px-4 py-4">{{ $patient->telephone }}</td>
                    <td class="px-2 py-2">{{ $patient->sexe == 'M' ? 'M' : 'F' }}</td>
                    <td class="px-4 py-2">{{ \Carbon\Carbon::parse($patient->date_naissance)->format('d/m/Y') }}</td>
                    <td class="px-4 py-2">{{ $patient->observations ?? 'Aucune' }}</td>
                    <td class="px-4 py-2 text-center">
                        <div class="flex space-x-2 justify-center">
                            <a href="{{ route('patients.show', $patient->id) }}"
                               class="bg-blue-600 hover:bg-blue-800 text-white py-2 px-6 rounded-md transition duration-200 text-xs">Voir</a>
                            <a href="{{ route('patients.edit', $patient->id) }}"
                               class="bg-yellow-600 hover:bg-yellow-800 text-white py-2 px-6 rounded-md transition duration-200 text-xs">Modifier</a>
                            <form action="{{ route('patients.destroy', $patient->id) }}" method="POST"
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce patient ?');" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="bg-red-600 hover:bg-red-800 text-white py-2 px-6 rounded-md transition duration-200 text-xs">Supprimer</button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- Script de recherche -->
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
</x-app-layout>
