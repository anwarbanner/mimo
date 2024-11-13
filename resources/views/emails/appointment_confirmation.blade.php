<!DOCTYPE html>
<html>

<head>
    <title>Confirmation de votre rendez-vous</title>
</head>

<body>
    <h1>Bonjour,</h1>
    <p>Nous confirmons votre rendez-vous avec les informations suivantes:</p>
    <ul>
        <!-- resources/views/emails/appointment_confirmation.blade.php -->

        <p>Bonjour {{ $appointment->patient->name }},</p>

        <p>Votre rendez-vous est prévu pour le {{ $appointment->date->format('d M Y') }} à {{ $appointment->time }}.</p>

        <p>Veuillez confirmer votre rendez-vous en cliquant sur le bouton ci-dessous.</p>

        <!-- Add any other content you need here -->


    </ul>
    <p>Merci de confirmer votre présence.</p>
</body>

</html>
