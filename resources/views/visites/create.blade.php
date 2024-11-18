<x-app-layout>

    <h1>Créer une Visite</h1>

    <form action="{{ route('visites.store') }}" method="POST">
        @csrf
        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Whoops!</strong>
                <span class="block sm:inline">There were some problems with your submission:</span>
                <ul class="mt-2 list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        <input type="hidden" name="id_rdv" value="{{ $rdv->id }}">

        <div>
            <label for="observation">Observation</label>
            <textarea name="observation" id="observation" rows="4"></textarea>
        </div>

        <div>
            <label>Products</label>
            <div id="products">
                <div>
                    <select name="products[0][id]">
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                    <input type="number" name="products[0][quantity]" placeholder="Quantity">
                </div>
            </div>
            <button type="button" onclick="addProduct()">Add Product</button>
        </div>

        <div>
            <label>Soins</label>
            <div id="soins">
                <div>
                    <select name="soins[0][id]">
                        @foreach ($soins as $soin)
                            <option value="{{ $soin->id }}">{{ $soin->name }}</option>
                        @endforeach
                    </select>
                    <input type="number" name="soins[0][quantity]" placeholder="Quantity">
                </div>
            </div>
            <button type="button" onclick="addSoin()">Add Soin</button>
        </div>

        <button type="submit">Submit Visite</button>
    </form>

    <script>
        let productIndex = 1;
        let soinIndex = 1;

        function addProduct() {
            const productDiv = document.createElement('div');
            productDiv.innerHTML = `
                    <select name="products[${productIndex}][id]">
                        @foreach ($products as $product)
                            <option value="{{ $product->id }}">{{ $product->name }}</option>
                        @endforeach
                    </select>
                    <input type="number" name="products[${productIndex}][quantity]" placeholder="Quantity">
                `;
            document.getElementById('products').appendChild(productDiv);
            productIndex++;
        }

        function addSoin() {
            const soinDiv = document.createElement('div');
            soinDiv.innerHTML = `
                    <select name="soins[${soinIndex}][id]">
                        @foreach ($soins as $soin)
                            <option value="{{ $soin->id }}">{{ $soin->name }}</option>
                        @endforeach
                    </select>
                    <input type="number" name="soins[${soinIndex}][quantity]" placeholder="Quantity">
                `;
            document.getElementById('soins').appendChild(soinDiv);
            soinIndex++;
        }
    </script>


</x-app-layout>
