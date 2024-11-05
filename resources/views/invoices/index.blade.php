
<x-app-layout>

<div class="container">
<h1 class="text-4xl lg:text-5xl font-extrabold text-center text-blue-700 mb-6 lg:mb-8">Liste des Factures</h1>
    <a href="{{ route('invoices.create') }}" class="btn btn-primary mb-3">Créer une facture</a>

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
            @foreach ($invoices as $invoice)
            <tr>
                <td>{{ $invoice->id }}</td>
                <td>{{ $invoice->patient->nom }}</td>
                <td>{{ $invoice->total_amount }} DH</td>
                <td>
                    <a href="{{ route('invoices.show', $invoice->id) }}" class="btn btn-info">voir</a>
                    <a href="{{ route('invoices.edit', $invoice->id) }}" class="btn btn-warning">Editer</a>
                    <form action="{{ route('invoices.destroy', $invoice->id) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">suprimer</button>
                    </form>
                    <a href="{{ route('invoices.download-pdf', $invoice->id) }}" class="btn btn-success">Download PDF</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

</x-app-layout>
