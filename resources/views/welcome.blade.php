<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.google-site-verification')
    <title>{{ config('app.name', 'Gymmaps') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('logo/gymmaps-logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('logo/gymmaps-logo.png') }}">
    <style>
        :root {
            --bg: #f4f8fc;
            --card: #ffffff;
            --border: #d2ddea;
            --text: #0e2235;
            --muted: #5f7285;
            --accent: #0f8a5f;
            --accent-dark: #0b6a48;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: "Segoe UI", Roboto, sans-serif;
            background: var(--bg);
            color: var(--text);
        }

        .hero {
            width: 100%;
            height: 400px;
            background: linear-gradient(135deg, #103455, #1a6ea0);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            padding: 24px;
            text-align: center;
        }

        .hero h1 {
            margin: 0;
            font-size: clamp(1.8rem, 3vw, 3rem);
            max-width: 900px;
        }

        .container {
            max-width: 1200px;
            margin: -40px auto 32px;
            padding: 0 16px;
        }

        .search-bar {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 14px;
            box-shadow: 0 8px 24px rgba(12, 35, 56, 0.08);
        }

        .row {
            display: flex;
            gap: 10px;
            align-items: stretch;
        }

        .field-search { flex: 0 0 50%; }
        .field-radius { flex: 0 0 25%; }
        .field-options { flex: 0 0 25%; position: relative; }

        label {
            display: block;
            font-size: 0.86rem;
            font-weight: 600;
            color: var(--muted);
            margin-bottom: 6px;
        }

        input[type="text"], select, .multi-trigger {
            width: 100%;
            height: 44px;
            border: 1px solid #c2d2e2;
            border-radius: 10px;
            padding: 10px 12px;
            font: inherit;
            background: #fff;
            color: var(--text);
        }

        .multi-trigger {
            text-align: left;
            cursor: pointer;
        }

        .multi-menu {
            position: absolute;
            z-index: 20;
            left: 0;
            right: 0;
            top: calc(100% + 6px);
            background: #fff;
            border: 1px solid #c2d2e2;
            border-radius: 10px;
            padding: 8px;
            display: none;
            max-height: 230px;
            overflow: auto;
            box-shadow: 0 12px 20px rgba(10, 31, 50, 0.12);
        }

        .multi-menu.open { display: block; }

        .opt {
            display: flex;
            gap: 8px;
            align-items: center;
            padding: 6px 4px;
            font-size: 0.95rem;
        }

        .actions {
            margin-top: 12px;
            display: flex;
            justify-content: flex-end;
        }

        .btn {
            border: 0;
            border-radius: 10px;
            padding: 10px 14px;
            font-weight: 600;
            cursor: pointer;
            background: var(--accent);
            color: #fff;
        }

        .btn:hover { background: var(--accent-dark); }

        @media (max-width: 900px) {
            .row { flex-direction: column; }
            .field-search, .field-radius, .field-options { flex: 1 1 auto; }
            .container { margin-top: 16px; }
        }
    </style>
</head>
<body>
@include('partials.site-header')
    <header class="hero">
        <h1>Vind hier de sportschool of andere sportactiviteit bij jou in de buurt</h1>
    </header>

    <main class="container">
        <section class="search-bar">
            <div class="row">
                <div class="field-search">
                    <label for="q">Zoek op locatie / adres / postcode</label>
                    <input id="q" type="text" placeholder="Bijv. Utrecht, 3511 NS of Oudegracht 100">
                </div>

                <div class="field-radius">
                    <label for="radius">Radius</label>
                    <select id="radius">
                        <option>5 km</option>
                        <option selected>10 km</option>
                        <option>20 km</option>
                        <option>50 km</option>
                    </select>
                </div>

                <div class="field-options" id="sportsFilter">
                    <label>Sport opties (multi-select)</label>
                    <button type="button" class="multi-trigger" id="multiTrigger">Kies sporten</button>
                    <div class="multi-menu" id="multiMenu">
                        <label class="opt"><input type="checkbox" value="fitness"> Fitness</label>
                        <label class="opt"><input type="checkbox" value="yoga"> Yoga</label>
                        <label class="opt"><input type="checkbox" value="boksen"> Boksen</label>
                        <label class="opt"><input type="checkbox" value="crossfit"> CrossFit</label>
                        <label class="opt"><input type="checkbox" value="tennis"> Tennis</label>
                        <label class="opt"><input type="checkbox" value="squash"> Squash</label>
                    </div>
                </div>
            </div>

            <div class="actions">
                <button class="btn" type="button">Zoeken</button>
            </div>
        </section>
    </main>

    <script>
        const trigger = document.getElementById('multiTrigger');
        const menu = document.getElementById('multiMenu');
        const filter = document.getElementById('sportsFilter');
        const checks = Array.from(menu.querySelectorAll('input[type="checkbox"]'));

        function updateLabel() {
            const selected = checks.filter(c => c.checked).map(c => c.parentElement.textContent.trim());
            trigger.textContent = selected.length ? selected.join(', ') : 'Kies sporten';
        }

        trigger.addEventListener('click', () => {
            menu.classList.toggle('open');
        });

        checks.forEach(c => c.addEventListener('change', updateLabel));

        document.addEventListener('click', (e) => {
            if (!filter.contains(e.target)) {
                menu.classList.remove('open');
            }
        });
    </script>
@include('partials.site-footer')
</body>
</html>
