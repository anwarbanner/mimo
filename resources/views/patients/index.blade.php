@extends('layouts.app')

@section('title')

@section('contents')
<div class="container mx-auto p-6">
    <div class="flex items-center justify-between mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Liste des Patients</h1>
        <a href="{{ route('patients.create') }}" 
           class="bg-green-600 text-white font-semibold py-2 px-4 rounded-lg shadow-lg hover:bg-green-700 transition duration-300">
            Ajouter un Patient
        </a>
    </div>
    
    @if(Session::has('success'))
        <div class="mb-6 p-4 bg-green-100 border border-green-300 text-green-700 rounded-lg" role="alert">
            {{ Session::get('success') }}
        </div>
    @endif

    <div class="overflow-x-auto bg-white shadow-lg rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-indigo-600 text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-medium">Nom</th>
                    <th class="px-6 py-3 text-left text-sm font-medium">Prénom</th>
                    <th class="px-6 py-3 text-center text-sm font-medium">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-gray-50 divide-y divide-gray-200">
                @forelse($patients as $patient)
                    <tr class="hover:bg-gray-100 transition duration-200">
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $patient->nom }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $patient->prenom }}</td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center space-x-2">
                            <a 
    href="{{ route('patients.show', $patient->id) }}" 
    class="bg-blue-500 text-white py-1 px-3 rounded-lg shadow hover:bg-blue-600 transition duration-300">
    Voir
</a>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td class="text-center text-gray-500 py-4" colspan="3">Aucun patient trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>


@endsection
