<x-app-layout>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Facture</title>
        <script src="https://cdn.jsdelivr.net/npm/tailwindcss@3.2.7"></script>
    </head>
    <body class="bg-gray-50">
        <div class="container mx-auto px-4 py-8 bg-white shadow-lg rounded-lg">
            <!-- Header Section -->
            <div class="flex items-center justify-between mb-6">
                <!-- Logo and Invoice Title -->
                <div class="flex items-center">
                    <img src="{{ asset('images/logo/logo-acup.jpg') }}" alt="Logo" class="w-24 h-auto mr-4">
                    <h1 class="text-3xl font-semibold text-gray-800">Facture #{{ $invoice->id }}</h1>
                </div>
            </div>
    
            <!-- Institute and Client Details -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Institut Details -->
                <div class="p-4 bg-gray-100 rounded-md shadow-md">
                    <h3 class="text-xl font-semibold text-gray-700">Institut</h3>
                    <p><strong>Nom:</strong> Mi-Acup</p>
                    <p><strong>Adresse:</strong> {{ auth()->user()->adresse }}</p>
                    <p><strong>Cie:</strong> {{ auth()->user()->cie }}</p>
                    <p><strong>Identifiant Fiscal:</strong> {{ auth()->user()->fiscal_id }}</p>
                    <p><strong>Numéro de registre:</strong> {{ auth()->user()->register_number }}</p>
                </div>
    
                <!-- Client Details -->
                <div class="p-4 bg-gray-100 rounded-md shadow-md">
                    <h3 class="text-xl font-semibold text-gray-700">Client</h3>
                    <p><strong>Nom:</strong> <a href="/patients/{{$invoice->patient->id }}/edit" class="text-indigo-600">{{ $invoice->patient->nom }}</a></p>
                    <p><strong>Adresse:</strong> {{ $invoice->patient->adresse ?? 'N/A' }}</p>
                    <p><strong>Téléphone:</strong> {{ $invoice->patient->telephone ?? 'N/A' }}</p>
                </div>
            </div>
    
            <!-- Invoice Basic Info -->
            <div class="mb-8">
                <table class="w-full table-auto border-collapse mb-4">
                    <thead>
                        <tr>
                            <th class="border px-4 py-2 text-left">Facture n°</th>
                            <th class="border px-4 py-2 text-left">Date</th>
                            <th class="border px-4 py-2 text-left">Mode de paiement</th>
                            <th class="border px-4 py-2 text-left">TVA (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border px-4 py-2">{{ $invoice->created_at->format('Y')}}-{{ $invoice->id }}</td>
                            <td class="border px-4 py-2">{{ $invoice->created_at->format('Y-m-d') }}</td>
                            <td class="border px-4 py-2">Espèces</td>
                            <td class="border px-4 py-2">{{ auth()->user()->tva }}%</td> <!-- TVA rate from user -->
                        </tr>
                    </tbody>
                </table>
            </div>
    
            <!-- Items List (Consultation, Products, Soins) -->
            <div class="space-y-8">
                <!-- Consultation Item -->
                <div class="invoice-items">
                    <h3 class="text-2xl font-semibold text-gray-700 mb-4">Consultation</h3>
                    <ul class="space-y-2">
                        <li class="flex justify-between"><span>{{ $invoice->consultation_price }} DH</span></li>
                    </ul>
                </div>
    
                <!-- Products -->
                <div class="invoice-items">
                    <h3 class="text-2xl font-semibold text-gray-700 mb-4">Produits</h3>
                    <ul class="space-y-2">
                        @foreach($invoice->products as $product)
                            <li class="flex justify-between"><span>{{ $product->name }} - Quantity: {{ $product->pivot->quantity }} - Prix: {{ $product->price }} DH</span></li>
                        @endforeach
                    </ul>
                </div>
    
                <!-- Soins -->
                <div class="invoice-items">
                    <h3 class="text-2xl font-semibold text-gray-700 mb-4">Soins</h3>
                    <ul class="space-y-2">
                        @foreach($invoice->soins as $soin)
                            <li class="flex justify-between"><span>{{ $soin->name }} - Quantity: {{ $soin->pivot->quantity }} - Prix: {{ $soin->price }} DH</span></li>
                        @endforeach
                    </ul>
                </div>
    
                <!-- Calculation of Total Before and After TVA -->
                @php
                    $subtotal = $invoice->consultation_price;

                    // Add product prices
                    foreach ($invoice->products as $product) {
                        $subtotal += $product->price * $product->pivot->quantity;
                    }

                    // Add soins prices
                    foreach ($invoice->soins as $soin) {
                        $subtotal += $soin->price * $soin->pivot->quantity;
                    }

                    // Retrieve TVA rate from the authenticated user
                    $tva_rate = auth()->user()->tva; // TVA rate from the user table

                    // Calculate TVA
                    $tva_amount = ($subtotal * $tva_rate) / 100;
                    $total_amount_with_tva = $subtotal + $tva_amount;
                @endphp
    
                <p class="text-xl font-semibold text-gray-800">Sous-total: {{ $subtotal }} DH</p>
                <p class="text-xl font-semibold text-gray-800">TVA ({{ $tva_rate }}%): {{ $tva_amount }} DH</p>
                <p class="text-2xl font-semibold text-gray-800 text-right mt-4">Total à Payer: {{ $total_amount_with_tva }} DH</p>
            </div>
    
            
        </div>
        <!-- Download PDF Button -->
            <div class="text-right mt-8">
                <a href="{{ route('invoices.download-pdf', $invoice->id) }}" class="inline-block bg-green-600 text-white px-6 py-2 rounded-lg hover:bg-green-700 transition duration-200">Télécharger PDF</a>
            </div>
            <br>
    </body>
    </html>
</x-app-layout>
