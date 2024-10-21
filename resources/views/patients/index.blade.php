<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Patients</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">

    <div class="container mx-auto">
        <h1 class="text-4xl font-extrabold text-center text-blue-600 mb-8">Liste des Patients</h1>
        @if (session('success'))
            <div class="bg-green-500 text-white p-4 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif
        <div class="flex justify-end mb-6">
            <a href="{{ route('patients.create') }}" 
               class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-300">
                Ajouter un Patient
            </a>
        </div>
        

        <table class="w-full table-auto bg-white shadow-lg rounded-lg overflow-hidden">
            <thead class="bg-blue-600 text-white">
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
            <tbody>
                @forelse($patients as $patient)
                    <tr class="border-b hover:bg-gray-100 transition duration-200">
                        <td class="px-4 py-3">{{ $patient->id }}</td>
                        <td class="px-4 py-3">{{ $patient->nom }}</td>
                        <td class="px-4 py-3">{{ $patient->prenom }}</td>
                        <td class="px-4 py-3">{{ $patient->email }}</td>
                        <td class="px-4 py-3">{{ $patient->telephone }}</td>
                        <td class="px-4 py-3">{{ $patient->sexe == 'M' ? 'Masculin' : 'Féminin' }}</td>
                        <td class="px-4 py-3">{{ \Carbon\Carbon::parse($patient->date_naissance)->format('d/m/Y') }}</td>
                        <td class="px-4 py-3">{{ $patient->observations ?? 'Aucune' }}</td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex space-x-2 justify-center">
                                <a href="{{ route('patients.show', $patient->id) }}" 
                                   class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded-lg shadow-lg transition duration-300 transform hover:scale-105">
                                    Voir
                                </a>
                                <a href="{{ route('patients.edit', $patient->id) }}" 
                                   class="bg-yellow-500 hover:bg-yellow-700 text-white py-2 px-4 rounded-lg shadow-lg transition duration-300 transform hover:scale-105">
                                    Modifier
                                </a>
                                <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" 
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce patient ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="bg-red-500 hover:bg-red-700 text-white py-2 px-4 rounded-lg shadow-lg transition duration-300 transform hover:scale-105">
                                        Supprimer
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center py-4 text-gray-500">Aucun patient trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-6">
            <a href="/" 
               class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-full transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105">
                Retour à l'accueil
            </a>
        </div>
    </div>

</body>
</html>
