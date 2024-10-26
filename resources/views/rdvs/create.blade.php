@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">Créer un Rendez-vous</h1>

        @if ($errors->any())
            <div class="mb-4">
                <ul class="text-red-600">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('rdvs.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="patient_id" class="block text-sm font-medium text-gray-700">Patient</label>
                <select name="patient_id" id="patient_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <option value="">Sélectionnez un patient</option>
                    @foreach ($patients as $patient)
                        <option value="{{ $patient->id }}">{{ $patient->nom }} {{ $patient->prenom }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="motif" class="block text-sm font-medium text-gray-700">Motif</label>
                <input type="text" name="motif" id="motif" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
            </div>

            <div class="mb-4">
                <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                <input type="date" name="date" id="date" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
            </div>

            <div class="mb-4">
                <label for="heure_debut" class="block text-sm font-medium text-gray-700">Heure de Début</label>
                <input type="time" name="heure_debut" id="heure_debut" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
            </div>

            <div class="mb-4">
                <label for="heure_fin" class="block text-sm font-medium text-gray-700">Heure de Fin</label>
                <input type="time" name="heure_fin" id="heure_fin" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" />
            </div>

            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition duration-200">Créer Rendez-vous</button>
        </form>
    </div>
@endsection
