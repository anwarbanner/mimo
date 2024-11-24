<title>Modifier la Visite</title>
<x-app-layout>
   <h1 class="text-4xl lg:text-5xl font-extrabold text-center text-blue-700 mb-6 lg:mb-8"  name="title">Modifier la Visite</h1>

    <!-- Rendez-vous Information Section -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-6">
        <div class="bg-gray-100 px-6 py-4">
            <h3 class="text-xl font-semibold text-gray-800">Informations sur le Rendez-vous</h3>
        </div>
        <div class="px-6 py-4">
            <p><strong class="text-gray-600">Motif:</strong> <span class="text-gray-800">{{ $visite->rdv->title }}</span></p>
            <p><strong class="text-gray-600">Date:</strong> <span class="text-gray-800">{{ $visite->rdv->start->format('d-m-Y H:i') }}</span></p>
            <p><strong class="text-gray-600">Patient:</strong> <span class="text-gray-800">{{ $visite->rdv->patient->nom }} {{ $visite->rdv->patient->prenom }}</span></p>
        </div>
    </div>
<form method="POST" action="{{ route('visites.update', $visite->id) }}">
    <!-- Observation Section -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-6">
        <div class="bg-gray-100 px-6 py-4">
            <h3 class="text-xl font-semibold text-gray-800">Observation</h3>
        </div>
        <div class="px-6 py-4">
            
                @csrf
                @method('PUT')
                <textarea name="observation" class="w-full p-2 border border-gray-300 rounded-lg" rows="4">{{ old('observation', $visite->observation) }}</textarea>
                @error('observation')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
               
        </div>
    </div>

    {{-- <!-- Produits Utilisés Section -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-6">
        <div class="bg-gray-100 px-6 py-4">
            <h3 class="text-xl font-semibold text-gray-800">Produits Utilisés</h3>
        </div>
        <div class="px-6 py-4">
            @if($visite->invoice->products->isNotEmpty())
                <table class="min-w-full table-auto border-collapse">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left text-gray-700 border-b">Produit</th>
                            <th class="px-4 py-2 text-left text-gray-700 border-b">Quantité</th>
                            <th class="px-4 py-2 text-left text-gray-700 border-b">Prix Unitaire</th>
                            <th class="px-4 py-2 text-left text-gray-700 border-b">Total</th>
                            <th class="px-4 py-2 text-left text-gray-700 border-b">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($visite->invoice->products as $product)
                            <tr class="bg-gray-50 hover:bg-gray-100">
                                <td class="px-4 py-2 text-gray-700 border-b">{{ $product->name }}</td>
                                <td class="px-4 py-2 text-gray-700 border-b">
                                    <input type="number" name="products[{{ $product->id }}][quantity]" value="{{ $product->pivot->quantity }}" class="w-16 p-1 border border-gray-300 rounded">
                                </td>
                                <td class="px-4 py-2 text-gray-700 border-b">{{ number_format($product->price, 2) }} DH</td>
                                <td class="px-4 py-2 text-gray-700 border-b">{{ number_format($product->price * $product->pivot->quantity, 2) }} DH</td>
                                <td class="px-4 py-2 text-gray-700 border-b">
                                    <button type="button" class="text-red-500">Supprimer</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-gray-600">Aucun produit utilisé.</p>
            @endif
        </div>
    </div>

    <!-- Soins Réalisés Section -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-6">
        <div class="bg-gray-100 px-6 py-4">
            <h3 class="text-xl font-semibold text-gray-800">Soins Réalisés</h3>
        </div>
        <div class="px-6 py-4">
            @if($visite->invoice->soins->isNotEmpty())
                <table class="min-w-full table-auto border-collapse">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left text-gray-700 border-b">Soin</th>
                            <th class="px-4 py-2 text-left text-gray-700 border-b">Quantité</th>
                            <th class="px-4 py-2 text-left text-gray-700 border-b">Prix Unitaire</th>
                            <th class="px-4 py-2 text-left text-gray-700 border-b">Total</th>
                            <th class="px-4 py-2 text-left text-gray-700 border-b">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($visite->invoice->soins as $soin)
                            <tr class="bg-gray-50 hover:bg-gray-100">
                                <td class="px-4 py-2 text-gray-700 border-b">{{ $soin->name }}</td>
                                <td class="px-4 py-2 text-gray-700 border-b">
                                    <input type="number" name="soins[{{ $soin->id }}][quantity]" value="{{ $soin->pivot->quantity }}" class="w-16 p-1 border border-gray-300 rounded">
                                </td>
                                <td class="px-4 py-2 text-gray-700 border-b">{{ number_format($soin->price, 2) }} DH</td>
                                <td class="px-4 py-2 text-gray-700 border-b">{{ number_format($soin->price * $soin->pivot->quantity, 2) }} DH</td>
                                <td class="px-4 py-2 text-gray-700 border-b">
                                    <button type="button" class="text-red-500">Supprimer</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-gray-600">Aucun soin réalisé.</p>
            @endif
        </div>
    </div> --}}
    <!-- Produits Section -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-6">
        <h3 class="text-xl font-semibold text-gray-800"><label class="block text-gray-700 font-medium mb-2">Produits</label></h3>
        <div id="products" class="space-y-4">
            @foreach ($visite->invoice->products as $index => $product)
                <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4" data-index="{{ $index }}">
                    <select name="products[{{ $index }}][id]" class="w-full sm:w-2/3 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @foreach ($products as $prod)
                            <option value="{{ $prod->id }}" {{ $prod->id == $product->id ? 'selected' : '' }}>{{ $prod->name }}</option>
                        @endforeach
                    </select>
                    <input type="number" name="products[{{ $index }}][quantity]" value="{{ $product->pivot->quantity }}" placeholder="Quantité"
                        class="w-full sm:w-1/3 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <button type="button" onclick="removeProduct({{ $index }})" class="mt-2 text-red-500 hover:text-red-700 focus:outline-none">Supprimer</button>
                </div>
            @endforeach
        </div>
        <button type="button" onclick="addProduct()"
            class="mt-4 text-sm text-blue-500 hover:text-blue-700 focus:outline-none">
            + Ajouter un produit
        </button>
    </div>

    <!-- Soins Section -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-6">
        <h3 class="text-xl font-semibold text-gray-800"><label class="block text-gray-700 font-medium mb-2">Soins</label></h3>
        <div id="soins" class="space-y-4">
            @foreach ($visite->invoice->soins as $index => $soin)
                <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4" data-index="{{ $index }}">
                    <select name="soins[{{ $index }}][id]" class="w-full sm:w-2/3 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        @foreach ($soins as $soinItem)
                            <option value="{{ $soinItem->id }}" {{ $soinItem->id == $soin->id ? 'selected' : '' }}>{{ $soinItem->name }}</option>
                        @endforeach
                    </select>
                    <input type="number" name="soins[{{ $index }}][quantity]" value="{{ $soin->pivot->quantity }}" placeholder="Quantité"
                        class="w-full sm:w-1/3 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <button type="button" onclick="removeSoin({{ $index }})" class="mt-2 text-red-500 hover:text-red-700 focus:outline-none">Supprimer</button>
                </div>
            @endforeach
        </div>
        <button type="button" onclick="addSoin()"
            class="mt-4 text-sm text-blue-500 hover:text-blue-700 focus:outline-none">
            + Ajouter un soin
        </button>
    </div>

    <script>
        let productIndex = {{ count($visite->invoice->products) }};
        let soinIndex = {{ count($visite->invoice->soins) }};

        function addProduct() {
            const productDiv = document.createElement('div');
            productDiv.classList.add('flex', 'flex-col', 'sm:flex-row', 'space-y-4', 'sm:space-y-0', 'sm:space-x-4');
            productDiv.setAttribute('data-index', productIndex);
            productDiv.innerHTML = `
                <select name="products[${productIndex}][id]" class="w-full sm:w-2/3 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
                <input type="number" name="products[${productIndex}][quantity]" placeholder="Quantité"
                    class="w-full sm:w-1/3 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <button type="button" onclick="removeProduct(${productIndex})" class="mt-2 text-red-500 hover:text-red-700 focus:outline-none">Supprimer</button>
            `;
            document.getElementById('products').appendChild(productDiv);
            productIndex++;
        }

        function addSoin() {
            const soinDiv = document.createElement('div');
            soinDiv.classList.add('flex', 'flex-col', 'sm:flex-row', 'space-y-4', 'sm:space-y-0', 'sm:space-x-4');
            soinDiv.setAttribute('data-index', soinIndex);
            soinDiv.innerHTML = `
                <select name="soins[${soinIndex}][id]" class="w-full sm:w-2/3 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    @foreach ($soins as $soin)
                        <option value="{{ $soin->id }}">{{ $soin->name }}</option>
                    @endforeach
                </select>
                <input type="number" name="soins[${soinIndex}][quantity]" placeholder="Quantité"
                    class="w-full sm:w-1/3 p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <button type="button" onclick="removeSoin(${soinIndex})" class="mt-2 text-red-500 hover:text-red-700 focus:outline-none">Supprimer</button>
            `;
            document.getElementById('soins').appendChild(soinDiv);
            soinIndex++;
        }

        function removeProduct(index) {
            const productDiv = document.querySelector(`#products [data-index="${index}"]`);
            productDiv.remove();
        }

        function removeSoin(index) {
            const soinDiv = document.querySelector(`#soins [data-index="${index}"]`);
            soinDiv.remove();
        }
    </script>


    <!-- Facturation Section -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="bg-gray-100 px-6 py-4">
            <h3 class="text-xl font-semibold text-gray-800">Facturation</h3>
        </div>
        <div class="px-6 py-4">
            <p class="text-gray-700"><strong>Total:</strong> {{ number_format($visite->invoice->total_amount, 2) }} DH</p>
        </div>
    </div>

    {{-- <!-- Submit Button to Save the Updates -->
    <div class="mt-6 text-center">
        <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-lg">Sauvegarder les Modifications</button>
    </div> --}}
    <button type="submit" class="mt-4 px-6 py-2 bg-blue-600 text-white rounded-lg">Mettre à jour</button> 
            </form>
</x-app-layout>
