<title>Modifier Facture</title>
<x-app-layout>
    <head>
        <link rel="stylesheet" href="{{ asset('css/all.min.css') }}">
    </head>

    <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-lg p-8 mt-10">
        <h1 class="text-3xl lg:text-4xl font-bold text-center text-blue-700 mb-6">
            Modifier Facture
        </h1>

        <form action="{{ route('invoices.update', $invoice->id) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Patient Selection -->
            <div class="mb-6">
                <label for="patient_id" class="block text-sm font-medium text-gray-700 mb-2">Patient</label>
                <select name="patient_id" id="patient_id" class="block w-full bg-gray-50 border border-gray-300 rounded-lg shadow-sm focus:ring focus:ring-blue-500 focus:border-blue-500 p-3">
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}" {{ $invoice->patient_id == $patient->id ? 'selected' : '' }}>
                            {{ $patient->id }} - {{ $patient->nom }}
                        </option>
                    @endforeach
                </select>
                @error('patient_id')
                    <p class="text-red-500 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <!-- Dynamic Products Section -->
            <div>
                <label class="block text-gray-700 font-medium mb-2">Produits</label>
                <div id="products" class="space-y-4">
                    @foreach($invoice->products as $index => $product)
                        <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4 product-item">
                            <select name="products[{{ $index }}][id]" class="product-select w-full sm:w-2/3 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" onchange="calculateTotal()">
                                <option value="" data-price="0">-- Sélectionner un produit --</option>
                                @foreach($products as $availableProduct)
                                    <option value="{{ $availableProduct->id }}" data-price="{{ $availableProduct->price }}" {{ $product->id == $availableProduct->id ? 'selected' : '' }}>
                                        {{ $availableProduct->name }} ({{ $availableProduct->price }} DH)
                                    </option>
                                @endforeach
                            </select>
                            <input type="number" name="products[{{ $index }}][quantity]" value="{{ $product->pivot->quantity }}" min="1" placeholder="Quantité" class="product-quantity w-full sm:w-1/3 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" oninput="calculateTotal()">
                            <button type="button" onclick="deleteProduct(this)" class="mt-2 text-sm text-red-500 hover:text-red-700 focus:outline-none">Supprimer</button>
                        </div>
                    @endforeach
                </div>
                <button type="button" onclick="addProduct()" class="mt-4 text-sm text-blue-500 hover:text-blue-700 focus:outline-none">+ Ajouter un produit</button>
            </div>

            <!-- Dynamic Soins Section -->
            <div class="mt-6">
                <label class="block text-gray-700 font-medium mb-2">Soins</label>
                <div id="soins" class="space-y-4">
                    @foreach($invoice->soins as $index => $soin)
                        <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4 soin-item">
                            <select name="soins[{{ $index }}][id]" class="soin-select w-full sm:w-2/3 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" onchange="calculateTotal()">
                                <option value="" data-price="0">-- Sélectionner un soin --</option>
                                @foreach($soins as $availableSoin)
                                    <option value="{{ $availableSoin->id }}" data-price="{{ $availableSoin->price }}" {{ $soin->id == $availableSoin->id ? 'selected' : '' }}>
                                        {{ $availableSoin->name }} ({{ $availableSoin->price }} DH)
                                    </option>
                                @endforeach
                            </select>
                            <input type="number" name="soins[{{ $index }}][quantity]" value="{{ $soin->pivot->quantity }}" min="1" placeholder="Quantité" class="soin-quantity w-full sm:w-1/3 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" oninput="calculateTotal()">
                            <button type="button" onclick="deleteSoin(this)" class="mt-2 text-sm text-red-500 hover:text-red-700 focus:outline-none">Supprimer</button>
                        </div>
                    @endforeach
                </div>
                <button type="button" onclick="addSoin()" class="mt-4 text-sm text-blue-500 hover:text-blue-700 focus:outline-none">+ Ajouter un soin</button>
            </div>

            <!-- Total Amount -->
            <div class="mb-6 mt-6">
                <label for="total_amount" class="block text-sm font-medium text-gray-700 mb-2">Montant Total</label>
                <input type="text" id="total_amount" name="total_amount" value="{{ $invoice->total_amount }}" class="block w-full bg-gray-100 border border-gray-300 rounded-lg shadow-sm p-3" readonly>
            </div>
            <div class="mb-6 mt-6">
                <label for="total_amount" class="block text-sm font-medium text-gray-700 mb-2">Montant Total avec TVA ( {{ auth()->user()->tva }} %)</label>
                <input type="text" id="total_amount" name="total_amount" value="{{ $invoice->total_amount + ($invoice->total_amount * auth()->user()->tva/100) }}" class="block w-full bg-gray-100 border border-gray-300 rounded-lg shadow-sm p-3" readonly>
            </div>

            <!-- Submit Button -->
            <div class="text-center">
                <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 focus:ring focus:ring-blue-300 transition duration-150">Mettre à jour Facture</button>
            </div>
        </form>
    </div>

    <script>
        let productIndex = {{ count($invoice->products) }};
        let soinIndex = {{ count($invoice->soins) }};

        // Calculate Total
        function calculateTotal() {
            let total = 0;

            // Calculate products total
            document.querySelectorAll('.product-item').forEach(item => {
                const price = parseFloat(item.querySelector('.product-select option:checked').dataset.price || 0);
                const quantity = parseInt(item.querySelector('.product-quantity').value || 1);
                total += price * quantity;
            });

            // Calculate soins total
            document.querySelectorAll('.soin-item').forEach(item => {
                const price = parseFloat(item.querySelector('.soin-select option:checked').dataset.price || 0);
                const quantity = parseInt(item.querySelector('.soin-quantity').value || 1);
                total += price * quantity;
            });

            document.getElementById('total_amount').value = total.toFixed(2);
        }

        function addProduct() {
    const productDiv = document.createElement('div');
    productDiv.classList.add('flex', 'flex-col', 'sm:flex-row', 'space-y-4', 'sm:space-y-0', 'sm:space-x-4', 'product-item');
    productDiv.innerHTML = `
        <select name="products[${productIndex}][id]" class="product-select w-full sm:w-2/3 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" onchange="calculateTotal()">
            <option value="" data-price="0">-- Sélectionner un produit --</option>
            @foreach($products as $product)
                <option value="{{ $product->id }}" data-price="{{ $product->price }}">{{ $product->name }} ({{ $product->price }} DH)</option>
            @endforeach
        </select>
        <input type="number" name="products[${productIndex}][quantity]" value="1" placeholder="Quantité" min="1" class="product-quantity w-full sm:w-1/3 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" oninput="calculateTotal()">
        <button type="button" onclick="deleteProduct(this)" class="mt-2 text-sm text-red-500 hover:text-red-700 focus:outline-none">Supprimer</button>
    `;
    document.getElementById('products').appendChild(productDiv);
    productIndex++;
    calculateTotal();
}

function addSoin() {
    const soinDiv = document.createElement('div');
    soinDiv.classList.add('flex', 'flex-col', 'sm:flex-row', 'space-y-4', 'sm:space-y-0', 'sm:space-x-4', 'soin-item');
    soinDiv.innerHTML = `
        <select name="soins[${soinIndex}][id]" class="soin-select w-full sm:w-2/3 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" onchange="calculateTotal()">
            <option value="" data-price="0">-- Sélectionner un soin --</option>
            @foreach($soins as $soin)
                <option value="{{ $soin->id }}" data-price="{{ $soin->price }}">{{ $soin->name }} ({{ $soin->price }} DH)</option>
            @endforeach
        </select>
        <input type="number" name="soins[${soinIndex}][quantity]" value="1" placeholder="Quantité" min="1" class="soin-quantity w-full sm:w-1/3 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" oninput="calculateTotal()">
        <button type="button" onclick="deleteSoin(this)" class="mt-2 text-sm text-red-500 hover:text-red-700 focus:outline-none">Supprimer</button>
    `;
    document.getElementById('soins').appendChild(soinDiv);
    soinIndex++;
    calculateTotal();
}


        function deleteProduct(button) {
            button.parentElement.remove();
            calculateTotal();
        }

        function deleteSoin(button) {
            button.parentElement.remove();
            calculateTotal();
        }

        document.querySelectorAll('.product-select, .soin-select').forEach(select => select.addEventListener('change', calculateTotal));
        document.querySelectorAll('.product-quantity, .soin-quantity').forEach(input => input.addEventListener('input', calculateTotal));

        // Initial calculation of total on page load
        calculateTotal();
    </script>
</x-app-layout>
