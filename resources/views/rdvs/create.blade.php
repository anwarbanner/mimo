<x-app-layout>
{{-- <x-slot name="title">Créer un Rendez-vous</x-slot> --}}
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Créer un Rendez-vous</h1>

        @if ($errors->any())
            <div class="mb-6">
                <ul class="text-red-600 list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('rdvs.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="patient_id" class="block text-sm font-medium text-gray-700">Patient</label>
                <select name="patient_id" id="patient_id"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                    <option value="">Sélectionnez un patient</option>
                    @foreach ($patients as $patient)
                        <option value="{{ $patient->id }}">{{ $patient->nom }} {{ $patient->prenom }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="motif" class="block text-sm font-medium text-gray-700">Motif du rendez-vous</label>
                <input type="text" name="title" id="motif" required
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2"
                    placeholder="Entrez le motif" />
            </div>

            <div>
                <label for="heure_debut" class="block text-sm font-medium text-gray-700">Date et Heure de Début</label>
                <input type="datetime-local" name="start" id="heure_debut" required
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2" />
            </div>

            <div>
                <label for="heure_fin" class="block text-sm font-medium text-gray-700">Date et Heure de Fin</label>
                <input type="datetime-local" name="end" id="heure_fin" required
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2" />
            </div>

            <button type="submit"
                class="w-full sm:w-auto inline-flex justify-center px-6 py-3 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 transition duration-200">
                Créer Rendez-vous
            </button>
        </form>
    </div>
</x-app-layout>
