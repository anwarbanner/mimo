<title>Patients</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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

        <!-- Search Form -->
        <div class="row mb-4">
            <div class="col-12 col-md-6 col-lg-4 mx-auto">
                <input type="text" id="patient_search" class="form-control" placeholder="Chercher par ID, nom ou prénom">
            </div>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <!-- Patients Table -->
        <div class="row">
            <div class="col-12 table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Sexe</th>
                            <th>Date de Naissance</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="patient_list">
                        @foreach ($patients as $patient)
                            <tr class="patient-row" data-id="{{ $patient->id }}" data-name="{{ $patient->nom }} {{ $patient->prenom }}">
                                <td>{{ $patient->id }}</td>
                                <td>{{ $patient->nom }}</td>
                                <td>{{ $patient->prenom }}</td>
                                <td>{{ $patient->sexe == 'M' ? 'M' : 'F' }}</td>
                                <td>{{ \Carbon\Carbon::parse($patient->date_naissance)->format('d/m/Y') }}</td>
                                <td>
                                    <!-- Button to Open Modal -->
                                    <button type="button" class="btn btn-primary  shadow-sm" data-bs-toggle="modal" data-bs-target="#actionModal{{ $patient->id }}">
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
                                                                <button type="submit" class="btn btn-outline-danger w-100 py-3 px-4 shadow-sm hover-shadow">
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

    <!-- JavaScript for Search Functionality -->
    <script>
        document.getElementById('patient_search').addEventListener('input', function() {
            var searchValue = this.value.toLowerCase();
            var patientRows = document.querySelectorAll('.patient-row');

            patientRows.forEach(function(row) {
                var patientID = row.getAttribute('data-id').toLowerCase();
                var patientName = row.getAttribute('data-name').toLowerCase();
                if (patientID.includes(searchValue) || patientName.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });
    </script>
</x-app-layout>
