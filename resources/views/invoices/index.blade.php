<title>Facture</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>


<x-app-layout>
    <div class="container-fluid px-7">
        <!-- Heading Section -->
        <div class="row justify-content-center mb-5">
            <div class="col-lg-8 col-md-10 col-sm-12">
                <h1 class="display-4 text-center text-blue-700">Liste des Factures</h1>
            </div>
        </div>

        <!-- Create Invoice Button -->
        <div class="row mb-4">
            <div class="col-12 text-left">
                <a href="{{ route('invoices.create') }}" class="btn btn-primary">
                  Créer une facture
                </a>
            </div>
        </div>

        <!-- Search Form -->
        <div class="row mb-4">
            <div class="col-12 col-md-6 col-lg-4 mx-auto">
                <form action="{{ route('invoices.index') }}" method="GET">
                    <div class="input-group">
                        <input type="text" name="search" id="search" class="form-control" placeholder="Entrez l'ID de facture" value="{{ request('search') }}">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Invoices Table -->
        <div class="row">
            <div class="col-12 table-responsive">
                <table class="table table-striped table-bordered">
                    <thead class="thead-dark">
                        <tr>
                            <th>ID FACTURE</th>
                            <th>Patient</th>
                            <th>Total Amount</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($invoices as $invoice)
                            <tr>
                                <td>{{ $invoice->id }}</td>
                                <td>{{ $invoice->patient->id }} - {{ $invoice->patient->nom }} {{ $invoice->patient->prenom }}</td>
                                <td>{{ $invoice->total_amount }} DH</td>
                                <td>
                                    <!-- Button to Open Modal -->
                                    <button type="button" class="btn btn-outline-primary bg-white" data-bs-toggle="modal" data-bs-target="#actionModal{{ $invoice->id }}">
                                        Actions
                                    </button>

                                    <!-- Modal -->
                                    <div class="modal fade" id="actionModal{{ $invoice->id }}" tabindex="-1" aria-labelledby="actionModalLabel{{ $invoice->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content rounded-3 shadow-lg">
                                                <div class="modal-header">
                                                    <h5 class="modal-title text-dark" id="actionModalLabel{{ $invoice->id }}">
                                                        Actions pour la facture <strong>#{{ $invoice->id }}</strong>
                                                    </h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="row g-3">
                                                        <!-- View Button -->
                                                        <div class="col-12 col-md-6">
                                                            <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-outline-info w-100 py-3">
                                                                <i class="bi bi-eye"></i> Voir
                                                            </a>
                                                        </div>
                                                        <!-- Edit Button -->
                                                        <div class="col-12 col-md-6">
                                                            <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-outline-warning w-100 py-3">
                                                                <i class="bi bi-pencil"></i> Editer
                                                            </a>
                                                        </div>
                                                        <!-- Delete Button -->
                                                        <div class="col-12 col-md-6">
                                                            <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" class="w-100">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-outline-danger w-100 py-3">
                                                                    <i class="bi bi-trash"></i> Supprimer
                                                                </button>
                                                            </form>
                                                        </div>
                                                        <!-- Download PDF Button -->
                                                        <div class="col-12 col-md-6">
                                                            <a href="{{ route('invoices.download-pdf', $invoice->id) }}" class="btn btn-outline-success w-100 py-3">
                                                                <i class="bi bi-file-earmark-pdf"></i> Télécharger PDF
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                        <i class="bi bi-x-circle"></i> Fermer
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Aucune facture trouvée.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
