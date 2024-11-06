<x-app-layout>
<x-slot name="title">Liste des Produit</x-slot>



    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
    <h1 class="text-4xl lg:text-5xl font-extrabold text-center text-blue-700 mb-6 lg:mb-8">Liste des Produits</h1>
        <a href="{{ route('products.create') }}" class="inline-block mb-4 px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition duration-300">Ajouter un Produit</a>

        @if (session('success'))
        <div class="mb-4 text-green-600">{{ session('success') }}</div>
        @endif

        <table class="min-w-full bg-white border border-gray-300 rounded-lg overflow-hidden shadow-sm">
            <thead>
                <tr class="bg-gray-100">
                    <th class="py-3 px-4 border-b text-left text-sm font-semibold text-gray-700">Nom</th>
                    <th class="py-3 px-4 border-b text-left text-sm font-semibold text-gray-700">Image</th>
                    <th class="py-3 px-4 border-b text-left text-sm font-semibold text-gray-700">Description</th>
                    <th class="py-3 px-4 border-b text-left text-sm font-semibold text-gray-700">Prix</th>
                    <th class="py-3 px-4 border-b text-left text-sm font-semibold text-gray-700">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr class="hover:bg-gray-50 transition duration-200">
                        <td class="py-4 px-4 border-b text-sm text-gray-600">{{ $product->name }}</td>
                        <td class="py-4 px-4 border-b text-sm text-gray-600">
    @if ($product->image)
        <img src="data:image/jpeg;base64,{{ $product->image }}" alt="Product Image" class="w-20 h-20 object-cover rounded-lg shadow-sm">
    @else
        <p class="text-gray-500">No image available</p>
    @endif
</td>

                        <td class="py-4 px-4 border-b text-sm text-gray-600">{{ $product->description }}</td>
                        <td class="py-4 px-4 border-b text-sm text-gray-600">{{ $product->price }}</td>
                        <td class="py-4 px-4 border-b text-sm">
                            <a href="{{ route('products.edit', $product->id) }}" class="inline-block px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition duration-200">Modifier</a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-block px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition duration-200 ml-4">Supprimer</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</body>
</x-app-layout>
