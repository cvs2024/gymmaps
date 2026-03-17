<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.google-site-verification')
    <title>Contact - GymMaps.nl</title>
    @include('partials.favicon')
    @include('partials.brand-theme')
    <meta name="description" content="Neem contact op met GymMaps.nl voor vragen, feedback of het aanmelden van jouw sportlocatie.">
    <style>
        :root {
            --bg: #f4f8fc;
            --ink: #10263d;
            --muted: #586779;
            --line: #d5e0ea;
            --blue: #0f5e88;
            --blue-dark: #0c4f74;
        }
        * { box-sizing: border-box; }
        body {
            margin:0;
            font-family:"Segoe UI", Roboto, sans-serif;
            background: transparent;
            color: var(--gm-brand-text);
        }
        .container { max-width:860px; margin:0 auto; padding:28px 16px 40px; }
        .card { background:#fff; border:1px solid var(--line); border-radius:14px; padding:18px; box-shadow:0 10px 22px rgba(12, 47, 74, .08); }
        h1 { margin:0 0 8px; }
        p { color:var(--muted); }
        .flash { margin-bottom:10px; background:#e8f3fb; color:#124265; padding:12px; border-radius:10px; border:1px solid #c5dbeb; }
        .error-box { margin-bottom:10px; background:#fff4f4; color:#8b1f1f; padding:12px; border-radius:10px; border:1px solid #f1c6c6; }
        .grid { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
        form { width: 100%; }
        .field { margin-bottom:12px; }
        label { display:block; font-weight:600; margin-bottom:6px; color:#123a5f; }
        input, textarea { width:100%; max-width:100%; border:1px solid #c7d8e8; border-radius:10px; padding:11px; font:inherit; background:#f7fbff; }
        .error { color:#9f1d1d; font-size:0.9rem; margin-top:4px; }
        .btn-row { margin-top: 12px; display: flex; flex-wrap: wrap; gap: 8px; }
        .btn { display:inline-block; background:var(--blue); color:#fff; text-decoration:none; border-radius:10px; padding:10px 14px; font-weight:600; border:0; cursor:pointer; }
        .btn:hover { background:var(--blue-dark); }
        .btn-ghost { background:#e7eff8; color:#163f62; margin-left:8px; }
        .btn-ghost { margin-left: 0; }
        @media (max-width:760px) { .grid { grid-template-columns:1fr; } }
    </style>
</head>
<body>
@include('partials.site-header')
<div class="container">
    <div class="card">
        <h1>Contact</h1>
        <p>Heb je een vraag over GymMaps.nl? Vul hieronder het vragenformulier in. We nemen zo snel mogelijk contact met je op.</p>

        @if(session('status'))
            <div class="flash">{{ session('status') }}</div>
        @endif

        @if($errors->has('mail'))
            <div class="error-box">{{ $errors->first('mail') }}</div>
        @endif

        <form method="POST" action="{{ route('pages.contact.store') }}">
            @csrf

            <div class="grid">
                <div class="field">
                    <label for="name">Naam</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required>
                    @error('name')<div class="error">{{ $message }}</div>@enderror
                </div>
                <div class="field">
                    <label for="email">E-mailadres</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required>
                    @error('email')<div class="error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="field">
                <label for="phone">Telefoonnummer (optioneel)</label>
                <input id="phone" name="phone" type="text" value="{{ old('phone') }}">
                @error('phone')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="field">
                <label for="subject">Onderwerp</label>
                <input id="subject" name="subject" type="text" value="{{ old('subject') }}" required>
                @error('subject')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="field">
                <label for="message">Jouw vraag</label>
                <textarea id="message" name="message" rows="6" required>{{ old('message') }}</textarea>
                @error('message')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="btn-row">
                <button class="btn" type="submit">Verstuur vraag</button>
            </div>
        </form>
    </div>
</div>
@include('partials.site-footer')
</body>
</html>
