<x-app-layout>
<div class="container">
    <h1>Editer Facture</h1>

    <form action="{{ route('invoices.update', $invoice->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label for="patient_id">Patient</label>
            <select name="patient_id" id="patient_id" class="form-control">
                @foreach($patients as $patient)
                    <option value="{{ $patient->id }}" {{ $invoice->patient_id == $patient->id ? 'selected' : '' }}>
                    {{ $patient->id }}-{{ $patient->nom }}
                    </option>
                @endforeach
            </select>
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

        <button type="submit" class="btn btn-primary">{{ isset($invoice) ? 'MISE A JOUR' : 'Create' }} FACTURE</button>
    </form>
</div>
       
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