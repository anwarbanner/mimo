<title>Soins</title>
<x-app-layout>
    <h1 class="text-4xl lg:text-5xl  text-center text-blue-700 mb-6 lg:mb-8"  name="title">Liste des Soins</h1>
    <div class="max-w-6xl mx-auto bg-white p-4 sm:p-6 rounded-lg shadow-md">
        
        <a href="{{ route('soins.create') }}" 
           class="inline-block mb-3 sm:mb-4 px-3 py-2 sm:px-4 sm:py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition duration-300 text-sm sm:text-base">
            Ajouter un Soin
        </a>
        
        @if (session('success'))
            <div class="mb-3 text-green-600 text-sm sm:text-base">{{ session('success') }}</div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-300 rounded-lg overflow-hidden shadow-sm">
                <thead class="bg-gray-100 text-xs sm:text-sm">
                    <tr>
                        <th class="py-2 px-3 sm:py-3 sm:px-4 border-b text-left font-semibold text-gray-700">Nom</th>
                        <th class="py-2 px-3 sm:py-3 sm:px-4 border-b text-left font-semibold text-gray-700">Description</th>
                        <th class="py-2 px-3 sm:py-3 sm:px-4 border-b text-left font-semibold text-gray-700">Prix</th>
                        <th class="py-2 px-3 sm:py-3 sm:px-4 border-b text-left font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($soins as $soin)
                        <tr class="hover:bg-gray-50 transition duration-200">
                            <td class="py-3 px-3 sm:py-4 sm:px-4 border-b text-xs sm:text-sm text-gray-600">{{ $soin->name }}</td>
                            <td class="py-3 px-3 sm:py-4 sm:px-4 border-b text-xs sm:text-sm text-gray-600 truncate" style="max-width: 200px;">
                                {{ $soin->description }}
                            </td>
                            <td class="py-3 px-3 sm:py-4 sm:px-4 border-b text-xs sm:text-sm text-gray-600">{{ $soin->price }}</td>
                            <td class="py-3 px-3 sm:py-4 sm:px-4 border-b text-xs sm:text-sm">
                                <a href="{{ route('soins.edit', $soin->id) }}" 
                                   class="inline-block mb-1 sm:mb-0 px-3 py-1 sm:px-4 sm:py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700 transition duration-200 text-xs sm:text-sm">
                                    Modifier
                                </a>
                                <!--<form action="{{ route('soins.destroy', $soin->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            class="inline-block mt-1 sm:mt-0 px-3 py-1 sm:px-4 sm:py-2 bg-red-600 text-white rounded hover:bg-red-700 transition duration-200 text-xs sm:text-sm delete-button">
                                        Supprimer
                                    </button>
                                </form>-->
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.delete-button').forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault(); // Prevent form submission immediately
                    const form = button.closest('form');
                    
                    Swal.fire({
                        title: 'Êtes-vous sûr ?',
                        text: "Vous ne pourrez pas revenir en arrière !",
                        icon: 'warning', // Corrected icon type
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Oui, supprimez-le !',
                        cancelButtonText: 'Annuler'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>