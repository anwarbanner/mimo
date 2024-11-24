<title>Détails de la Visite</title>
<x-app-layout>
    <h1 class="text-4xl lg:text-5xl font-extrabold text-center text-blue-700 mb-6 lg:mb-8" name="title">
        Détails de la Visite
    </h1>

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

    <!-- Observation Section -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-6">
        <div class="bg-gray-100 px-6 py-4">
            <h3 class="text-xl font-semibold text-gray-800">Observation</h3>
        </div>
        <div class="px-6 py-4">
            <p class="text-gray-700">{{ $visite->observation ?? 'Aucune observation.' }}</p>
        </div>
    </div>

    <!-- Images Section -->
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

    <!-- Produits Utilisés Section -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden mb-6">
        <div class="bg-gray-100 px-6 py-4">
            <h3 class="text-xl font-semibold text-gray-800">Produits Utilisés</h3>
        </div>
        <div class="px-6 py-4">
            @if ($visite->invoice->products->isNotEmpty())
                <table class="min-w-full table-auto border-collapse">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left text-gray-700 border-b">Produit</th>
                            <th class="px-4 py-2 text-left text-gray-700 border-b">Quantité</th>
                            <th class="px-4 py-2 text-left text-gray-700 border-b">Prix Unitaire</th>
                            <th class="px-4 py-2 text-left text-gray-700 border-b">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($visite->invoice->products as $product)
                            <tr class="bg-gray-50 hover:bg-gray-100">
                                <td class="px-4 py-2 text-gray-700 border-b">{{ $product->name }}</td>
                                <td class="px-4 py-2 text-gray-700 border-b">{{ $product->pivot->quantity }}</td>
                                <td class="px-4 py-2 text-gray-700 border-b">{{ number_format($product->price, 2) }} DH</td>
                                <td class="px-4 py-2 text-gray-700 border-b">
                                    {{ number_format($product->price * $product->pivot->quantity, 2) }} DH
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
            @if ($visite->invoice->soins->isNotEmpty())
                <table class="min-w-full table-auto border-collapse">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 text-left text-gray-700 border-b">Soin</th>
                            <th class="px-4 py-2 text-left text-gray-700 border-b">Quantité</th>
                            <th class="px-4 py-2 text-left text-gray-700 border-b">Prix Unitaire</th>
                            <th class="px-4 py-2 text-left text-gray-700 border-b">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($visite->invoice->soins as $soin)
                            <tr class="bg-gray-50 hover:bg-gray-100">
                                <td class="px-4 py-2 text-gray-700 border-b">{{ $soin->name }}</td>
                                <td class="px-4 py-2 text-gray-700 border-b">{{ $soin->pivot->quantity }}</td>
                                <td class="px-4 py-2 text-gray-700 border-b">{{ number_format($soin->price, 2) }} DH</td>
                                <td class="px-4 py-2 text-gray-700 border-b">
                                    {{ number_format($soin->price * $soin->pivot->quantity, 2) }} DH
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-gray-600">Aucun soin réalisé.</p>
            @endif
        </div>
    </div>

    <!-- Facturation Section -->
    <div class="bg-white shadow-lg rounded-lg overflow-hidden">
        <div class="bg-gray-100 px-6 py-4">
            <h3 class="text-xl font-semibold text-gray-800">Facturation</h3>
        </div>
        <div class="px-6 py-4">
            <p class="text-gray-700"><strong>Total:</strong> {{ number_format($visite->invoice->total_amount, 2) }} DH</p>
        </div>
    </div>
</x-app-layout>
