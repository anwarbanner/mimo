<x-app-layout>
<head>
    <link rel="stylesheet" href="{{ asset('css/all.min.css') }}">
</head>
<div class="container">
    <h1 class="text-4xl lg:text-5xl font-extrabold text-center text-blue-700 mb-6 lg:mb-8">Créer Factures</h1>

    <form action="{{ isset($invoice) ? route('invoices.update', $invoice->id) : route('invoices.store') }}" method="POST">
        @csrf
        @if(isset($invoice))
            @method('PUT')
        @endif

        <div class="form-group mb-4">
            <label for="patient_id" class="form-label">Patient</label>
            <select name="patient_id" id="patient_id" class="form-control">
                @foreach($patients as $patient)
                    <option value="{{ $patient->id }}" {{ isset($invoice) && $invoice->patient_id == $patient->id ? 'selected' : '' }}>
                        {{ $patient->id }} - {{ $patient->nom }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group mb-4">
            <label for="consultation_price" class="form-label">Prix de La Consultation</label>
            <input type="number" name="consultation_price" id="consultation_price" class="form-control" value="{{ $invoice->consultation_price ?? 0 }}">
        </div>

        <div class="form-group mb-4">
            <label for="products" class="form-label">Produits</label>
            <select name="products[]" id="products" class="form-control" multiple>
                @foreach($products as $product)
                    <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                        {{ $product->name }} - {{ $product->price }} DH
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group mb-4">
            <label for="soins" class="form-label">Soins</label>
            <select name="soins[]" id="soins" class="form-control" multiple>
                @foreach($soins as $soin)
                    <option value="{{ $soin->id }}" data-price="{{ $soin->price }}">
                        {{ $soin->name }} - {{ $soin->price }} DH
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group mb-4">
            <label for="total_amount" class="form-label">Montant Total</label>
            <input type="number" name="total_amount" id="total_amount" class="form-control" value="{{ $invoice->total_amount ?? 0 }}" readonly>
        </div>

        <button type="submit" class="btn btn-primary">{{ isset($invoice) ? '' : '' }} creer facture</button>
    </form>
</div>

<script>
    function calculateTotal() {
        let consultationPrice = parseFloat(document.getElementById('consultation_price').value) || 0;
        let productPrices = Array.from(document.getElementById('products').selectedOptions).map(option => parseFloat(option.getAttribute('data-price')) || 0);
        let soinPrices = Array.from(document.getElementById('soins').selectedOptions).map(option => parseFloat(option.getAttribute('data-price')) || 0);

        let totalProducts = productPrices.reduce((a, b) => a + b, 0);
        let totalSoins = soinPrices.reduce((a, b) => a + b, 0);

        document.getElementById('total_amount').value = consultationPrice + totalProducts + totalSoins;
    }

    document.getElementById('consultation_price').addEventListener('input', calculateTotal);
    document.getElementById('products').addEventListener('change', calculateTotal);
    document.getElementById('soins').addEventListener('change', calculateTotal);
</script>
</x-app-layout>
