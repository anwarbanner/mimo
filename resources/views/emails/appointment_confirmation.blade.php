<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de votre Rendez-vous</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            color: #4CAF50;
            font-size: 24px;
            margin-bottom: 20px;
        }

        p {
            font-size: 16px;
            line-height: 1.6;
        }

        .appointment-details {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f9f9f9;
            border-left: 5px solid #4CAF50;
        }

        .footer {
            margin-top: 30px;
            font-size: 14px;
            color: #777;
            text-align: center;
        }

        .contact-info {
            font-size: 16px;
            font-weight: bold;
            color: #333;
            margin-top: 20px;
        }

        .logo {
            width: 150px;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Image at the top of the email -->
        <img src="{{ asset('storage/images/logo-acup.jpg') }}" alt="Logo de Votre Entreprise" class="logo">


        <h1>Bonjour {{ $appointment->patient->name }},</h1>

        <p>Nous avons le plaisir de vous confirmer que votre rendez-vous a été programmé avec succès. Voici les détails :</p>

        <div class="appointment-details">
            <p><strong>Date du rendez-vous :</strong> {{ $appointment->date->format('d M Y') }}</p>
            <p><strong>Heure :</strong> {{ $appointment->start }}</p>
        </div>

        <p>Nous vous prions de bien vouloir confirmer votre présence en nous répondant par email ou en nous appelant.</p>

        <p class="contact-info">
            <strong>Email :</strong> contact@votreentreprise.com<br>
            <strong>Téléphone :</strong> +33 1 23 45 67 89
        </p>

        <p class="footer">
            Si vous avez des questions ou si vous devez reprogrammer, n'hésitez pas à nous contacter.<br>
            Merci de choisir notre service. À bientôt !
        </p>
    </div>
</body>

</html>
