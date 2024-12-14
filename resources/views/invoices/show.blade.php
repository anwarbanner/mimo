<x-app-layout>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Facture</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
            body {
                font-family: 'Arial', sans-serif;
                background-color: #f8f9fa;
            }
            .invoice-container {
                border: 1px solid #dee2e6;
                background-color: #fff;
                padding: 2rem;
                border-radius: 8px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
                max-width: 800px;
                margin: auto;
            }
            @media (max-width: 576px) {
                .invoice-container {
                    padding: 1rem;
                }
                .section-title {
                    font-size: 1rem;
                }
                .table th, .table td {
                    font-size: 0.875rem;
                }
                .total {
                    font-size: 1rem;
                }
                .btn-download {
                    font-size: 0.875rem;
                }
            }
        </style>
    </head>
    <body>
        <div class="text-center mt-4">
            <a href="{{ route('invoices.download-pdf', $invoice->id) }}" class="btn btn-success btn-download">Télécharger PDF</a>
        </div>
        <br><br>
        <div class="container my-5">
            <div class="invoice-container">
                <!-- Header Section -->
                <div class="d-flex justify-content-between align-items-center mb-4 flex-column-reverse flex-md-row">
                    <!-- Invoice Title -->
                    <div class="invoice-header text-center text-md-start mt-3 mt-md-0">
                        <h1 class="h5 mb-0">Facture N°{{ $invoice->id }}</h1>
                    </div>
                    <!-- Logo Section -->
                    <div class="logo-section text-center text-md-end">
                        <img src="{{ asset('images/logo/logo-acup.jpg') }}" alt="Logo" class="logo-img" style="max-width: 100px;">
                    </div>
                </div>
                
                <!-- Institute and Client Details -->
                <div class="row mb-4">
                    <div class="col-12 col-md-6 mb-3 mb-md-0 d-flex flex-column flex-sm-row">
                        <div class="p-3 bg-light rounded border flex-grow-1 mb-3 mb-sm-0">
                            <h5 class="section-title text-center">Institut</h5>
                            <hr class="border-2">
                            <p><strong>Nom:</strong> Mi-Acup</p>
                            <p><strong>Adresse:</strong> {{ auth()->user()->adresse }}</p>
                            <p><strong>Cie:</strong> {{ auth()->user()->cie }}</p>
                            <p><strong>Identifiant Fiscal:</strong> {{ auth()->user()->fiscal_id }}</p>
                            <p><strong>Numéro de registre:</strong> {{ auth()->user()->register_number }}</p>
                        </div>
                    </div>
                
                    <div class="col-12 col-md-6 mb-3 mb-md-0 d-flex flex-column flex-sm-row">
                        <div class="p-3 bg-light rounded border flex-grow-1">
                            <h5 class="section-title text-center">Client</h5>
                            <hr class="border-2">
                            <p><strong>Nom:</strong> <a href="/patients/{{ $invoice->patient->id }}/edit" class="text-primary">{{ $invoice->patient->nom }} {{ $invoice->patient->prenom }}</a></p>
                            <p><strong>Adresse:</strong> {{ $invoice->patient->adresse ?? 'N/A' }}</p>
                            <p><strong>Téléphone:</strong> {{ $invoice->patient->telephone ?? 'N/A' }}</p>
                        </div>
                    </div>
                </div>
                
                

                <!-- Invoice Info -->
                <table class="table table-bordered mb-4">
                    <thead>
                        <tr>
                            <th>Facture n°</th>
                            <th>Date</th>
                            <th>Mode de paiement</th>
                            <th>TVA (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $invoice->created_at->format('Y')}}-{{ $invoice->id }}</td>
                            <td>{{ $invoice->created_at->format('Y-m-d') }}</td>
                            <td>Espèces</td>
                            <td>{{ auth()->user()->tva }}%</td>
                        </tr>
                    </tbody>
                </table>
        
                

                <div class="mb-4">
                    <h5 class="section-title text-center">Détails</h5>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Type</th> <!-- New column added for type -->
                                <th>Quantité</th>
                                <th>Prix Unitaire</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Produits -->
                            @php
                                $totalHTC = 0; // Initialize total HTC variable
                            @endphp
                            @foreach($invoice->products as $product)
                            <tr>
                                <td>{{ $product->name }}</td>
                                <td>Produit</td> <!-- "Produit" type for products -->
                                <td>{{ $product->pivot->quantity }}</td>
                                <td>{{ $product->price }} DH</td>
                                <td>{{ $product->price * $product->pivot->quantity }} DH</td>
                            </tr>
                            @php
                                $totalHTC += $product->price * $product->pivot->quantity; // Add product total to HTC
                            @endphp
                            @endforeach
                
                            <!-- Soins -->
                            @foreach($invoice->soins as $soin)
                            <tr>
                                <td>{{ $soin->name }}</td>
                                <td>Soin</td> <!-- "Soin" type for treatments -->
                                <td>{{ $soin->pivot->quantity }}</td>
                                <td>{{ $soin->price }} DH</td>
                                <td>{{ $soin->price * $soin->pivot->quantity }} DH</td>
                            </tr>
                            @php
                                $totalHTC += $soin->price * $soin->pivot->quantity; // Add soin total to HTC
                            @endphp
                            @endforeach
                
                            <!-- Total HTC Row -->
                            <tr>
                                <td colspan="4" class="text-center"><strong>Montant total HT</strong></td>
                                <td><strong>{{ $totalHTC }} DH</strong></td>
                            </tr>
                
                            <!-- Calculation for TVA and Total Amount -->
                            @php
                                $tva_rate = auth()->user()->tva;
                                $tva_amount = ($totalHTC * $tva_rate) / 100;
                                $total_amount_with_tva = $totalHTC + $tva_amount;
                            @endphp
                            <tr>
                                <td colspan="4" class="text-center"><strong>TVA ({{ $tva_rate }}%)</strong></td>
                                <td><strong>{{ $tva_amount }} DH</strong></td>
                            </tr>
                            <tr>
                                <td colspan="4" class="text-center"><strong>Total à Payer</strong></td>
                                <td><strong>{{ $total_amount_with_tva }} DH</strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                

                <!-- Download PDF Button -->
                
            </div>
        </div>
        
                <br> <br>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </body>
    </html>
</x-app-layout>
