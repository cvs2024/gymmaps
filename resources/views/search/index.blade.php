<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gymmap.nl - Sportlocaties in Nederland</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap');

        :root {
            color-scheme: light;
            --bg: #f4f7fb;
            --card: #ffffff;
            --text: #0b1f33;
            --muted: #586779;
            --accent: #0f8a5f;
            --accent-dark: #0a6a48;
            --border: #d5e0ea;
        }

        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "Segoe UI", Roboto, sans-serif;
            background: radial-gradient(circle at top right, #dceefe, var(--bg));
            color: var(--text);
        }

        .site-nav {
            width: 100%;
            background: #ffffff;
            border-bottom: 1px solid #d9e4ee;
        }

        .site-nav-inner {
            max-width: 1050px;
            margin: 0 auto;
            padding: 12px 16px;
            display: grid;
            grid-template-columns: auto 1fr auto;
            gap: 20px;
            align-items: center;
        }

        .nav-logo {
            display: inline-flex;
            align-items: center;
            text-decoration: none;
        }

        .nav-logo img {
            height: 42px;
            width: auto;
            display: block;
        }

        .nav-menu {
            display: flex;
            justify-content: flex-end;
            gap: 22px;
            font-family: "Poppins", "Segoe UI", sans-serif;
            font-weight: 300;
            font-size: 15px;
        }

        .nav-menu a {
            color: #21415e;
            text-decoration: none;
        }

        .nav-menu a:hover {
            color: #0f8a5f;
        }

        .nav-auth {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
        }

        .nav-btn {
            border-radius: 10px;
            padding: 9px 14px;
            font-weight: 600;
            text-decoration: none;
            border: 1px solid #c7d6e5;
        }

        .nav-btn-signup {
            background: #0f8a5f;
            border-color: #0f8a5f;
            color: #fff;
        }

        .nav-btn-login {
            background: #fff;
            color: #21415e;
        }

        .hero {
            width: 100%;
            min-height: 400px;
            background: linear-gradient(135deg, #113a5c, #176089);
            color: #fff;
            padding: 24px 16px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .hero-inner {
            width: min(1050px, 100%);
        }

        .container {
            max-width: 1050px;
            margin: 0 auto;
            padding: 28px 16px 40px;
        }

        h1 { margin: 0 0 8px; font-size: 2rem; }
        p { margin: 0; color: inherit; }

        .toolbar {
            margin-top: 16px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .btn {
            border: 0;
            border-radius: 10px;
            padding: 10px 14px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            cursor: pointer;
        }

        .btn-primary { background: var(--accent); color: #fff; }
        .btn-primary:hover { background: var(--accent-dark); }
        .btn-light { background: #fff; color: #0d3655; }

        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 16px;
            margin-bottom: 14px;
        }

        .grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 10px;
        }

        input[type="text"], select {
            width: 100%;
            padding: 10px;
            border: 1px solid var(--border);
            border-radius: 10px;
            margin-top: 6px;
        }

        .sports {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 10px;
        }

        .pill {
            border: 1px solid var(--border);
            border-radius: 99px;
            padding: 7px 11px;
            background: #f9fbfd;
            font-size: 0.9rem;
        }

        .list {
            display: grid;
            gap: 10px;
        }

        .map-wrap {
            overflow: hidden;
            padding: 0;
        }

        #results-map {
            width: 100%;
            min-height: 420px;
            border-radius: 14px;
        }

        .location-name {
            font-size: 1.1rem;
            font-weight: 700;
            margin: 0;
        }

        .muted {
            color: var(--muted);
            font-size: 0.95rem;
            margin-top: 4px;
        }

        .tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px;
        }

        .tag {
            background: #e8f7f0;
            color: #0d6b49;
            font-size: 0.8rem;
            border-radius: 99px;
            padding: 4px 9px;
        }

        .location-card {
            display: grid;
            grid-template-columns: 190px 1fr;
            gap: 14px;
            align-items: start;
        }

        .location-photo {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 10px;
            border: 1px solid var(--border);
            background: #edf2f7;
        }

        .flash {
            margin-bottom: 10px;
            background: #e8f7f0;
            color: #14563c;
            padding: 12px;
            border-radius: 10px;
            border: 1px solid #bce8d4;
        }

        .pager {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .pager a, .pager span {
            font-size: 0.92rem;
            color: #21415e;
            text-decoration: none;
            padding: 8px 10px;
            border-radius: 8px;
            border: 1px solid #c7d6e5;
            background: #f8fbfe;
        }

        .pager span.disabled {
            color: #90a0b1;
            border-color: #dde6ef;
            background: #f4f7fb;
        }

        @media (max-width: 760px) {
            .site-nav-inner {
                grid-template-columns: 1fr;
                gap: 12px;
            }
            .nav-menu, .nav-auth {
                justify-content: flex-start;
                flex-wrap: wrap;
            }
            .grid { grid-template-columns: 1fr; }
            h1 { font-size: 1.6rem; }
            .location-card { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<nav class="site-nav">
    <div class="site-nav-inner">
        <a class="nav-logo" href="{{ route('home') }}">
            <img src="{{ asset('logo') }}" alt="Gymmap logo">
        </a>

        <div class="nav-menu">
            <a href="{{ route('home') }}">Home</a>
            <a href="{{ route('gymbuddy.index') }}">Gymbuddy</a>
            <a href="{{ route('listing-requests.create') }}">Locatie aanmelden</a>
        </div>

        <div class="nav-auth">
            <a class="nav-btn nav-btn-signup" href="{{ route('listing-requests.create') }}">Aanmelden</a>
            <a class="nav-btn nav-btn-login" href="{{ url('/login') }}">Inloggen</a>
        </div>
    </div>
</nav>

<section class="hero">
    <div class="hero-inner">
        <h1>Vind hier de sportschool of andere sportactiviteit bij jou in de buurt!</h1>
        <p>Voer je adres, postcode of plaats in en klik op de zoekknop.</p>
        <div class="toolbar">
            <a class="btn btn-light" href="{{ route('listing-requests.create') }}">Locatie aanmelden (gratis)</a>
            <a class="btn btn-light" href="{{ route('gymbuddy.index') }}">Gymbuddy zoeken</a>
        </div>
    </div>
</section>

<div class="container">

    @if(session('status'))
        <div class="flash">{{ session('status') }}</div>
    @endif

    <section class="card">
        <form method="GET" action="{{ route('home') }}">
            <div class="grid">
                <label>
                    Zoek op adres, postcode of plaats
                    <input type="text" name="q" value="{{ $query }}" placeholder="Bijv. Utrecht of 3511 NS">
                </label>
                <label>
                    Radius
                    <select name="radius">
                        <option value="5" @selected($radius === 5)>5 km</option>
                        <option value="10" @selected($radius === 10)>10 km</option>
                        <option value="20" @selected($radius === 20)>20 km</option>
                        <option value="50" @selected($radius === 50)>50 km</option>
                        <option value="250" @selected($radius === 250)>50+ km</option>
                    </select>
                </label>
            </div>

            <div class="sports">
                @foreach($sports as $sport)
                    <label class="pill">
                        <input type="checkbox" name="sports[]" value="{{ $sport->id }}" @checked($selectedSports->contains($sport->id))>
                        {{ $sport->name }}
                    </label>
                @endforeach
            </div>

            <div style="margin-top: 12px;">
                <button class="btn btn-primary" type="submit">Vind locaties</button>
            </div>
        </form>
    </section>

    <section class="list">
        <article class="card map-wrap">
            @if($googleMapsKey !== '')
                <div id="results-map"></div>
            @else
                <div style="padding: 16px;">
                    <p class="location-name">Kaart nog niet actief</p>
                    <p class="muted">Voeg een geldige Google Maps API-key toe in <code>GOOGLE_MAPS_API_KEY</code> om de kaart en stipjes te tonen.</p>
                </div>
            @endif
        </article>

        @if($query !== '' && !$center)
            <article class="card">
                <p class="location-name">Geen middelpunt gevonden voor "{{ $query }}"</p>
                <p class="muted">Tip: probeer een plaatsnaam of postcode die al bekend is in de database.</p>
            </article>
        @endif

        @if($center)
            <article class="card">
                <p class="muted">Zoekcentrum: <strong>{{ $center->city }}</strong> ({{ $center->postcode }}) · {{ $results->count() }} resultaten · pagina {{ $paginatedResults?->currentPage() ?? 1 }} van {{ $paginatedResults?->lastPage() ?? 1 }}</p>
            </article>
        @endif

        @if($center && $results->isEmpty())
            <article class="card">
                <p class="location-name">Geen resultaten binnen deze straal</p>
                <p class="muted">Probeer een grotere radius zoals 20 of 50 km.</p>
            </article>
        @endif

        @if($center)
            @foreach(($paginatedResults?->items() ?? []) as $location)
                <article class="card">
                    <div class="location-card">
                        <img
                            class="location-photo"
                            src="{{ $location->display_photo_url }}"
                            alt="Foto van {{ $location->name }}"
                        >
                        <div>
                            <p class="location-name">{{ $location->name }}</p>
                            <p class="muted">{{ $location->address }}, {{ $location->postcode }} {{ $location->city }}</p>
                            <p class="muted">Afstand: {{ number_format($location->distance_km, 1, ',', '.') }} km</p>
                            <div class="tags">
                                @foreach($location->sports as $sport)
                                    <span class="tag">{{ $sport->name }}</span>
                                @endforeach
                            </div>
                            @if($location->phone)
                                <p class="muted">
                                    {{ $location->phone }}
                                </p>
                            @endif
                            <p style="margin-top: 10px;">
                                <a class="btn btn-primary" href="{{ route('locations.show', $location) }}">Bekijk sportschool</a>
                            </p>
                        </div>
                    </div>
                </article>
            @endforeach

            @if($paginatedResults && $paginatedResults->lastPage() > 1)
                <article class="card">
                    <div class="pager">
                        @if($paginatedResults->onFirstPage())
                            <span class="disabled">Vorige</span>
                        @else
                            <a href="{{ $paginatedResults->previousPageUrl() }}">Vorige</a>
                        @endif

                        <span>Pagina {{ $paginatedResults->currentPage() }} / {{ $paginatedResults->lastPage() }}</span>

                        @if($paginatedResults->hasMorePages())
                            <a href="{{ $paginatedResults->nextPageUrl() }}">Volgende</a>
                        @else
                            <span class="disabled">Volgende</span>
                        @endif
                    </div>
                </article>
            @endif
        @endif
    </section>
</div>
@if($googleMapsKey !== '')
    <script>
        function initGymmapResultsMap() {
            const hasSearchCenter = {{ $hasSearchCenter ? 'true' : 'false' }};
            const center = {
                lat: {{ $mapCenterLat }},
                lng: {{ $mapCenterLng }},
            };

            const locations = @json($mapLocations);

            const escapeHtml = (value) =>
                String(value ?? "")
                    .replaceAll("&", "&amp;")
                    .replaceAll("<", "&lt;")
                    .replaceAll(">", "&gt;")
                    .replaceAll('"', "&quot;")
                    .replaceAll("'", "&#039;");

            const map = new google.maps.Map(document.getElementById("results-map"), {
                center,
                zoom: {{ $mapZoom }},
                mapTypeControl: false,
                streetViewControl: false,
                fullscreenControl: true,
            });

            if (hasSearchCenter) {
                new google.maps.Marker({
                    position: center,
                    map,
                    title: "Zoekcentrum",
                    icon: "https://maps.google.com/mapfiles/ms/icons/blue-dot.png",
                });
            }

            const infoWindow = new google.maps.InfoWindow();
            const bounds = new google.maps.LatLngBounds();
            bounds.extend(center);

            const markers = [];

            locations.forEach((location) => {
                const marker = new google.maps.Marker({
                    position: { lat: location.lat, lng: location.lng },
                    map,
                    title: location.name,
                });
                markers.push(marker);

                bounds.extend(marker.getPosition());

                marker.addListener("click", () => {
                    const distanceLine = location.distance !== null
                        ? `<br>Afstand: ${escapeHtml(location.distance.toFixed(1))} km`
                        : "";
                    const photoLine = location.photo_url
                        ? `<br><img src="${escapeHtml(location.photo_url)}" alt="${escapeHtml(location.name)}" style="width:160px;height:90px;object-fit:cover;border-radius:6px;margin-top:6px;">`
                        : "";
                    const detailLink = location.detail_url
                        ? `<br><a href="${escapeHtml(location.detail_url)}" style="display:inline-block;margin-top:8px;padding:7px 10px;background:#0f8a5f;color:#fff;text-decoration:none;border-radius:7px;">Bekijk sportschool</a>`
                        : "";
                    infoWindow.setContent(
                        `<strong>${escapeHtml(location.name)}</strong><br>${escapeHtml(location.address)}${distanceLine}${photoLine}${detailLink}`
                    );
                    infoWindow.open(map, marker);
                });
            });

            if (locations.length > 0 && hasSearchCenter) {
                map.fitBounds(bounds);
            }

            if (window.markerClusterer && markers.length > 1) {
                new markerClusterer.MarkerClusterer({ map, markers });
            }
        }
    </script>
    <script src="https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js"></script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key={{ $googleMapsKey }}&callback=initGymmapResultsMap"></script>
@endif
</body>
</html>
