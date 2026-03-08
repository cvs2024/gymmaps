<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gratis sportlocatie aanmelden - Gymmap.nl</title>
    <style>
        body {
            margin: 0;
            font-family: "Segoe UI", Roboto, sans-serif;
            background: #f4f7fb;
            color: #0b1f33;
        }

        .container {
            max-width: 760px;
            margin: 0 auto;
            padding: 24px 16px 34px;
        }

        .card {
            background: #fff;
            border: 1px solid #d5e0ea;
            border-radius: 14px;
            padding: 18px;
        }

        h1 { margin: 0 0 8px; }

        .muted { color: #586779; margin-bottom: 16px; }

        .field { margin-bottom: 12px; }

        label {
            display: block;
            font-weight: 600;
            margin-bottom: 6px;
        }

        input, textarea {
            width: 100%;
            border: 1px solid #c9d8e5;
            border-radius: 10px;
            padding: 10px;
            font: inherit;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .error { color: #9d1f1f; font-size: 0.9rem; margin-top: 4px; }

        .actions {
            margin-top: 16px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .btn {
            border: 0;
            border-radius: 10px;
            padding: 10px 14px;
            text-decoration: none;
            font-weight: 600;
            cursor: pointer;
        }

        .btn-primary { background: #0f8a5f; color: #fff; }
        .btn-ghost { background: #e9f0f7; color: #0d3655; }

        @media (max-width: 680px) {
            .grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <h1>Sportlocatie gratis aanmelden</h1>
        <p class="muted">Staat jouw sportschool of sportactiviteit nog niet op Gymmap.nl? Vul dit formulier in. De aanmelding is kosteloos.</p>

        <form method="POST" action="{{ route('listing-requests.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="grid">
                <div class="field">
                    <label for="contact_name">Contactpersoon</label>
                    <input id="contact_name" name="contact_name" type="text" value="{{ old('contact_name') }}" required>
                    @error('contact_name')<div class="error">{{ $message }}</div>@enderror
                </div>
                <div class="field">
                    <label for="business_name">Naam locatie</label>
                    <input id="business_name" name="business_name" type="text" value="{{ old('business_name') }}" required>
                    @error('business_name')<div class="error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="grid">
                <div class="field">
                    <label for="email">E-mail</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required>
                    @error('email')<div class="error">{{ $message }}</div>@enderror
                </div>
                <div class="field">
                    <label for="phone">Telefoon</label>
                    <input id="phone" name="phone" type="text" value="{{ old('phone') }}">
                    @error('phone')<div class="error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="field">
                <label for="website">Website (optioneel)</label>
                <input id="website" name="website" type="url" value="{{ old('website') }}" placeholder="https://">
                @error('website')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="field">
                <label for="address">Adres</label>
                <input id="address" name="address" type="text" value="{{ old('address') }}" required>
                @error('address')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="grid">
                <div class="field">
                    <label for="postcode">Postcode</label>
                    <input id="postcode" name="postcode" type="text" value="{{ old('postcode') }}" required>
                    @error('postcode')<div class="error">{{ $message }}</div>@enderror
                </div>
                <div class="field">
                    <label for="city">Plaats</label>
                    <input id="city" name="city" type="text" value="{{ old('city') }}" required>
                    @error('city')<div class="error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="field">
                <label for="sports_overview">Welke sportactiviteiten bied je aan?</label>
                <input id="sports_overview" name="sports_overview" type="text" value="{{ old('sports_overview') }}" placeholder="Bijv. fitness, yoga, boksen" required>
                @error('sports_overview')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="field">
                <label for="message">Extra toelichting (optioneel)</label>
                <textarea id="message" name="message" rows="4">{{ old('message') }}</textarea>
                @error('message')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="field">
                <label for="photo">Foto van de sportschool (optioneel)</label>
                <input id="photo" name="photo" type="file" accept="image/*">
                <p class="muted" style="margin: 4px 0 0;">Max 5MB, JPG/PNG/WebP.</p>
                @error('photo')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="actions">
                <button class="btn btn-primary" type="submit">Gratis aanmelden</button>
                <a class="btn btn-ghost" href="{{ route('home') }}">Terug naar zoeken</a>
            </div>
        </form>
    </div>
</div>
</body>
</html>
