<x-app-layout>
    
    <div class="max-w-4xl mx-auto bg-white p-6 rounded-lg shadow-md">
        <h1 class="text-2xl font-bold mb-4">Liste des Soins</h1>
        <a href="{{ route('soins.create') }}" class="inline-block mb-4 px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition duration-300">Ajouter un Soin</a>
       
        @if (session('success'))
        <div class="mb-4 text-green-600">{{ session('success') }}</div>
        @endif
        
        <table class="min-w-full bg-white border border-gray-300 rounded-lg overflow-hidden shadow-sm">
            <thead>
                <tr class="bg-gray-100">
                    <th class="py-3 px-4 border-b text-left text-sm font-semibold text-gray-700">Nom</th>
                    <th class="py-3 px-4 border-b text-left text-sm font-semibold text-gray-700">Description</th>
                    <th class="py-3 px-4 border-b text-left text-sm font-semibold text-gray-700">Prix</th>
                    <th class="py-3 px-4 border-b text-left text-sm font-semibold text-gray-700">Actions</th>
                </tr>
        
</td>

@foreach ($soins as $soin)
    <tr>
        <td class="py-4 px-4 border-b text-sm text-gray-600">{{ $soin->name }}</td>
        <td class="py-4 px-4 border-b text-sm text-gray-600">{{ $soin->description }}</td>
        <td class="py-4 px-4 border-b text-sm text-gray-600">{{ $soin->price }}</td>
        <td class="py-4 px-4 border-b text-sm">
            <a href="{{ route('soins.edit', $soin->id) }}" class="inline-block px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition duration-200">Modifier</a>
            <form action="{{ route('soins.destroy', $soin->id) }}" method="POST" style="display:inline;">
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