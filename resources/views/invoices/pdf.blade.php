<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture N°{{ $invoice->id }}</title>
    <!-- Bootstrap 5 CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            font-size: 10px;
            line-height: 1.5;
            background-color: #ffffff; /* Light background for the body */
        }

        .container {
            width: 100%;
            max-width: 1200px; /* Max width to prevent stretching */
            margin: auto;
            padding: 15px;
        }

        .invoice-container {
            max-width: 100%;
            margin: auto;
            background: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px; /* Rounded corners for a modern look */
            font-size: 12px; /* Adjust font size slightly */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Subtle shadow for the container */
        }

        .invoice-header h1 {
            font-size: 22px; /* Slightly larger title */
            margin-bottom: 10px;
            color: #333; /* Dark color for the header */
        }

        .section {
            padding: 20px;
            background-color: #fff; /* White background for sections */
            border: 1px solid #ddd; /* Light border for sections */
            border-radius: 8px; /* Rounded corners for smooth edges */
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); /* Soft shadow for depth */
            font-size: 14px;
        }

        .section-title {
            font-size: 16px; /* Larger section title */
            font-weight: bold;
            margin-bottom: 15px;
            color: #333;
            text-align: center; /* Center-align section title */
            text-transform: uppercase; /* Make titles uppercase for emphasis */
            border-bottom: 2px solid #007bff; /* Blue underline for section titles */
            padding-bottom: 5px;
        }

        .row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px; /* Space between the sections */
            justify-content: space-between;
        }

        .col-12 {
            width: 100%; 
            flex: 1; /* Full width on small screens */
        }

        .col-md-6 {
            width: 48%; /* Half width on medium and larger screens */
        }

        .p-4 {
            padding: 20px; /* Standard padding */
        }

        .shadow-sm {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* Subtle shadow effect */
        }

        p {
            margin: 5px 0; /* Consistent spacing for paragraph elements */
            color: #555; /* Dark gray text color */
        }

        strong {
            color: #333; /* Darker color for emphasis */
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            font-size: 12px;
        }

        .table th, .table td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .table th {
            background-color: #f0f0f0;
            font-weight: bold;
            color: #333;
        }

        .table-striped tr:nth-child(even) {
            background-color: #f9f9f9; /* Alternate row color */
        }

        .text-center {
            text-align: center;
        }

        .bg-light {
            background-color: #f8f9fa;
        }

        .mb-4 {
            margin-bottom: 30px; /* Increased margin for more space */
        }

        .border {
            border: 1px solid #ddd;
        }

        .rounded {
            border-radius: 8px; /* Rounded corners */
        }

        .footer {
            margin-top: 40px; /* Increased margin for better separation */
            text-align: center;
            font-size: 14px; /* Larger font size for footer */
        }

        .d-flex {
            display: flex;
            justify-content: space-between;
            align-items: center; /* Vertically center content */
            flex-wrap: wrap; /* Wrap content on small screens */
        }

        .logo-section {
            text-align: center;
        }

        .logo-img {
            max-height: 150px; /* Reduced size for better proportion */
            object-fit: contain; /* Ensure logo maintains aspect ratio */
        }

        .div1 {
            display: flex; /* Enable flexbox */
            gap: 20px; /* Space between the two columns */
            justify-content: space-between; /* Make them align next to each other */
        }

        @media (max-width: 768px) {
            .div1 {
                flex-direction: column; /* Stack sections vertically on small screens */
            }

            .col-md-6 {
                width: 100%; /* Full width on smaller screens */
            }
        }
    </style>
</head>
<body>
    <div>
        <div class="invoice-container">
            <!-- Header Section -->
            <div class="d-flex justify-content-between mb-4">
                <!-- Logo Section -->
                <div class="logo-section">
                    <img src="data:image/jpeg;base64,{{ base64_encode(file_get_contents(public_path('images/logo/logo-acup.jpg'))) }}" alt="Logo" class="logo-img">
                </div>
            </div>

            <div class="col-lg-8 col-lg-offset-2 d-flex justify-content-between">
                <!-- Institute Details -->
                <div class="bg-light rounded border p-4 shadow-sm" style="flex: 1;">
                    <h5 class="section-title">Institut</h5>
                    <p><strong>Nom:</strong> Mi-Acup</p>
                    <p><strong>Adresse:</strong> {{ auth()->user()->adresse }}</p>
                    <p><strong>Cie:</strong> {{ auth()->user()->cie }}</p>
                    <p><strong>Identifiant Fiscal:</strong> {{ auth()->user()->fiscal_id }}</p>
                    <p><strong>Numéro de registre:</strong> {{ auth()->user()->register_number }}</p>
                </div>
            <br>
                <!-- Client Details -->
                <div class="bg-light rounded border p-4 shadow-sm" style="flex: 1;">
                    <h5 class="section-title">Patient</h5>
                    <p><strong>Nom:</strong> {{ $invoice->patient->nom }} {{ $invoice->patient->prenom }}</p>
                    <p><strong>Adresse:</strong> {{ $invoice->patient->adresse ?? 'N/A' }}</p>
                    <p><strong>Téléphone:</strong> {{ $invoice->patient->telephone ?? 'N/A' }}</p>
                </div>
            </div>
            
        

            
            <br>

            <!-- Invoice Info -->
            <div class="section">
                <h5 class="section-title">Informations Facture</h5>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Facture N°</th>
                            <th>Date</th>
                            <th>Mode de Paiement</th>
                            <th>TVA (%)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $invoice->created_at->format('Y') }}-{{ $invoice->id }}</td>
                            <td>{{ $invoice->created_at->format('Y-m-d') }}</td>
                            <td>Espèces</td>
                            <td>{{ auth()->user()->tva }}%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
<br>
            <!-- Product and Soins Details -->
            <div class="section">
                <h5 class="section-title text-center">Détails des Produits et Soins</h5>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Type</th>
                            <th>Quantité</th>
                            <th>Prix Unitaire</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $totalHTC = 0;
                        @endphp
                        @foreach($invoice->products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>Produit</td> 
                            <td>{{ $product->pivot->quantity }}</td>
                            <td>{{ $product->price }} DH</td>
                            <td>{{ $product->price * $product->pivot->quantity }} DH</td>
                        </tr>
                        @php
                            $totalHTC += $product->price * $product->pivot->quantity;
                        @endphp
                        @endforeach
                        @foreach($invoice->soins as $soin)
                        <tr>
                            <td>{{ $soin->name }}</td>
                            <td>Soin</td> 
                            <td>{{ $soin->pivot->quantity }}</td>
                            <td>{{ $soin->price }} DH</td>
                            <td>{{ $soin->price * $soin->pivot->quantity }} DH</td>
                        </tr>
                        @php
                            $totalHTC += $soin->price * $soin->pivot->quantity;
                        @endphp
                        @endforeach
                        <tr>
                            <td colspan="4" class="text-right"><strong>Total HTC:</strong></td>
                            <td>{{ $totalHTC }} DH</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-right"><strong>Total TVA:</strong></td>
                            <td>{{ $totalHTC * auth()->user()->tva / 100 }} DH</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-right"><strong>Total TTC:</strong></td>
                            <td>{{ $totalHTC + ($totalHTC * auth()->user()->tva / 100) }} DH</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <div class="footer">
        <p>Merci pour votre confiance !</p>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
