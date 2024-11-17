<x-app-layout>
    
    <div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-6">Modifier le Soin</h1>
        
        <form action="{{ route('soins.update', $soin->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Nom</label>
                <input type="text" name="name" id="name" value="{{ $soin->name }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2">
            </div>
            
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                <textarea name="description" id="description" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2">{{ $soin->description }}</textarea>
            </div>
            
            <div class="mb-4">
                <label for="price" class="block text-sm font-medium text-gray-700">Prix</label>
                <input type="text" name="price" id="price" value="{{ $soin->price }}" required class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 p-2">
            </div>
            
        
            
          
                <button type="submit" class="w-full bg-indigo-600 text-white font-bold py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-opacity-50">Mettre à jour Produit</button>
            </form>
        </div>
        
    </body>

</x-app-layout>