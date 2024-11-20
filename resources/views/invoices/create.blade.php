<x-app-layout>
    <head>
        <link rel="stylesheet" href="{{ asset('css/all.min.css') }}">
    </head>

    <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg p-8 mt-10">
        <h1 class="text-3xl lg:text-4xl font-bold text-center text-blue-700 mb-6">
            {{ isset($invoice) ? 'Modifier Facture' : 'Créer Facture' }}
        </h1>

        <form action="{{ isset($invoice) ? route('invoices.update', $invoice->id) : route('invoices.store') }}" method="POST">
            @csrf
            @if(isset($invoice))
                @method('PUT')
            @endif

            <!-- Patient Selection -->
            <div class="mb-6">
                <label for="patient_id" class="block text-sm font-medium text-gray-700 mb-2">Patient</label>
                <select name="patient_id" id="patient_id" class="block w-full bg-gray-50 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500 p-3">
                    <option value="" disabled selected>-- Sélectionner un patient --</option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}" {{ isset($invoice) && $invoice->patient_id == $patient->id ? 'selected' : '' }}>
                            {{ $patient->id }} - {{ $patient->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Products Selection -->
            <div class="mb-6">
                <label for="products" class="block text-sm font-medium text-gray-700 mb-2">Produits</label>
                <select name="products[]" id="products" class="block w-full bg-gray-50 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500 p-3" multiple>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" data-price="{{ $product->price }}">
                            {{ $product->name }} - {{ $product->price }} DH
                        </option>
                    @endforeach
                </select>
                <small class="text-gray-500">Maintenez la touche CTRL (ou CMD) pour sélectionner plusieurs produits.</small>
            </div>

            <!-- Soins Selection -->
            <div class="mb-6">
                <label for="soins" class="block text-sm font-medium text-gray-700 mb-2">Soins</label>
                <select name="soins[]" id="soins" class="block w-full bg-gray-50 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500 p-3" multiple>
                    @foreach($soins as $soin)
                        <option value="{{ $soin->id }}" data-price="{{ $soin->price }}">
                            {{ $soin->name }} - {{ $soin->price }} DH
                        </option>
                    @endforeach
                </select>
                <small class="text-gray-500">Maintenez la touche CTRL (ou CMD) pour sélectionner plusieurs soins.</small>
            </div>

            <!-- Total Amount -->
            <div class="mb-6">
                <label for="total_amount" class="block text-sm font-medium text-gray-700 mb-2">Montant Total</label>
                <input type="number" name="total_amount" id="total_amount" class="block w-full bg-gray-100 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500 p-3" value="{{ $invoice->total_amount ?? 0 }}" readonly>
            </div>

            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 focus:ring focus:ring-blue-300 transition duration-150">
                    {{ isset($invoice) ? 'Mettre à Jour' : 'Créer Facture' }}
                </button>
            </div>
        </form>
    </div>

    <script>
        function calculateTotal() {
            let productPrices = Array.from(document.getElementById('products').selectedOptions).map(option => parseFloat(option.getAttribute('data-price')) || 0);
            let soinPrices = Array.from(document.getElementById('soins').selectedOptions).map(option => parseFloat(option.getAttribute('data-price')) || 0);

            let totalProducts = productPrices.reduce((a, b) => a + b, 0);
            let totalSoins = soinPrices.reduce((a, b) => a + b, 0);

            document.getElementById('total_amount').value = totalProducts + totalSoins;
        }

        document.getElementById('products').addEventListener('change', calculateTotal);
        document.getElementById('soins').addEventListener('change', calculateTotal);
    </script>
</x-app-layout>
