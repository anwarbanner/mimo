<x-app-layout>
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-lg shadow-md">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Créer un Rendez-vous</h1>

        <form action="{{ route('rdvs.store') }}" method="POST" class="space-y-6">
            @csrf

            <div>
                <label for="patient_id" class="block text-sm font-medium text-gray-700">Patient</label>
                <select name="patient_id" id="patient_id"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2">
                    <option value="">Sélectionnez un patient</option>
                    @foreach ($patients as $patient)
                        <option value="{{ $patient->id }}"
                            {{ old('patient_id') == $patient->id ? 'selected' : '' }}>
                            {{ $patient->nom }} {{ $patient->prenom }}
                        </option>
                    @endforeach
                </select>
                @error('patient_id')
                    <p class="text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="motif" class="block text-sm font-medium text-gray-700">Motif du rendez-vous</label>
                <input type="text" name="title" id="motif" required
                    value="{{ old('title') }}"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2"
                    placeholder="Entrez le motif" />
                @error('title')
                    <p class="text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="heure_debut" class="block text-sm font-medium text-gray-700">Date et Heure de Début</label>
                <input type="datetime-local" name="start" id="heure_debut" required
                    value="{{ old('start') }}"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2" />
                @error('start')
                    <p class="text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="heure_fin" class="block text-sm font-medium text-gray-700">Date et Heure de Fin</label>
                <input type="datetime-local" name="end" id="heure_fin" required
                    value="{{ old('end') }}"
                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 px-4 py-2" />
                @error('end')
                    <p class="text-red-600 mt-2">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit"
                class="w-full sm:w-auto inline-flex justify-center px-6 py-3 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 transition duration-200">
                Créer Rendez-vous
            </button>
        </form>
    </div>

    <script>
        // JavaScript to update the 'end' date to match the 'start' date
        document.getElementById('heure_debut').addEventListener('change', function() {
            var startDate = new Date(this.value);
            var endDate = new Date(startDate);

            // Ensure end time is same day as start
            endDate.setHours(startDate.getHours() + 1); // Optional: set a default duration (e.g., 1 hour)

            // Set the 'end' input to match the 'start' date
            var endInput = document.getElementById('heure_fin');
            endInput.value = endDate.toISOString().slice(0, 16); // Format as 'YYYY-MM-DDTHH:MM'
        });
    </script>
</x-app-layout>
