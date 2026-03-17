<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.google-site-verification')
    <title>Tarieven - GymMaps.nl</title>
    <meta name="description" content="Premium vermelding op GymMaps: meer zichtbaarheid voor jouw sportschool voor €20 per maand.">
    @include('partials.favicon')
    @include('partials.brand-theme')
    <style>
        :root {
            --bg: #eef4fb;
            --card: #ffffff;
            --line: #d3e0ec;
            --ink: #133f63;
            --muted: #5c7287;
            --cta: #FF5C39;
            --cta-dark: #e64f2e;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: "Segoe UI", Roboto, sans-serif;
            background: transparent;
            color: var(--gm-brand-text);
        }

        .container { max-width: 1120px; margin: 0 auto; padding: 18px 16px 46px; }
        .section { margin-bottom: 14px; }
        .card { background: var(--card); border: 1px solid var(--line); border-radius: 16px; padding: 22px; }
        h1, h2, h3 { margin: 0 0 10px; line-height: 1.2; }
        h1 { font-size: 2.05rem; }
        h2 { font-size: 1.5rem; }
        h3 { font-size: 1.18rem; }
        p { margin: 0; color: var(--muted); line-height: 1.65; }

        .hero {
            display: grid;
            grid-template-columns: 1.4fr 1fr;
            gap: 18px;
            align-items: center;
            background: linear-gradient(135deg, #0f4f80 0%, #0f5f93 54%, #1973a5 100%);
            color: #fff;
            border: 0;
        }

        .hero p { color: rgba(255, 255, 255, 0.92); }

        .hero-badge {
            display: inline-flex;
            padding: 6px 10px;
            border-radius: 999px;
            background: rgba(255, 255, 255, 0.14);
            font-weight: 700;
            font-size: 0.83rem;
            margin-bottom: 10px;
        }

        .hero-visual {
            border-radius: 14px;
            border: 1px solid rgba(255, 255, 255, 0.24);
            background: rgba(255, 255, 255, 0.08);
            padding: 16px;
        }

        .hero-list {
            margin: 0;
            padding: 0;
            list-style: none;
            display: grid;
            gap: 8px;
            font-weight: 600;
            color: #fff;
        }

        .btn {
            display: inline-block;
            text-decoration: none;
            border: 0;
            border-radius: 11px;
            padding: 12px 18px;
            font-weight: 700;
            cursor: pointer;
        }

        .btn-cta { background: var(--cta); color: #fff; }
        .btn-cta:hover { background: var(--cta-dark); }

        .lead { max-width: 68ch; }

        .benefits {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
            margin-top: 12px;
        }

        .benefit {
            border: 1px solid #dbe6f0;
            border-radius: 12px;
            padding: 13px 14px;
            background: #f9fcff;
            color: #1f4f73;
            font-weight: 600;
        }

        .pricing-wrap {
            display: grid;
            grid-template-columns: 1fr;
            gap: 12px;
            margin-top: 10px;
        }

        .price-card {
            border: 1px solid #c6d9ea;
            border-radius: 14px;
            background: linear-gradient(180deg, #f7fbff 0%, #f2f8ff 100%);
            padding: 18px;
        }

        .price-name { font-size: 1.12rem; font-weight: 700; color: #0f446d; margin-bottom: 6px; }
        .price { font-size: 2rem; font-weight: 800; color: #0f446d; margin-bottom: 8px; }

        .price-points {
            margin: 0 0 14px;
            padding-left: 18px;
            color: var(--muted);
            line-height: 1.6;
        }

        .faq {
            display: grid;
            gap: 10px;
            margin-top: 10px;
        }

        details {
            border: 1px solid #d9e5ef;
            border-radius: 12px;
            background: #fff;
            padding: 10px 12px;
        }

        summary {
            cursor: pointer;
            font-weight: 700;
            color: #143f63;
            list-style: none;
        }
        summary::-webkit-details-marker { display: none; }

        details p { margin-top: 8px; }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
            margin-top: 12px;
        }

        .field-wide { grid-column: 1 / -1; }

        label {
            display: block;
            margin: 0 0 6px;
            font-weight: 600;
            color: #1f4f73;
            font-size: 0.95rem;
        }

        input, textarea {
            width: 100%;
            border: 1px solid #ccdceb;
            border-radius: 10px;
            padding: 11px 12px;
            font: inherit;
            color: #173f60;
            background: #fff;
        }

        textarea { min-height: 120px; resize: vertical; }

        .error { margin-top: 4px; font-size: 0.84rem; color: #9d2d2d; }
        .flash {
            margin-bottom: 10px;
            padding: 12px;
            border-radius: 10px;
            border: 1px solid #bfe4cb;
            background: #eaf9ef;
            color: #1a5a3f;
        }

        .cta-bottom {
            text-align: center;
            background: linear-gradient(135deg, #113f66 0%, #145585 100%);
            color: #fff;
            border: 0;
        }

        .cta-bottom p { color: rgba(255, 255, 255, 0.92); margin-bottom: 12px; }
        .mt-12 { margin-top: 12px; }

        @media (max-width: 900px) {
            .hero { grid-template-columns: 1fr; }
            .benefits { grid-template-columns: 1fr; }
            .form-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
@include('partials.site-header')
<main class="container">
    @if(session('status'))
        <div class="flash">{{ session('status') }}</div>
    @endif

    @if($errors->has('mail'))
        <div class="flash" style="background:#fdeeee;border-color:#f3c7c7;color:#852828;">
            {{ $errors->first('mail') }}
        </div>
    @endif

    <section class="section card hero">
        <div>
            <span class="hero-badge">Voor sportscholen in Nederland</span>
            <h1>Meer zichtbaarheid voor jouw sportschool</h1>
            <p>Word beter gevonden via GymMaps en bereik sporters in jouw regio.</p>
            <p class="mt-12">
                <a class="btn btn-cta" href="#premium-aanvraag">Premium aanvragen</a>
            </p>
        </div>
        <aside class="hero-visual">
            <ul class="hero-list">
                <li>+ Meer bereik onder sporters in jouw buurt</li>
                <li>+ Professionele profielpresentatie</li>
                <li>+ Directe aanvragen via GymMaps</li>
            </ul>
        </aside>
    </section>

    <section class="section card">
        <h2>Waarom zichtbaar zijn op GymMaps?</h2>
        <p class="lead">
            Sporters gebruiken GymMaps om sportscholen in de buurt te ontdekken. Met een premium vermelding
            valt jouw sportschool extra op tussen andere locaties, waardoor je meer relevante bezoekers en
            proefles-aanvragen kunt ontvangen.
        </p>
    </section>

    <section class="section card">
        <h2>Voordelen van Premium</h2>
        <div class="benefits">
            <div class="benefit">Beter zichtbaar tussen andere gyms</div>
            <div class="benefit">Uitgelichte vermelding op GymMaps</div>
            <div class="benefit">Meer kans op proefles-aanvragen</div>
            <div class="benefit">Professionele presentatie van jouw sportschool</div>
            <div class="benefit">Bereik sporters in jouw eigen regio</div>
        </div>
    </section>

    <section class="section card">
        <h2>Pricing</h2>
        <div class="pricing-wrap">
            <article class="price-card">
                <p class="price-name">Premium vermelding</p>
                <p class="price">€20 per maand</p>
                <ul class="price-points">
                    <li>Maandelijks opzegbaar</li>
                    <li>Geen opstartkosten</li>
                </ul>
                <a class="btn btn-cta" href="#premium-aanvraag">Vraag Premium aan</a>
            </article>
        </div>
    </section>

    <section class="section card">
        <h2>Veelgestelde vragen</h2>
        <div class="faq">
            <details>
                <summary>Wat houdt een premium vermelding in?</summary>
                <p>Een premium vermelding geeft je sportschool extra zichtbaarheid en een meer uitgelichte positie binnen GymMaps.</p>
            </details>
            <details>
                <summary>Kan ik maandelijks opzeggen?</summary>
                <p>Ja, het premium pakket is maandelijks opzegbaar.</p>
            </details>
            <details>
                <summary>Hoe snel wordt mijn vermelding actief?</summary>
                <p>Na je aanvraag nemen we zo snel mogelijk contact op en zetten we je premium status daarna actief.</p>
            </details>
            <details>
                <summary>Kan ik mijn sportschoolgegevens later aanpassen?</summary>
                <p>Ja, je gegevens en inhoud blijven aanpasbaar zodat je profiel altijd actueel is.</p>
            </details>
        </div>
    </section>

    <section id="premium-aanvraag" class="section card">
        <h2>Premium aanvragen</h2>
        <p>Vul hieronder je gegevens in. We nemen snel contact op om je aanvraag af te ronden.</p>

        <form action="{{ route('pages.pricing.store') }}" method="post" class="form-grid">
            @csrf
            <div>
                <label for="gym_name">Naam sportschool</label>
                <input id="gym_name" name="gym_name" type="text" value="{{ old('gym_name') }}" required>
                @error('gym_name')<p class="error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="contact_name">Naam contactpersoon</label>
                <input id="contact_name" name="contact_name" type="text" value="{{ old('contact_name') }}" required>
                @error('contact_name')<p class="error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="email">E-mailadres</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required>
                @error('email')<p class="error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="phone">Telefoonnummer (optioneel)</label>
                <input id="phone" name="phone" type="text" value="{{ old('phone') }}">
                @error('phone')<p class="error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="city">Plaats</label>
                <input id="city" name="city" type="text" value="{{ old('city') }}" required>
                @error('city')<p class="error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="website">Website (optioneel)</label>
                <input id="website" name="website" type="url" value="{{ old('website') }}" placeholder="https://...">
                @error('website')<p class="error">{{ $message }}</p>@enderror
            </div>
            <div class="field-wide">
                <label for="notes">Opmerkingen (optioneel)</label>
                <textarea id="notes" name="notes">{{ old('notes') }}</textarea>
                @error('notes')<p class="error">{{ $message }}</p>@enderror
            </div>
            <div class="field-wide">
                <button class="btn btn-cta" type="submit">Premium aanvragen</button>
            </div>
        </form>
    </section>

    <section class="section card cta-bottom">
        <h2>Klaar om meer zichtbaar te worden?</h2>
        <p>Vraag vandaag je premium vermelding aan en laat jouw sportschool beter opvallen in jouw regio.</p>
        <a class="btn btn-cta" href="#premium-aanvraag">Premium aanvragen</a>
    </section>
</main>
@include('partials.site-footer')
</body>
</html>
