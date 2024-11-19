<x-app-layout>
    <h1 class="text-4xl lg:text-5xl font-extrabold text-center text-blue-700 mb-6 lg:mb-8">Liste des Produits</h1>
    <div class="max-w-full mx-auto bg-white p-4 sm:p-6 rounded-lg shadow-md">
        <a href="{{ route('products.create') }}"
           class="inline-block mb-3 sm:mb-4 px-4 py-2 sm:px-6 sm:py-3 bg-indigo-600 text-white rounded-md sm:rounded-lg hover:bg-indigo-700 transition duration-300 transform hover:scale-105 text-sm sm:text-base">
            Ajouter un Produit
        </a>

        @if (session('success'))
            <div class="mb-3 text-green-600 text-sm sm:text-base">{{ session('success') }}</div>
        @endif

        <!-- Grid of product cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            @foreach ($products as $product)
                <div class="bg-gradient-to-t from-gray-100 to-white border border-gray-300 rounded-lg shadow-md overflow-hidden hover:shadow-xl transform hover:scale-105 transition duration-300">
                    <div class="relative p-3 sm:p-4">

                        <!-- Product Image -->
                        <div class="mb-3 sm:mb-4 flex justify-center">
                            @if ($product->image)
                                <img src="data:image/jpeg;base64,{{ $product->image }}" alt="Product Image"
                                     class="w-24 h-24 sm:w-32 sm:h-32 object-cover rounded-lg shadow-md border-4 border-indigo-500 transform transition duration-200 hover:scale-105">
                            @else
                                <p class="text-gray-500 text-sm">No image</p>
                            @endif
                        </div>

                        <!-- Product Name -->
                        <h2 class="text-lg sm:text-xl font-bold text-gray-800 mb-2 truncate">{{ $product->name }}</h2>

                        <!-- Product Description -->
                        <p class="text-sm text-gray-600 mb-3 sm:mb-4 truncate" style="max-width: 220px;">{{ $product->description }}</p>

                        <!-- Product Price -->
                        <p class="text-lg font-semibold text-indigo-700 mb-3 sm:mb-4">{{ $product->price }} DH</p>

                        <!-- Actions -->
                        <div class="flex justify-between items-center">
                            <a href="{{ route('products.edit', $product->id) }}"
                               class="inline-block text-xs sm:text-sm px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition duration-200 transform hover:scale-105">
                                Modifier
                            </a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="inline-block text-xs sm:text-sm px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700 transition duration-200 transform hover:scale-105">
                                    Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
