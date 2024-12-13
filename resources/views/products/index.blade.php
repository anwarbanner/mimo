<title>Produits</title>
<x-app-layout>
    <h1 class="text-4xl lg:text-5xl text-center text-blue-700 mb-6 lg:mb-8">Liste des Produits</h1>
    <div class="max-w-full mx-auto bg-white p-4 sm:p-6 rounded-lg shadow-lg">
        <form action="{{ route('products.create') }}" method="GET">
            <button type="submit"
                class="inline-block mb-3 sm:mb-4 px-6 py-3 bg-indigo-600 text-white rounded-md sm:rounded-lg hover:bg-indigo-700 transition duration-300 transform hover:scale-105 text-sm sm:text-base flex items-center justify-center">
                <i class="fas fa-plus-circle mr-2"></i> Ajouter un Produit
            </button>
        </form>
        

        @if (session('success'))
            <div class="mb-3 text-green-600 text-sm sm:text-base">{{ session('success') }}</div>
        @endif

        <!-- Grid of product cards -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
    @foreach ($products as $product)
        <div class="max-w-xs rounded overflow-hidden shadow-lg bg-white border border-gray-300 transform hover:scale-105 transition duration-300">
            <div class="relative">

                <!-- Product Image -->
                <div class="flex justify-center mb-4">
                    @if ($product->image)
                        <img src="data:image/jpeg;base64,{{ $product->image }}" alt="Product Image"
                             class="w-full h-36 object-cover rounded-t-lg shadow-md">
                    @else
                        <div class="w-full h-36 bg-gray-200 flex items-center justify-center rounded-t-lg">
                            <p class="text-gray-500 text-sm">No image</p>
                        </div>
                    @endif
                </div>

                <div class="px-4 py-3">

                    <!-- Product Name -->
                    <div class="font-bold text-lg mb-2 text-gray-800">{{ $product->name }}</div>

                    <!-- Product Price -->
                    <p class="text-lg font-semibold text-indigo-600">{{ $product->price }} DH</p>

                    <!-- Product Description Button -->
                    <div class="flex justify-center items-center">
                    <button type="button" class="mx-auto px-4 py-2 bg-green-600 text-white text-xs rounded-full hover:bg-green-700 transition duration-200 transform" data-bs-toggle="modal" data-bs-target="#productModal-{{ $product->id }}">
                        Voir la description du produit
                      </button>
                    </div>
                </div>

                <!-- Product Actions -->
                <div class="px-4 pt-3 pb-2 flex justify-between items-center">
                    <form action="{{ route('products.edit', $product->id) }}" method="GET">
                        <button type="submit" class="inline-block px-4 py-2 bg-indigo-600 text-white text-xs rounded-full hover:bg-indigo-700 transition duration-200 transform">
                            <i class="fas fa-edit mr-2"></i> Modifier
                        </button>
                    </form>
                    
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-block px-4 py-2 bg-red-600 text-white text-xs rounded-full hover:bg-red-700 transition duration-200 transform">
                            <i class="fas fa-trash-alt mr-2"></i> Supprimer
                        </button>
                    </form>
                </div>

            </div>
        </div>

        <!-- Modal for Product Description (Popup) -->
       <!-- Modal for Product Description (Popup) -->
<div class="modal fade" id="productModal-{{ $product->id }}" tabindex="-1" aria-labelledby="productModalLabel-{{ $product->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content border-0 shadow-lg rounded-lg">
        <!-- Modal Header -->
        <div class="modal-header bg-indigo-600 text-white rounded-t-lg">
          <h5 class="modal-title" id="productModalLabel-{{ $product->id }}">{{ $product->name }}</h5>
          <button type="button" class="btn-close text-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <!-- Modal Body -->
        <div class="modal-body bg-gray-100 p-6">
          <p class="text-gray-800 text-sm">{{ $product->description }}</p>
        </div>
        
      </div>
    </div>
  </div>
  
    @endforeach
</div>



    </div>
</x-app-layout>
<!-- Add Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Add Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

