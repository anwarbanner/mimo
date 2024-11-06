<x-app-layout>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Facture</title>
    <style>
        .container {
            width: 80%;
            margin: 0 auto;
            font-family: Arial, sans-serif;
        }
        .facture-div {
            border: 2px solid #333;
            padding: 20px;
            margin-top: 20px;
            background-color: #f9f9f9;
        }
        .invoice-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        .invoice-details-admin,
        .invoice-details-client {
            width: 45%;
        }
        .invoice-details-admin-infos, .invoice-details-client-infos {
            margin-bottom: 10px;
            font-size: 14px;
        }
        .invoice-details-admin-infos b,
        .invoice-details-client-infos b {
            color: #333;
        }
        .invoice-details-admin-facture-infos {
            margin-top: 20px;
        }
        .download-btn-container {
            text-align: right;
            margin-top: 20px;
        }
        .table-details-facture {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table-details-facture th,
        .table-details-facture td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        .invoice-items {
            margin-bottom: 20px;
        }
        .invoice-items h3 {
            margin-bottom: 10px;
            color: #555;
        }
        .invoice-items ul {
            list-style-type: none;
            padding: 0;
        }
        .invoice-items ul li {
            padding: 5px 0;
            border-bottom: 1px solid #ddd;
        }
        .total-amount {
            text-align: right;
            font-size: 18px;
            font-weight: bold;
        }
    </style>
</head>
<body>
<div class="container facture-div">
    <h1>FACTURE #{{ $invoice->id }}</h1>   
    
    <div class="invoice-details">
        <div class="invoice-details-admin">
            <div class="invoice-details-admin-infos">
                <p><b>- Institut:</b> Mi-Acup</p>
                <p><b>- Adresse:</b> rfgergerg 4000 gdsfgfds</p>
                <p><b>- Cie:</b> 9223372036854775807</p>
                <p><b>- Identifiant Fiscal:</b> 11111111111123</p>
                <p><b>- Numéro de registre:</b> 3333333333333</p>
            </div>
        </div>
        <div class="invoice-details-client">
            <div class="invoice-details-client-infos">
                <p><b>- Client:</b> <a href="#"> {{ $invoice->patient->nom }} </a></p>
                <p><b>- Adresse:</b> {{ $invoice->patient->adresse ?? 'N/A' }}</p>
                <p><b>- Téléphone:</b> {{ $invoice->patient->telephone ?? 'N/A' }}</p>
            </div>
        </div>
    </div>

    <div class="invoice-details-admin-facture-infos">
        <table class="table-details-facture">
            <thead>
                <tr>
                    <th>Facture n°</th>
                    <th>Date</th>
                    <th>Mode de payment</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{ $invoice->created_at->format('Y')}}-{{ $invoice->id }}</td>
                    <td>{{ $invoice->created_at->format('Y-m-d') }}</td>
                    <td>Espèces</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="invoice-items">
        <h3>Consultation</h3>
        <ul>
           
                <li>{{ $invoice->consultation_price }} DH</li>
           
        </ul>
    </div>
    <div class="invoice-items">
        <h3>Products</h3>
        <ul>
            @foreach($invoice->products as $product)
                <li>{{ $product->name }} - Quantity: {{ $product->pivot->quantity }} - Prix: {{ $product->price }} DH</li>
            @endforeach
        </ul>
    </div>

    <div class="invoice-items">
        <h3>Soins</h3>
        <ul>
            @foreach($invoice->soins as $soin)
                <li>{{ $soin->name }} - Quantity: {{ $soin->pivot->quantity }} - Prix: {{ $soin->price }} DH</li>
            @endforeach
        </ul>
    </div>

    <p class="total-amount">Total Amount: {{ $invoice->total_amount }} DH</p>
</div>

<div class="download-btn-container">
        <a href="{{ route('invoices.download-pdf', $invoice->id) }}" class="btn btn-success">Download PDF</a>
    </div>
</body>
</html>

</x-app-layout>
