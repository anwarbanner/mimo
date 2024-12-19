<title>Modifier la Visite</title>
<x-app-layout>
    <div class="max-w-4xl mx-auto p-6 bg-white shadow-lg rounded-lg my-6">

    <h1 class="text-4xl lg:text-5xl font-extrabold text-center text-blue-700 mb-6 lg:mb-8">Modifier la Visite</h1>

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
        @csrf
        @method('PUT')

        <!-- Observation Section -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-6">
            <div class="bg-gray-100 px-6 py-4">
                <h3 class="text-xl font-semibold text-gray-800">Observation</h3>
            </div>
            <div class="px-6 py-4">
                <textarea name="observation" class="w-full p-2 border border-gray-300 rounded-lg" rows="4">{{ old('observation', $visite->observation) }}</textarea>
                @error('observation')
                <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <!-- Produits Utilisés Section -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-6">
            <div class="bg-gray-100 px-6 py-4 flex justify-between">
                <h3 class="text-xl font-semibold text-gray-800">Produits Utilisés</h3>
                <button type="button" class="px-4 py-2 bg-green-500 text-white rounded" onclick="addProduct()">Ajouter Produit</button>
            </div>
            <div class="px-6 py-4" id="products">
                @foreach($visite->invoice->products as $product)
                <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4 product-item">
                    <select name="products[{{ $loop->index }}][id]" class="w-full sm:w-2/3 p-2 border rounded-lg">
                        @foreach($products as $p)
                        <option value="{{ $p->id }}" @if($p->id == $product->id) selected @endif>{{ $p->name }}</option>
                        @endforeach
                    </select>
                    <input type="number" name="products[{{ $loop->index }}][quantity]" value="{{ $product->pivot->quantity }}" class="w-full sm:w-1/3 p-2 border rounded-lg">
                    <button type="button" class="text-red-500 hover:text-red-700" onclick="deleteProduct(this)">Supprimer</button>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Soins Réalisés Section -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-6">
            <div class="bg-gray-100 px-6 py-4 flex justify-between">
                <h3 class="text-xl font-semibold text-gray-800">Soins Réalisés</h3>
                <button type="button" class="px-4 py-2 bg-green-500 text-white rounded" onclick="addSoin()">Ajouter Soin</button>
            </div>
            <div class="px-6 py-4" id="soins">
                @foreach($visite->invoice->soins as $soin)
                <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4 soin-item">
                    <select name="soins[{{ $loop->index }}][id]" class="w-full sm:w-2/3 p-2 border rounded-lg">
                        @foreach($soins as $s)
                        <option value="{{ $s->id }}" @if($s->id == $soin->id) selected @endif>{{ $s->name }}</option>
                        @endforeach
                    </select>
                    <input type="number" name="soins[{{ $loop->index }}][quantity]" value="{{ $soin->pivot->quantity }}" class="w-full sm:w-1/3 p-2 border rounded-lg">
                    <button type="button" class="text-red-500 hover:text-red-700" onclick="deleteSoin(this)">Supprimer</button>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Facturation Section -->
        <div class="bg-white shadow-lg rounded-lg overflow-hidden">
            <div class="bg-gray-100 px-6 py-4">
                <h3 class="text-xl font-semibold text-gray-800">Facturation</h3>
            </div>
            <div class="px-6 py-4">
                <p class="text-gray-700"><strong>Total TTC:</strong> {{ number_format($visite->invoice->total_amount+($visite->invoice->total_amount * 20/100), 2) }} DH</p>
            </div>
        </div>
        <br>
        <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-6">
            <div class="bg-gray-100 px-6 py-4">
                <h3 class="text-xl font-semibold text-gray-800">Images</h3>
            </div>
            <div class="px-6 py-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach ($visite->visiteImages as $image)
                    <div class="image-container">
                        <img src="data:image/png;base64,{{ base64_encode($image->images) }}" alt="Visite Image" class="w-full h-auto rounded-lg object-cover" />
                    </div>
                @endforeach
            </div>
        </div>
        <div class="flex justify-center mt-6">
            <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-md text-sm font-medium hover:bg-blue-700">
                Mettre à jour
            </button>
        </div>
        
    </form>
    <form action="{{ route('visites.show', $visite->id) }}">
        <button type="submit" class="bg-blue-500 text-white px-6 py-3 rounded-md text-sm font-medium hover:bg-blue-600">
            retour
        </button>
    </form>
<br>
    <script>
        let productIndex = {{ $visite->invoice->products->count() }};
        let soinIndex = {{ $visite->invoice->soins->count() }};

        function addProduct() {
            const productDiv = document.createElement('div');
            productDiv.classList.add('flex', 'flex-col', 'sm:flex-row', 'space-y-4', 'sm:space-y-0', 'sm:space-x-4', 'product-item');
            productDiv.innerHTML = `
                <select name="products[${productIndex}][id]" class="w-full sm:w-2/3 p-2 border rounded-lg">
                    @foreach($products as $product)
                    <option value="{{ $product->id }}">{{ $product->name }}</option>
                    @endforeach
                </select>
                <input type="number" name="products[${productIndex}][quantity]" placeholder="Quantité" value="1" class="w-full sm:w-1/3 p-2 border rounded-lg">
                <button type="button" class="text-red-500 hover:text-red-700" onclick="deleteProduct(this)">Supprimer</button>
            `;
            document.getElementById('products').appendChild(productDiv);
            productIndex++;
        }

        function addSoin() {
            const soinDiv = document.createElement('div');
            soinDiv.classList.add('flex', 'flex-col', 'sm:flex-row', 'space-y-4', 'sm:space-y-0', 'sm:space-x-4', 'soin-item');
            soinDiv.innerHTML = `
                <select name="soins[${soinIndex}][id]" class="w-full sm:w-2/3 p-2 border rounded-lg">
                    @foreach($soins as $soin)
                    <option value="{{ $soin->id }}">{{ $soin->name }}</option>
                    @endforeach
                </select>
                <input type="number" name="soins[${soinIndex}][quantity]" placeholder="Quantité" value="1" class="w-full sm:w-1/3 p-2 border rounded-lg">
                <button type="button" class="text-red-500 hover:text-red-700" onclick="deleteSoin(this)">Supprimer</button>
            `;
            document.getElementById('soins').appendChild(soinDiv);
            soinIndex++;
        }

        function deleteProduct(button) {
            button.closest('.product-item').remove();
        }

        function deleteSoin(button) {
            button.closest('.soin-item').remove();
        }
    </script>
</x-app-layout>
