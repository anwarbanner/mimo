<title>Patient {{$patient->nom}}</title>
<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz4fnFO9gybU5rI5u2B1mwj5WT3syiC2Pl8yzWXEdl8/kSuI6KXKtD73w7" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js" integrity="sha384-QF2ElfNvch5aolgINkGUf5j0uHRKUpqZJL+Lk+lc+8JXQHzHlgxhzRRf3iYkTCG7" crossorigin="anonymous"></script>

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
                    
                    
                   
                    
                    {{-- Display the Age --}}
                    <span class="text-base sm:text-lg font-medium text-gray-700 ml-4">
                        ({{ \Carbon\Carbon::parse($patient->date_naissance)->age }} ans)
                    </span>
                    {{-- Display the Date of Birth --}}
                    <span class="text-base sm:text-lg font-medium text-gray-700">
                        {{ \Carbon\Carbon::parse($patient->date_naissance)->format('d/m/Y') }}
                    </span>
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
                <button type="button" data-bs-toggle="modal" data-bs-target="#invoicesModal"
                        class="inline-block px-4 py-2 sm:px-6 sm:py-3 bg-green-600 text-white rounded-lg shadow-md hover:bg-green-700 transition duration-300 ease-in-out w-full sm:w-auto mb-4 sm:mb-0">
                    <i class="fas fa-file-invoice mr-2"></i> Voir Factures
                </button>
            </div>
            
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="invoicesModal" tabindex="-1" aria-labelledby="invoicesModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-blue-500 text-white">
                    <h5 class="modal-title" id="invoicesModalLabel">Historique des Factures</h5>
                    <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if($patient->invoices->isEmpty())
                        <p class="text-gray-700">Aucune facture trouvée pour ce patient.</p>
                    @else
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>ID Facture</th>
                                    <th>Date</th>
                                    <th>Montant Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($patient->invoices as $invoice)
                                    <tr>
                                        <td>{{ $invoice->id }}</td>
                                        <td>{{ $invoice->created_at->format('d/m/Y') }}</td>
                                        <td>{{ $invoice->total_amount + ($invoice->total_amount * auth()->user()->tva/100), 2  }} DH</td>
                                        <td><form action="{{ route('invoices.show', $invoice->id) }}">
                                            <button type="submit" class="bg-green-500 text-white px-6 py-3 rounded-md text-sm font-medium hover:bg-green-600">
                                                Voir Facture de Visite
                                            </button>
                                        </form></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JS and Popper.js -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</x-app-layout>
