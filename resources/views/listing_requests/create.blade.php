<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.google-site-verification')
    <title>Gratis sportlocatie aanmelden - Gymmap.nl</title>
    @include('partials.favicon')
    <style>
        :root {
            --blue-900: #0f3f73;
            --blue-700: #1f5e9a;
            --ink: #11395f;
            --muted: #5c7289;
            --line: #d6e2ee;
            --card: #f9fbfe;
            --field: #f4f8fc;
            --blue-accent: #0f5e88;
            --blue-accent-dark: #0c4f74;
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
            max-width: 860px;
            margin: 0 auto;
            padding: 42px 16px 50px;
        }

        .panel {
            background: linear-gradient(180deg, rgba(255, 255, 255, 0.97), rgba(247, 250, 254, 0.96));
            border: 1px solid rgba(202, 218, 234, 0.9);
            border-radius: 30px;
            padding: 24px;
            box-shadow: 0 26px 60px rgba(9, 35, 61, 0.34);
        }

        h1 {
            margin: 0 0 8px;
            font-size: clamp(1.9rem, 3vw, 2.45rem);
            color: #123f6a;
            text-align: center;
        }

        .muted {
            color: var(--muted);
            margin: 0 0 18px;
            text-align: center;
        }

        .steps {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 18px;
            position: relative;
        }

        .steps::before {
            content: "";
            position: absolute;
            left: 8%;
            right: 8%;
            top: 20px;
            height: 3px;
            background: #d2dfeb;
            border-radius: 999px;
            z-index: 0;
        }

        .step {
            position: relative;
            z-index: 1;
            text-align: center;
            color: #668097;
            font-weight: 600;
        }

        .step-dot {
            width: 42px;
            height: 42px;
            margin: 0 auto 8px;
            border-radius: 50%;
            border: 2px solid #bed0e3;
            background: #ecf3fa;
            display: grid;
            place-items: center;
            font-size: 1.35rem;
            font-weight: 700;
        }

        .step.active {
            color: #0f5e88;
        }

        .step.active .step-dot {
            border-color: var(--blue-accent);
            background: var(--blue-accent);
            color: #fff;
            box-shadow: 0 8px 16px rgba(15, 94, 136, 0.34);
        }

        .form-card {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 22px;
            padding: 24px 26px;
            box-shadow: 0 16px 30px rgba(20, 55, 89, 0.08);
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
            background: linear-gradient(180deg, #1f76a8, #0c4f74);
            color: #fff;
            box-shadow: 0 14px 24px rgba(15, 94, 136, 0.3);
            min-width: 220px;
        }

        .btn-primary:hover { background: linear-gradient(180deg, #2b86bb, var(--blue-accent-dark)); }

        .btn-ghost {
            background: #e6eef7;
            color: #1e4b75;
        }

        @media (max-width: 680px) {
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
    <div class="panel">
        <h1>Sportlocatie gratis aanmelden</h1>
        <p class="muted">Staat jouw sportschool of sportactiviteit nog niet op GymMaps.nl? Vul dit formulier in. De aanmelding is kosteloos.</p>

        <div class="steps" aria-hidden="true">
            <div class="step active">
                <div class="step-dot">1</div>
                Basisinfo
            </div>
            <div class="step">
                <div class="step-dot">2</div>
                Locatie
            </div>
            <div class="step">
                <div class="step-dot">3</div>
                Details
            </div>
        </div>

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
                    <p class="muted" style="margin: 4px 0 0; text-align:left;">Max 5MB, JPG/PNG/WebP.</p>
                    @error('photo')<div class="error">{{ $message }}</div>@enderror
                </div>

                <div class="actions">
                    <button class="btn btn-primary" type="submit">Gratis aanmelden</button>
                    <a class="btn btn-ghost" href="{{ route('home') }}">Terug naar zoeken</a>
                </div>
            </form>
        </div>
    </div>
</div>
@include('partials.site-footer')
</body>
</html>
