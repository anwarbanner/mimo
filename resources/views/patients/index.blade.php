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

        <div class="flex justify-end mb-6">
            <a href="{{ route('patients.create') }}" 
               class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-300">
                Ajouter un Patient
            </a>
        </div>

        <table class="w-full table-auto bg-white shadow-md rounded-lg overflow-hidden">
            <thead class="bg-blue-600 text-white">
                <tr>
                    <th class="px-4 py-2">ID</th>
                    <th class="px-4 py-2">Nom</th>
                    <th class="px-4 py-2">Prénom</th>
                    <th class="px-4 py-2">Email</th>
                    <th class="px-4 py-2">Téléphone</th>
                    <th class="px-4 py-2">Sexe</th>
                    <th class="px-4 py-2">Date de Naissance</th>
                    <th class="px-4 py-2">Observations</th>
                    <th class="px-4 py-2 text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($patients as $patient)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $patient->id }}</td>
                        <td class="px-4 py-2">{{ $patient->nom }}</td>
                        <td class="px-4 py-2">{{ $patient->prenom }}</td>
                        <td class="px-4 py-2">{{ $patient->email }}</td>
                        <td class="px-4 py-2">{{ $patient->telephone }}</td>
                        <td class="px-4 py-2">{{ $patient->sexe }}</td>
                        <td class="px-4 py-2">{{ $patient->date_naissance }}</td>
                        <td class="px-4 py-2">{{ $patient->observations ?? 'Aucune' }}</td>
                        <td class="px-4 py-2 text-center">
                            <div class="flex space-x-2 justify-center">
                                <a href="{{ route('patients.show', $patient->id) }}" 
                                   class="bg-blue-500 hover:bg-blue-700 text-white py-1 px-3 rounded-lg shadow-md hover:shadow-lg transition duration-300">
                                    Voir
                                </a>
                                <a href="{{ route('patients.edit', $patient->id) }}" 
                                   class="bg-yellow-500 hover:bg-yellow-700 text-white py-1 px-3 rounded-lg shadow-md hover:shadow-lg transition duration-300">
                                    Modifier
                                </a>
                                <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" 
                                      onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce patient ?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="bg-red-500 hover:bg-red-700 text-white py-1 px-3 rounded-lg shadow-md hover:shadow-lg transition duration-300">
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
