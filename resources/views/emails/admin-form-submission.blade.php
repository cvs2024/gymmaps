<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <title>{{ $subjectLine }}</title>
</head>
<body style="font-family:Segoe UI,Roboto,sans-serif;color:#13283f;">
    <h2 style="margin:0 0 12px;">{{ $subjectLine }}</h2>

    @foreach($fields as $label => $value)
        @if($value !== null && trim((string) $value) !== '')
            <p style="margin:0 0 8px;">
                <strong>{{ $label }}:</strong><br>
                <span style="white-space:pre-line;">{{ $value }}</span>
            </p>
        @endif
    @endforeach
</body>
</html>
