<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Patient</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 p-10">

    <div class="container mx-auto max-w-xl">
        <h1 class="text-3xl font-bold text-center text-blue-600 mb-6">Détails du Patient</h1>

        <div class="bg-white p-6 rounded-lg shadow-lg">
            <div class="mb-4">
                <h2 class="text-xl font-semibold text-gray-700">Nom :</h2>
                <p class="text-gray-600">{{ $patient->nom }}</p>
            </div>

            <div class="mb-4">
                <h2 class="text-xl font-semibold text-gray-700">Prénom :</h2>
                <p class="text-gray-600">{{ $patient->prenom }}</p>
            </div>

            <div class="mb-4">
                <h2 class="text-xl font-semibold text-gray-700">Email :</h2>
                <p class="text-gray-600">{{ $patient->email }}</p>
            </div>

            <div class="mb-4">
                <h2 class="text-xl font-semibold text-gray-700">Téléphone :</h2>
                <p class="text-gray-600">{{ $patient->telephone }}</p>
            </div>

            <div class="mb-4">
                <h2 class="text-xl font-semibold text-gray-700">Adresse :</h2>
                <p class="text-gray-600">{{ $patient->adresse }}</p>
            </div>

            <div class="mb-4">
                <h2 class="text-xl font-semibold text-gray-700">Date de Naissance :</h2>
                <p class="text-gray-600">{{ $patient->date_naissance }}</p>
            </div>

            <div class="mb-4">
                <h2 class="text-xl font-semibold text-gray-700">Sexe :</h2>
                <p class="text-gray-600">
                    {{ $patient->sexe == 'M' ? 'Masculin' : 'Féminin' }}
                </p>
            </div>

            <div class="mb-4">
                <h2 class="text-xl font-semibold text-gray-700">Observations :</h2>
                <p class="text-gray-600">
                    {{ $patient->observations ?? 'Aucune observation' }}
                </p>
            </div>

            <div class="flex justify-between mt-6">
                <a href="{{ route('patients.index') }}" 
                   class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-all duration-300">
                    Retour à la liste
                </a>
                <a href="{{ route('patients.edit', $patient->id) }}" 
                   class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded-lg shadow-md transition-all duration-300">
                    Modifier
                </a>
            </div>
        </div>
    </div>

</body>
</html>
