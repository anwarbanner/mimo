<x-app-layout>
<div class="container">
    <h1 class="text-4xl lg:text-5xl font-extrabold text-center text-blue-700 mb-6 lg:mb-8">Liste des Factures</h1>
    
    <!-- Search Form --> <a href="{{ route('invoices.create') }}" class="btn btn-primary mb-3">Créer une facture</a>

    <form action="{{ route('invoices.index') }}" method="GET" class="mb-4">
        <div class="form-group">
            <label for="search" class="form-label">Rechercher par ID de facture:</label>
            <input type="text" name="search" id="search" class="form-control" placeholder="Entrez l'ID de facture" value="{{ request('search') }}">
        </div>
        <button type="submit" class="btn btn-primary mt-2">Rechercher</button>
    </form>

   
    <table class="table">
        <thead>
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
                <td>{{ $invoice->patient->nom }}</td>
                <td>{{ $invoice->total_amount }} DH</td>
                <td>
                    <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-info">Voir</a>
                    <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-warning">Editer</a>
                    <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Supprimer</button>
                    </form>
                    <a href="{{ route('invoices.download-pdf', $invoice->id) }}" class="btn btn-success">Télécharger PDF</a>
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
</x-app-layout>
