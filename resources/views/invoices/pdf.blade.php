<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture</title>
    <script src="https://cdn.jsdelivr.net/npm/tailwindcss@3.2.7"></script>
</head>
<body class="bg-gray-50 font-sans leading-normal tracking-normal">

    <div class="max-w-7xl mx-auto px-8 py-10 bg-white shadow-lg rounded-lg">
        <!-- Header Section -->
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center">
                <img src="{{ asset('images/logo/logo-acup.jpg') }}" alt="Logo" class="w-24 h-auto mr-4">
                <h1 class="text-3xl font-bold text-gray-800">Facture #{{ $invoice->id }}</h1>
            </div>
            <div class="text-right">
                <p class="text-xl text-gray-600">Date: {{ $invoice->created_at->format('Y-m-d') }}</p>
            </div>
        </div>

        <!-- Institute and Client Details Section -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <!-- Institut Details -->
            <div class="bg-gray-100 p-6 rounded-md shadow-md">
                <h3 class="text-xl font-semibold text-gray-700">Institut</h3>
                <table class="min-w-full mt-4">
                    <tr><td class="font-semibold text-gray-600">Nom:</td><td>{{ auth()->user()->cie }}</td></tr>
                    <tr><td class="font-semibold text-gray-600">Adresse:</td><td>{{ auth()->user()->adresse }}</td></tr>
                    <tr><td class="font-semibold text-gray-600">Identifiant Fiscal:</td><td>{{ auth()->user()->fiscal_id }}</td></tr>
                    <tr><td class="font-semibold text-gray-600">Numéro de registre:</td><td>{{ auth()->user()->register_number }}</td></tr>
                </table>
            </div>

            <!-- Client Details -->
            <div class="bg-gray-100 p-6 rounded-md shadow-md">
                <h3 class="text-xl font-semibold text-gray-700">Client</h3>
                <table class="min-w-full mt-4">
                    <tr><td class="font-semibold text-gray-600">Nom:</td><td><a href="/patients/{{$invoice->patient->id }}/edit" class="text-indigo-600">{{ $invoice->patient->nom }}</a></td></tr>
                    <tr><td class="font-semibold text-gray-600">Adresse:</td><td>{{ $invoice->patient->adresse ?? 'N/A' }}</td></tr>
                    <tr><td class="font-semibold text-gray-600">Téléphone:</td><td>{{ $invoice->patient->telephone ?? 'N/A' }}</td></tr>
                </table>
            </div>
        </div>

        <!-- Invoice Basic Info Section -->
        <div class="bg-white shadow-md rounded-md p-6 mb-8">
            <h3 class="text-xl font-semibold text-gray-700 mb-4">Détails de la Facture</h3>
            <table class="w-full table-auto border-collapse">
                <thead>
                    <tr>
                        <th class="border px-4 py-2 text-left text-gray-600">Facture n°</th>
                        <th class="border px-4 py-2 text-left text-gray-600">Date</th>
                        <th class="border px-4 py-2 text-left text-gray-600">Mode de paiement</th>
                        <th class="border px-4 py-2 text-left text-gray-600">TVA (%)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border px-4 py-2">{{ $invoice->created_at->format('Y')}}-{{ $invoice->id }}</td>
                        <td class="border px-4 py-2">{{ $invoice->created_at->format('Y-m-d') }}</td>
                        <td class="border px-4 py-2">Espèces</td>
                        <td class="border px-4 py-2">{{ auth()->user()->tva }}%</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Items Section (Consultation, Products, Soins) -->
        <div class="bg-white shadow-md rounded-md p-6 mb-8">
            <h3 class="text-2xl font-semibold text-gray-700 mb-4">Détails des Articles</h3>
            <!-- Consultation Item -->
            <div class="invoice-items mb-6">
                <h4 class="font-semibold text-gray-700">Consultation</h4>
                <table class="w-full table-auto border-collapse">
                    <thead>
                        <tr>
                            <th class="border px-4 py-2 text-left">Description</th>
                            <th class="border px-4 py-2 text-left">Prix</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border px-4 py-2">Consultation</td>
                            <td class="border px-4 py-2">{{ $invoice->consultation_price }} DH</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Products -->
            <div class="invoice-items mb-6">
                <h4 class="font-semibold text-gray-700">Produits</h4>
                <table class="w-full table-auto border-collapse">
                    <thead>
                        <tr>
                            <th class="border px-4 py-2 text-left">Produit</th>
                            <th class="border px-4 py-2 text-left">Quantité</th>
                            <th class="border px-4 py-2 text-left">Prix</th>
                            <th class="border px-4 py-2 text-left">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->products as $product)
                            <tr>
                                <td class="border px-4 py-2">{{ $product->name }}</td>
                                <td class="border px-4 py-2">{{ $product->pivot->quantity }}</td>
                                <td class="border px-4 py-2">{{ $product->price }} DH</td>
                                <td class="border px-4 py-2">{{ $product->price * $product->pivot->quantity }} DH</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Soins -->
            <div class="invoice-items mb-6">
                <h4 class="font-semibold text-gray-700">Soins</h4>
                <table class="w-full table-auto border-collapse">
                    <thead>
                        <tr>
                            <th class="border px-4 py-2 text-left">Soin</th>
                            <th class="border px-4 py-2 text-left">Quantité</th>
                            <th class="border px-4 py-2 text-left">Prix</th>
                            <th class="border px-4 py-2 text-left">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($invoice->soins as $soin)
                            <tr>
                                <td class="border px-4 py-2">{{ $soin->name }}</td>
                                <td class="border px-4 py-2">{{ $soin->pivot->quantity }}</td>
                                <td class="border px-4 py-2">{{ $soin->price }} DH</td>
                                <td class="border px-4 py-2">{{ $soin->price * $soin->pivot->quantity }} DH</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Calculation of Total Before and After TVA -->
            @php
                $subtotal = $invoice->consultation_price;

                foreach ($invoice->products as $product) {
                    $subtotal += $product->price * $product->pivot->quantity;
                }

                foreach ($invoice->soins as $soin) {
                    $subtotal += $soin->price * $soin->pivot->quantity;
                }

                $tva_rate = auth()->user()->tva;
                $tva_amount = ($subtotal * $tva_rate) / 100;
                $total_amount_with_tva = $subtotal + $tva_amount;
            @endphp

            <table class="w-full table-auto border-collapse mt-6">
                <thead>
                    <tr>
                        <th class="border px-4 py-2 text-left">Sous-total</th>
                        <th class="border px-4 py-2 text-left">TVA ({{ $tva_rate }}%)</th>
                        <th class="border px-4 py-2 text-left">Total à Payer</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="border px-4 py-2">{{ $subtotal }} DH</td>
                        <td class="border px-4 py-2">{{ $tva_amount }} DH</td>
                        <td class="border px-4 py-2">{{ $total_amount_with_tva }} DH</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>
