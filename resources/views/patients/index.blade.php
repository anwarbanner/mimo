<title>Patients</title>

<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<!-- Add Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Add Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">


<x-app-layout>
    <div class="container-fluid px-7">
        <!-- Heading Section -->
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8 col-md-10 col-sm-12">
                <h1 class="display-4 text-center text-blue-700">Liste des Patients</h1>
            </div>
        </div>

        <!-- Create Patient Button -->
        <div class="row mb-4">
            <div class="col-12 text-left">
                <a href="{{ route('patients.create') }}" class="btn btn-primary">
                    Ajouter un nouveau patient
                </a>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-12 col-md-6 col-lg-4 mx-auto">
                <form action="{{ route('patients.index') }}" method="GET">
                    <div class="input-group">
                        <input type="text" name="search" id="patient_search" class="form-control" placeholder="Chercher par ID, nom ou prénom" value="{{ request('search') }}">
                        <div class="input-group-append">
                            <button class="btn btn-primary" type="submit">
                                <i class="fas fa-search"></i> Chercher
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        

        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="row">
            <div class="col-12 table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="bg-blue-500">
                        <tr>
                            <th class="text-white"><i class="fas fa-id-card mr-2"></i> ID</th>
                            <th class="text-white"><i class="fas fa-user mr-2"></i> Nom et Prénom</th>
                            <th class="text-white"><i class="fas fa-venus-mars mr-2"></i> Sexe</th>
                            <th class="text-white"><i class="fas fa-calendar-alt mr-2"></i> Date de Naissance</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="patient_list">
                        @foreach ($patients as $patient)
                            <tr class="patient-row" data-id="{{ $patient->id }}" data-name="{{ $patient->nom }} {{ $patient->prenom }}">
                                <td>{{ $patient->id }}</td>
                                <td>{{ $patient->nom }} {{ $patient->prenom }}</td>
                                <td>{{ $patient->sexe == 'M' ? 'M' : 'F' }}</td>
                                <td>{{ \Carbon\Carbon::parse($patient->date_naissance)->format('d/m/Y') }}</td>
                                <td>
                                    <!-- Button to Open Modal -->
                                    <button type="button" class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#actionModal{{ $patient->id }}">
                                        <i class="bi bi-gear"></i> Actions
                                    </button>
                                
                                    <!-- Modal -->
                                    <div class="modal fade" id="actionModal{{ $patient->id }}" tabindex="-1" aria-labelledby="actionModalLabel{{ $patient->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content rounded-4 shadow-lg">
                                                <!-- Modal Header -->
                                                <div class="modal-header border-0 bg-light">
                                                    <h5 class="modal-title text-dark" id="actionModalLabel{{ $patient->id }}">
                                                        Actions pour le patient <strong>{{ $patient->nom }} {{ $patient->prenom }}</strong>
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                        
                                                <!-- Modal Body -->
                                                <div class="modal-body">
                                                    <div class="row g-4">
                                                        <!-- View Button -->
                                                        <div class="col-12 col-md-6">
                                                            <a href="{{ route('patients.show', $patient->id) }}" class="btn btn-outline-info w-100 py-3 px-4 shadow-sm hover-shadow">
                                                                <i class="bi bi-eye"></i> Voir
                                                            </a>
                                                        </div>
                                                        <!-- Questionnaire Button -->
                                                        <div class="col-12 col-md-6">
                                                            <a href="{{ route('patients.startQuestionnaire', $patient->id) }}" class="btn btn-outline-success w-100 py-3 px-4 shadow-sm hover-shadow">
                                                                <i class="bi bi-pencil"></i> Questionnaire
                                                            </a>
                                                        </div>
                                                        <!-- Edit Button -->
                                                        <div class="col-12 col-md-6">
                                                            <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-outline-warning w-100 py-3 px-4 shadow-sm hover-shadow">
                                                                <i class="bi bi-pencil"></i> Modifier
                                                            </a>
                                                        </div>
                                                        <!-- Delete Button -->
                                                        <div class="col-12 col-md-6">
                                                            <form action="{{ route('patients.destroy', $patient->id) }}" method="POST" class="w-100">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-outline-danger w-100 py-3 px-4 shadow-sm hover-shadow delete-button">
                                                                    <i class="bi bi-trash"></i> Supprimer
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        {{-- Pagination links --}}
        {{ $patients->links() }}
    </div>
    
   
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-button').forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault(); // Prevent form submission immediately
                    const form = button.closest('form');
                    
                    Swal.fire({
                        title: 'Êtes-vous sûr ?',
                        text: "Vous ne pourrez pas revenir en arrière !",
                        icon: 'warning', // Corrected icon type
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Oui, supprimez-le !',
                        cancelButtonText: 'Annuler'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
</x-app-layout>
