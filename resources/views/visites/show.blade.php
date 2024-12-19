<title>Détails de la Visite</title>
<x-app-layout>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <!-- Add Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Add Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

    <div class="max-w-4xl mx-auto p-6 bg-white shadow-lg rounded-lg my-6">
    <h1 class="text-4xl lg:text-5xl text-center text-blue-700 mb-6 lg:mb-8" name="title">
        Détails de la Visite
    </h1>

  <!-- Rendez-vous Information Section -->
<div class="bg-gradient-to-br from-white to-gray-50 rounded-xl mb-8 border border-gray-300">
    <div class="bg-blue-500 text-white px-6 py-4 rounded-t-xl flex items-center">
        <i class="fas fa-calendar-alt text-3xl mr-3"></i>
        <span class="text-xl font-semibold">Informations sur le Rendez-vous</span>
    </div>
    <div class="px-6 py-6 space-y-4">
        <div class="flex items-center">
            <i class="fas fa-file-alt text-blue-500 text-xl mr-4"></i>
            <div>
                <strong class="block text-gray-700">Motif</strong>
                <span class="text-gray-900">{{ $visite->rdv->title }}</span>
            </div>
        </div>
        <hr>
        <div class="flex items-center">
            <i class="fas fa-calendar-day text-blue-500 text-xl mr-4"></i>
            <div>
                <strong class="block text-gray-700">Date</strong>
                <span class="text-gray-900">{{ $visite->rdv->start->format('d-m-Y H:i') }}</span>
            </div>
        </div>
        <hr>
        <div class="flex items-center">
            <i class="fas fa-user text-blue-500 text-xl mr-4"></i>
            <div>
                <strong class="block text-gray-700">Patient</strong>
                <span class="text-gray-900">{{ $visite->rdv->patient->nom }} {{ $visite->rdv->patient->prenom }}</span>
            </div>
        </div>
    </div>
</div>



   <!-- Observation Section -->
<div class="bg-gradient-to-br from-white to-gray-50 rounded-xl mb-8 border border-gray-300">
    <div class="bg-blue-500 text-white px-6 py-4 rounded-t-xl flex items-center">
        <i class="fas fa-eye text-3xl mr-3"></i>
        <span class="text-xl font-semibold">Observation</span>
    </div>
    <div class="px-6 py-6">
        <p class="text-gray-900 text-base">
            {{ $visite->observation ?? 'Aucune observation.' }}
        </p>
    </div>
</div>


   <!-- Images Section -->
<div class="bg-gradient-to-br from-white to-gray-50 rounded-xl mb-8 border border-gray-300">
    <div class="bg-blue-500 text-white px-6 py-4 rounded-t-xl flex items-center">
        <i class="fas fa-images text-3xl mr-3"></i>
        <span class="text-xl font-semibold">Images</span>
    </div>
    <div class="px-6 py-6 overflow-x-auto">
        <div class="flex space-x-4 overflow-x-auto px-6 py-4">
            @foreach ($visite->visiteImages as $image)
                <div class="image-container flex-shrink-0 w-64">
                    <!-- Thumbnail Image -->
                    <img src="data:image/png;base64,{{ base64_encode($image->images) }}" 
                         alt="Visite Image" 
                         class="w-full h-40 rounded-lg object-cover border border-gray-300 shadow-sm cursor-pointer"
                         data-bs-toggle="modal" data-bs-target="#imageModal" onclick="openModal('{{ base64_encode($image->images) }}')" />
                </div>
            @endforeach
        </div>
    </div>
</div>
<!-- Modal  when i click on the image get bigger as pop up-->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img id="modalImage" src="" alt="Visite Image" class="w-full h-auto rounded-lg shadow-lg" />
            </div>
        </div>
    </div>
</div>

    <!-- Produits Utilisés Section -->
    <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl mb-8 border border-gray-300">
        <div class="bg-blue-500 text-white px-6 py-4 rounded-t-xl flex items-center">
            <i class="fas fa-mortar-pestle text-3xl mr-3"></i>
            <span class="text-xl font-semibold">Produits de visite</span>
        </div>
        <div class="px-4 py-4">
            @if ($visite->invoice->products->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full table-auto border border-gray-300 rounded-lg overflow-hidden shadow-sm text-sm sm:text-base">
                        <thead>
                            <tr class="bg-blue-500 text-white">
                                <th class="px-2 sm:px-6 py-2 sm:py-3 text-left font-semibold">Produit</th>
                                <th class="px-2 sm:px-6 py-2 sm:py-3 text-left font-semibold">Quantité</th>
                                <th class="px-2 sm:px-6 py-2 sm:py-3 text-left font-semibold">Prix Unitaire (DH)</th>
                                <th class="px-2 sm:px-6 py-2 sm:py-3 text-left font-semibold">Total (DH)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($visite->invoice->products as $product)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-2 sm:px-6 py-2 text-gray-800">{{ $product->name }}</td>
                                    <td class="px-2 sm:px-6 py-2 text-gray-800">{{ $product->pivot->quantity }}</td>
                                    <td class="px-2 sm:px-6 py-2 text-gray-800">{{ number_format($product->price, 2) }}</td>
                                    <td class="px-2 sm:px-6 py-2 text-gray-800">
                                        {{ number_format($product->price * $product->pivot->quantity, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-600 text-center">Aucun produit utilisé.</p>
            @endif
        </div>
        
    </div>

    <!-- Soins Réalisés Section -->
    <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl mb-8 border border-gray-300">
        <div class="bg-blue-500 text-white px-6 py-4 rounded-t-xl flex items-center">
            <i class="fas fa-hand-holding-heart text-3xl mr-3"></i>
            <span class="text-xl font-semibold">Soins de visite</span>
        </div>
        <div class="px-4 py-4">
            @if ($visite->invoice->soins->isNotEmpty())
                <div class="overflow-x-auto">
                    <table class="w-full table-auto border border-gray-300 rounded-lg overflow-hidden shadow-sm text-sm sm:text-base">
                        <thead>
                            <tr class="bg-blue-500 text-white">
                                <th class="px-2 sm:px-6 py-2 sm:py-3 text-left font-semibold">Soin</th>
                                <th class="px-2 sm:px-6 py-2 sm:py-3 text-left font-semibold">Quantité</th>
                                <th class="px-2 sm:px-6 py-2 sm:py-3 text-left font-semibold">Prix Unitaire (DH)</th>
                                <th class="px-2 sm:px-6 py-2 sm:py-3 text-left font-semibold">Total (DH)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach ($visite->invoice->soins as $soin)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-2 sm:px-6 py-2 text-gray-800">{{ $soin->name }}</td>
                                    <td class="px-2 sm:px-6 py-2 text-gray-800">{{ $soin->pivot->quantity }}</td>
                                    <td class="px-2 sm:px-6 py-2 text-gray-800">{{ number_format($soin->price, 2) }}</td>
                                    <td class="px-2 sm:px-6 py-2 text-gray-800">
                                        {{ number_format($soin->price * $soin->pivot->quantity, 2) }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-gray-600 text-center">Aucun soin réalisé.</p>
            @endif
        </div>        
    </div>

  <!-- Facturation Section -->
  <div class="bg-gradient-to-br from-white to-gray-50 rounded-xl mb-8 border border-gray-300 shadow-md">
    <!-- Header -->
    <div class="bg-blue-500 text-white px-6 py-4 rounded-t-xl flex items-center">
        <i class="fas fa-invoice text-3xl mr-3"></i>
        <span class="text-xl font-semibold">Facturation</span>
    </div>

    <!-- Button Wrapper -->
    <div class="px-6 py-4">
        <form action="{{ route('invoices.show', $visite->invoice->id) }}">
            <button type="submit" class="bg-green-500 text-white px-6 py-3 rounded-md text-sm font-medium hover:bg-green-600">
                Voir Facture de Visite
            </button>
        </form>
    </div>
</div>





    <!-- Buttons for Edit and Delete -->
<div class="flex justify-center items-center space-x-4 mt-6">
    <!-- Edit Button -->
    <form action="{{ route('visites.edit', $visite->id) }}">
        <button type="submit" class="bg-blue-500 text-white px-6 py-3 rounded-md text-sm font-medium hover:bg-blue-600">
            Modifier visite
        </button>
    </form>
    <!-- Delete Button -->
    <form action="{{ route('visites.destroy', $visite->id) }}" method="POST">
        @csrf
        @method('DELETE')
        <button type="submit" class="bg-red-500 text-white px-6 py-3 rounded-md text-sm font-medium hover:bg-red-600">
            Supprimer la visite
        </button>
    </form>
</div>

    </div>
<br><br>
<script>
    function openModal(imageSrc) {
        const modalImage = document.getElementById('modalImage');
        modalImage.src = 'data:image/png;base64,' + imageSrc;
    }
</script>
</x-app-layout>
