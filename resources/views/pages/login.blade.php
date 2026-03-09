<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Meld jouw sportlocatie aan op GymMaps.nl</title>
    <link rel="icon" type="image/png" href="{{ asset('logo/gymmaps-logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('logo/gymmaps-logo.png') }}">
    <meta name="description" content="Maak een bedrijfsprofiel aan op GymMaps.nl en word zichtbaar voor sporters in jouw regio.">
    <style>
        :root {
            --blue-900: #0f3f73;
            --blue-700: #1f5e9a;
            --ink: #11395f;
            --muted: #4f6781;
            --line: #d6e2ee;
            --card: #f9fbfe;
            --field: #f4f8fc;
            --green: #95c11f;
            --green-dark: #7ea61a;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: "Segoe UI", Roboto, sans-serif;
            background:
                radial-gradient(1000px 560px at 14% 8%, rgba(49, 120, 185, 0.34), transparent 58%),
                radial-gradient(860px 480px at 86% 92%, rgba(24, 84, 140, 0.38), transparent 56%),
                linear-gradient(140deg, var(--blue-900), var(--blue-700));
            color: var(--ink);
            min-height: 100vh;
        }

        .container {
            max-width: 960px;
            margin: 0 auto;
            padding: 40px 16px 56px;
        }

        .panel {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.97), rgba(247, 250, 254, 0.96));
            border: 1px solid rgba(202, 218, 234, 0.9);
            border-radius: 30px;
            padding: 26px;
            box-shadow: 0 26px 60px rgba(9, 35, 61, 0.34);
        }

        h1 {
            margin: 0 0 12px;
            font-size: clamp(1.9rem, 3vw, 2.5rem);
            color: #123f6a;
            text-align: center;
        }

        h2 {
            margin: 22px 0 10px;
            font-size: 1.35rem;
            color: #123f6a;
        }

        p {
            margin: 0 0 12px;
            color: var(--muted);
            line-height: 1.55;
        }

        .benefits {
            list-style: none;
            margin: 8px 0 20px;
            padding: 0;
            display: grid;
            gap: 8px;
        }

        .benefits li {
            color: #1d4367;
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }

        .benefits li::before {
            content: "";
            width: 16px;
            height: 16px;
            margin-top: 2px;
            border-radius: 4px;
            border: 2px solid #98c524;
            background: rgba(149, 193, 31, 0.15);
            flex: 0 0 16px;
        }

        .form-card {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 22px;
            padding: 24px 26px;
            box-shadow: 0 16px 30px rgba(20, 55, 89, 0.08);
            margin-top: 12px;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .field { margin-bottom: 12px; }

        label {
            display: block;
            font-weight: 600;
            margin-bottom: 6px;
            color: #1b446a;
        }

        input, textarea {
            width: 100%;
            border: 1px solid #c7d6e6;
            border-radius: 14px;
            padding: 13px 14px;
            font: inherit;
            background: var(--field);
            color: #183b5d;
        }

        .error { color: #9d1f1f; font-size: 0.9rem; margin-top: 4px; }

        .actions {
            margin-top: 16px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .btn {
            border: 0;
            border-radius: 14px;
            padding: 12px 20px;
            text-decoration: none;
            font-weight: 700;
            cursor: pointer;
            font-size: 1rem;
        }

        .btn-primary {
            background: linear-gradient(180deg, #9ecc21, #7ea61a);
            color: #fff;
            box-shadow: 0 14px 24px rgba(126, 166, 26, 0.3);
            min-width: 220px;
        }

        .btn-primary:hover { background: linear-gradient(180deg, #a8d329, var(--green-dark)); }

        .btn-ghost {
            background: #e6eef7;
            color: #1e4b75;
        }

        @media (max-width: 760px) {
            .grid { grid-template-columns: 1fr; }
            .form-card { padding: 16px; }
            .actions { justify-content: flex-start; }
            .btn-primary { width: 100%; }
        }
    </style>
</head>
<body>
@include('partials.site-header')
<div class="container">
    <section class="panel">
        <h1>Meld jouw sportlocatie aan op GymMaps.nl</h1>
        <p>
            Heb jij een sportschool, personal training studio of bied je sportactiviteiten aan in Nederland?
            Maak dan eenvoudig een bedrijfsprofiel op GymMaps.nl en word zichtbaar voor sporters in jouw regio.
            GymMaps.nl helpt sporters om sportlocaties, trainers en activiteiten in de buurt te vinden via een overzichtelijke kaart en handige filters.
            Door een profiel aan te maken zorg je ervoor dat potentiële sporters jouw locatie snel kunnen ontdekken.
        </p>

        <h2>Wat kun je met een bedrijfsprofiel?</h2>
        <p>Met een bedrijfsaccount kun je jouw sportlocatie volledig zelf beheren. Je kunt onder andere:</p>
        <ul class="benefits">
            <li>jouw sportlocatie op de kaart tonen</li>
            <li>een introductie of beschrijving van je bedrijf toevoegen</li>
            <li>foto’s van je locatie of trainingen uploaden</li>
            <li>een link naar je website, boekingssysteem of social media plaatsen</li>
            <li>je profiel altijd zelf aanpassen of updaten</li>
        </ul>

        <h2>Start vandaag nog met jouw bedrijfsprofiel</h2>

        <div class="form-card">
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
                    <label for="logo_url">Logo URL (optioneel)</label>
                    <input id="logo_url" name="logo_url" type="url" value="{{ old('logo_url') }}" placeholder="https://jouwdomein.nl/logo.png">
                    @error('logo_url')<div class="error">{{ $message }}</div>@enderror
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
                    <input id="sports_overview" name="sports_overview" type="text" value="{{ old('sports_overview') }}" placeholder="Bijv. fitness, personal training, boksen" required>
                    @error('sports_overview')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="field">
                    <label for="message">Extra toelichting (optioneel)</label>
                    <textarea id="message" name="message" rows="4">{{ old('message') }}</textarea>
                    @error('message')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="field">
                    <label for="photo">Foto van de locatie (optioneel)</label>
                    <input id="photo" name="photo" type="file" accept="image/*">
                    @error('photo')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="actions">
                    <button class="btn btn-primary" type="submit">Bedrijfsprofiel aanvragen</button>
                    <a class="btn btn-ghost" href="{{ route('home') }}">Terug naar homepage</a>
                </div>
            </form>
        </div>
    </section>
</div>
</body>
</html>
