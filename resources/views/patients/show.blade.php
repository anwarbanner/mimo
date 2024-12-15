<title>Patient {{$patient->nom}}</title>
<x-app-layout>
    <div class="container mx-auto p-6 sm:p-8 max-w-md md:max-w-lg lg:max-w-xl xl:max-w-4xl 2xl:max-w-6xl">
        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-8 sm:mb-10 text-center">Détails du Patient</h1>
    
        <!-- Patient Card -->
        <div class="bg-white shadow-2xl rounded-xl p-6 sm:p-8 space-y-6 sm:space-y-8 border border-gray-100 max-w-md mx-auto sm:max-w-lg lg:max-w-xl">
            <!-- Patient Image -->
            <div class="flex justify-center mb-6">
                @if($patient->image)
                <img src="data:image/jpeg;base64,{{ $patient->image }}" alt="Image du Patient"
                     class="h-24 w-24 sm:h-32 sm:w-32 rounded-full border-4 border-blue-500 object-cover shadow-xl">
                @else
                <div class="h-24 w-24 sm:h-32 sm:w-32 bg-gray-200 rounded-full flex items-center justify-center text-gray-500 text-xl sm:text-2xl">
                    <span>{{ strtoupper(substr($patient->nom, 0, 1)) }}{{ strtoupper(substr($patient->prenom, 0, 1)) }}</span>
                </div>
                @endif
            </div>

            <!-- Patient Name -->
            <div class="text-center mb-5">
                <h2 class="text-2xl sm:text-3xl font-semibold text-gray-800">{{ $patient->nom }} {{ $patient->prenom }}</h2>
            </div>

            <!-- Patient Details -->
            <div class="space-y-6 sm:space-y-8">

                <!-- Email -->
                <div class="flex items-center space-x-4 bg-gray-50 p-4 rounded-lg shadow-sm hover:bg-blue-50 transition-all duration-200">
                    <i class="fas fa-envelope text-blue-600 mr-2"></i>
                    <span class="text-base sm:text-lg font-medium text-gray-700">{{ $patient->email }}</span>
                </div>
                

                <!-- Phone -->
                <div class="flex items-center space-x-4 bg-gray-50 p-4 rounded-lg shadow-sm hover:bg-blue-50 transition-all duration-200">
                    <i class="fas fa-phone-alt text-blue-600 text-2xl"></i>
                    <span class="text-base sm:text-lg font-medium text-gray-700">{{ $patient->telephone }}</span>
                </div>

                <!-- Address -->
                <div class="flex items-center space-x-4 bg-gray-50 p-4 rounded-lg shadow-sm hover:bg-blue-50 transition-all duration-200">
                    <i class="fas fa-map-marker-alt text-blue-600 text-2xl"></i>
                    <span class="text-base sm:text-lg font-medium text-gray-700">{{ $patient->adresse }}</span>
                </div>

                <!-- Date of Birth -->
                <div class="flex items-center space-x-4 bg-gray-50 p-4 rounded-lg shadow-sm hover:bg-blue-50 transition-all duration-200">
                    <i class="fas fa-birthday-cake text-blue-600 text-2xl"></i>
                    <span class="text-base sm:text-lg font-medium text-gray-700">{{ $patient->date_naissance }}</span>
                </div>

                <!-- Gender -->
                <div class="flex items-center space-x-4 bg-gray-50 p-4 rounded-lg shadow-sm hover:bg-blue-50 transition-all duration-200">
                    <i class="fas fa-genderless text-blue-600 text-2xl"></i>
                    <span class="text-base sm:text-lg font-medium text-gray-700">{{ $patient->sexe == 'M' ? 'Masculin' : 'Féminin' }}</span>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row justify-between space-x-0 sm:space-x-4 mt-6 sm:mt-8">
                <button onclick="window.location='{{ route('patients.index') }}'" 
                        class="inline-block px-4 py-2 sm:px-6 sm:py-3 bg-blue-600 text-white rounded-lg shadow-md hover:bg-blue-700 transition duration-300 ease-in-out w-full sm:w-auto mb-4 sm:mb-0">
                    <i class="fas fa-arrow-left mr-2"></i> Retour
                </button>
                <button onclick="window.location='{{ route('reponses.show', $patient->id) }}'" 
                        class="inline-block px-4 py-2 sm:px-6 sm:py-3 bg-green-600 text-white rounded-lg shadow-md hover:bg-green-700 transition duration-300 ease-in-out w-full sm:w-auto mb-4 sm:mb-0">
                    <i class="fas fa-file-alt mr-2"></i> Voir Questionnaire
                </button>
            </div>
            
        </div>
    </div>
</x-app-layout>
