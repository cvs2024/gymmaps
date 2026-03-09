<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <title>Nieuwe contactvraag</title>
</head>
<body style="font-family:Segoe UI,Roboto,sans-serif;color:#13283f;">
    <h2 style="margin:0 0 12px;">Nieuwe vraag via GymMaps.nl</h2>

    <p><strong>Naam:</strong> {{ $payload['name'] }}</p>
    <p><strong>E-mail:</strong> {{ $payload['email'] }}</p>
    @if(!empty($payload['phone']))
        <p><strong>Telefoon:</strong> {{ $payload['phone'] }}</p>
    @endif
    <p><strong>Onderwerp:</strong> {{ $payload['subject'] }}</p>
    <p><strong>Bericht:</strong></p>
    <p style="white-space:pre-line;">{{ $payload['message'] }}</p>
</body>
</html>
