<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.google-site-verification')
    <title>Gymmap.nl - Sportlocaties in Nederland</title>
    <meta name="description" content="GymMaps.nl helpt je snel sportscholen, personal trainers en sportlocaties in Nederland te vinden op kaart, inclusief adres, afstand en sportfilter.">
    @include('partials.favicon')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap');

        :root {
            color-scheme: light;
            --bg: #f4f7fb;
            --card: #ffffff;
            --text: #0b1f33;
            --muted: #586779;
            --accent: #0f5e88;
            --accent-dark: #0c4f74;
            --border: #d5e0ea;
        }

        * { box-sizing: border-box; }

        .promo-bar {
            width: 100%;
            background: #dbecef;
            border-bottom: 1px solid #cfe3e8;
            color: #11395f;
        }

        .promo-bar-inner {
            max-width: 1400px;
            margin: 0 auto;
            padding: 20px 16px;
            text-align: center;
            font-size: 1.1rem;
            font-weight: 500;
            letter-spacing: 0.01em;
        }

        .promo-bar a {
            color: #0f4f7c;
            font-weight: 700;
            text-decoration: none;
        }

        .promo-bar a:hover {
            text-decoration: underline;
        }

        body {
            margin: 0;
            font-family: "Segoe UI", Roboto, sans-serif;
            background:
                radial-gradient(1100px 560px at 8% -12%, rgba(22, 96, 162, 0.18), transparent 60%),
                radial-gradient(920px 500px at 92% 4%, rgba(31, 118, 168, 0.18), transparent 58%),
                linear-gradient(180deg, #eaf3fc 0%, #f1f7fd 42%, #e9f3fd 100%);
            color: var(--text);
            min-height: 100vh;
        }

        .container {
            max-width: 1220px;
            margin: 0 auto;
            padding: 14px 16px 40px;
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
        .btn-light { background: var(--accent); color: #fff; }
        .btn-light:hover { background: var(--accent-dark); }

        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 16px;
            margin-bottom: 14px;
        }

        .top-layout {
            display: grid;
            grid-template-columns: minmax(320px, 1fr) minmax(0, 2fr);
            gap: 14px;
            align-items: stretch;
            margin-bottom: 14px;
        }

        .filter-intro {
            margin: 0 0 12px;
            color: #11395f;
            font-size: 0.97rem;
            font-weight: 700;
            line-height: 1.45;
            letter-spacing: 0.01em;
        }

        .filter-panel {
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .filter-panel form {
            display: grid;
            gap: 10px;
        }

        .filter-intro p {
            margin: 0;
            color: inherit;
        }

        .field-search { flex: 2 1 0; min-width: 0; position: relative; }
        .field-radius { flex: 1 1 0; min-width: 0; }
        .field-options { flex: 1 1 0; min-width: 0; position: relative; }

        .field-label {
            display: block;
            font-size: 0.86rem;
            font-weight: 600;
            color: var(--muted);
            margin-bottom: 6px;
        }

        input[type="text"], select, .multi-trigger {
            width: 100%;
            height: 44px;
            border: 1px solid var(--border);
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
            border: 1px solid var(--border);
            border-radius: 10px;
            padding: 8px;
            display: none;
            max-height: 230px;
            overflow: auto;
            box-shadow: 0 12px 20px rgba(10, 31, 50, 0.12);
        }

        .multi-menu.open { display: block; }

        .suggestions {
            position: absolute;
            z-index: 30;
            left: 0;
            right: 0;
            top: calc(100% + 6px);
            background: #fff;
            border: 1px solid var(--border);
            border-radius: 10px;
            box-shadow: 0 12px 20px rgba(10, 31, 50, 0.12);
            overflow: hidden;
            display: none;
        }

        .suggestions.open { display: block; }

        .suggestion-item {
            width: 100%;
            border: 0;
            background: #fff;
            text-align: left;
            padding: 10px 12px;
            font: inherit;
            color: var(--text);
            cursor: pointer;
            border-bottom: 1px solid #edf2f7;
        }

        .suggestion-item:last-child { border-bottom: 0; }
        .suggestion-item:hover, .suggestion-item.active { background: #f3f8fd; }

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

        .filter-panel .actions {
            justify-content: stretch;
            margin-top: 4px;
        }

        .filter-panel .actions .btn {
            width: 100%;
            text-align: center;
        }

        .list {
            display: grid;
            gap: 10px;
        }

        .map-wrap {
            overflow: hidden;
            padding: 0;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        #results-map {
            width: 100%;
            min-height: 420px;
            flex: 1 1 auto;
            border-radius: 14px;
        }

        .map-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            padding: 10px 12px 12px;
            border-top: 1px solid #dce7f2;
            background: #f7fbff;
        }

        .map-legend-item {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 999px;
            border: 1px solid #d2e2ee;
            background: #fff;
            padding: 4px 10px;
            font-size: 0.84rem;
            color: #244a68;
            cursor: pointer;
            user-select: none;
            transition: all 0.15s ease;
        }

        .map-legend-item.active {
            border-color: #1e5f95;
            background: #e9f2fb;
            color: #0f4f7c;
            box-shadow: 0 4px 10px rgba(20, 79, 124, 0.18);
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

        .location-photo.logo {
            object-fit: contain;
            padding: 10px;
            background: #fff;
        }

        .flash {
            margin-bottom: 10px;
            background: #e8f7f0;
            color: #14563c;
            padding: 12px;
            border-radius: 10px;
            border: 1px solid #bce8d4;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .stat-item {
            border: 1px solid #cfe0ee;
            border-radius: 10px;
            padding: 10px 12px;
            background: #f7fbff;
        }

        .stat-label {
            color: var(--muted);
            font-size: 0.85rem;
            margin: 0 0 4px;
        }

        .stat-value {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 700;
            color: #103455;
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
            .top-layout {
                grid-template-columns: 1fr;
            }
            .stats { grid-template-columns: 1fr; }
            .field-search, .field-radius, .field-options { flex: 1 1 auto; }
            h1 { font-size: 1.6rem; }
            .location-card { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<div class="promo-bar">
    <div class="promo-bar-inner">
        Jouw sportschool hier belichten? Dat kan.
        <a href="{{ route('pages.contact') }}">Mail ons via het contactformulier!</a>
    </div>
</div>
@include('partials.site-header')

<div class="container">

    @if(session('status'))
        <div class="flash">{{ session('status') }}</div>
    @endif

    <section class="top-layout">
        <aside class="card filter-panel">
            <div class="filter-intro">
                <p>Vind hier de sportschool of andere sportactiviteit bij jou in de buurt!</p>
            </div>
            <form method="GET" action="{{ route('home') }}">
                <div class="field-search">
                    <label class="field-label" for="q">Zoek op locatie / adres / postcode</label>
                    <input id="q" type="text" name="q" value="{{ $query }}" placeholder="Bijv. Utrecht, 3511 NS of Oudegracht 100">
                    <div class="suggestions" id="searchSuggestions"></div>
                </div>

                <div class="field-options" id="sportsFilter">
                    <label class="field-label">Sport opties (multi-select)</label>
                    <button type="button" class="multi-trigger" id="multiTrigger">Kies sporten</button>
                    <div class="multi-menu" id="multiMenu">
                        @foreach($sports as $sport)
                            <label class="opt">
                                <input type="checkbox" name="sports[]" value="{{ $sport->id }}" @checked($selectedSports->contains($sport->id))>
                                {{ $sport->name }}
                            </label>
                        @endforeach
                    </div>
                </div>

                <div class="field-radius">
                    <label class="field-label" for="radius">Radius</label>
                    <select id="radius" name="radius">
                        <option value="5" @selected($radius === 5)>5 km</option>
                        <option value="10" @selected($radius === 10)>10 km</option>
                        <option value="20" @selected($radius === 20)>20 km</option>
                        <option value="50" @selected($radius === 50)>50 km</option>
                        <option value="250" @selected($radius === 250)>50+ km</option>
                    </select>
                </div>

                <div class="actions">
                    <button class="btn btn-primary" type="submit">Vind locaties</button>
                </div>
            </form>
        </aside>

        <article class="card map-wrap">
            <div id="results-map"></div>
            <div class="map-legend">
                <button type="button" class="map-legend-item active" data-category="all">🗺️ Alles</button>
                <button type="button" class="map-legend-item" data-category="fitness">🏋️ Fitness / krachttraining</button>
                <button type="button" class="map-legend-item" data-category="boxing">🥊 Boksschool</button>
                <button type="button" class="map-legend-item" data-category="yoga">🧘 Yogastudio</button>
                <button type="button" class="map-legend-item" data-category="crossfit">🏋️‍♂️ CrossFit</button>
                <button type="button" class="map-legend-item" data-category="other">📍 Overig</button>
            </div>
        </article>
    </section>

    <section class="list">
        @if($isUsingFallbackSource)
            <article class="card">
                <p class="location-name">KVK-locaties nog niet beschikbaar</p>
                <p class="muted">Er zijn nog geen KVK sportscholen met coördinaten geïmporteerd. Tijdelijk tonen we alle beschikbare locaties met coördinaten.</p>
            </article>
        @endif

        @if($query !== '' && !$center && $results->isEmpty())
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

        @foreach(($paginatedResults?->items() ?? []) as $location)
            <article class="card">
                <div class="location-card">
                    <img
                        class="location-photo {{ $location->display_logo_url ? 'logo' : '' }}"
                        src="{{ $location->display_logo_url ?: $location->display_photo_url }}"
                        alt="Foto van {{ $location->name }}"
                        @if($location->display_logo_url)
                            data-fallback="{{ $location->display_photo_url }}"
                            data-final-fallback="{{ $location->fallback_photo_url }}"
                            onerror="if(this.dataset.fallback){this.src=this.dataset.fallback;this.classList.remove('logo');this.dataset.fallback='';return;}if(this.dataset.finalFallback){this.src=this.dataset.finalFallback;this.dataset.finalFallback='';return;}this.onerror=null;"
                        @else
                            onerror="this.onerror=null;this.src='{{ $location->fallback_photo_url }}';this.classList.remove('logo');"
                        @endif
                    >
                    <div>
                        <p class="location-name">{{ $location->name }}</p>
                        <p class="muted">{{ $location->address }}, {{ $location->postcode }} {{ $location->city }}</p>
                        @if($location->distance_km !== null)
                            <p class="muted">Afstand: {{ number_format($location->distance_km, 1, ',', '.') }} km</p>
                        @endif
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

        @if(($paginatedResults?->count() ?? 0) === 0)
            <article class="card">
                <p class="location-name">Nog geen resultaten zichtbaar</p>
                <p class="muted">Controleer de filters en radius, of importeer KVK-data opnieuw zodat kaart en overzicht gevuld worden.</p>
            </article>
        @endif
    </section>
</div>
<script>
    const filterTrigger = document.getElementById('multiTrigger');
    const filterMenu = document.getElementById('multiMenu');
    const filterContainer = document.getElementById('sportsFilter');
    const queryInput = document.getElementById('q');
    const suggestionBox = document.getElementById('searchSuggestions');
    const suggestionEndpoint = @json(route('home.suggestions'));

    if (filterTrigger && filterMenu && filterContainer) {
        const filterChecks = Array.from(filterMenu.querySelectorAll('input[type="checkbox"]'));

        const updateFilterLabel = () => {
            const selected = filterChecks
                .filter((checkbox) => checkbox.checked)
                .map((checkbox) => checkbox.parentElement.textContent.trim());
            filterTrigger.textContent = selected.length ? selected.join(', ') : 'Kies sporten';
        };

        filterTrigger.addEventListener('click', () => {
            filterMenu.classList.toggle('open');
        });

        filterChecks.forEach((checkbox) => checkbox.addEventListener('change', updateFilterLabel));

        document.addEventListener('click', (event) => {
            if (!filterContainer.contains(event.target)) {
                filterMenu.classList.remove('open');
            }
        });

        updateFilterLabel();
    }

    if (queryInput && suggestionBox) {
        let debounceTimer = null;
        let abortController = null;
        let currentItems = [];
        let activeIndex = -1;

        const closeSuggestions = () => {
            suggestionBox.classList.remove('open');
            suggestionBox.innerHTML = '';
            currentItems = [];
            activeIndex = -1;
        };

        const setInputValue = (value) => {
            queryInput.value = value;
            closeSuggestions();
        };

        const updateActive = () => {
            const buttons = Array.from(suggestionBox.querySelectorAll('.suggestion-item'));
            buttons.forEach((button, index) => {
                button.classList.toggle('active', index === activeIndex);
            });
        };

        const renderSuggestions = (items) => {
            currentItems = items;
            activeIndex = -1;
            if (!items.length) {
                closeSuggestions();
                return;
            }

            suggestionBox.innerHTML = items
                .map((item) => `<button type="button" class="suggestion-item" data-value="${item.value.replace(/"/g, '&quot;')}">${item.label}</button>`)
                .join('');
            suggestionBox.classList.add('open');
        };

        queryInput.addEventListener('input', () => {
            const value = queryInput.value.trim();
            if (value.length < 1) {
                closeSuggestions();
                return;
            }

            if (debounceTimer) {
                clearTimeout(debounceTimer);
            }

            debounceTimer = setTimeout(async () => {
                if (abortController) {
                    abortController.abort();
                }

                abortController = new AbortController();

                try {
                    const response = await fetch(`${suggestionEndpoint}?q=${encodeURIComponent(value)}&limit=8`, {
                        method: 'GET',
                        headers: { 'Accept': 'application/json' },
                        signal: abortController.signal,
                    });

                    if (!response.ok) {
                        closeSuggestions();
                        return;
                    }

                    const payload = await response.json();
                    renderSuggestions(Array.isArray(payload.items) ? payload.items : []);
                } catch (error) {
                    closeSuggestions();
                }
            }, 180);
        });

        queryInput.addEventListener('keydown', (event) => {
            if (!currentItems.length) {
                return;
            }

            if (event.key === 'ArrowDown') {
                event.preventDefault();
                activeIndex = Math.min(activeIndex + 1, currentItems.length - 1);
                updateActive();
            } else if (event.key === 'ArrowUp') {
                event.preventDefault();
                activeIndex = Math.max(activeIndex - 1, 0);
                updateActive();
            } else if (event.key === 'Enter' && activeIndex >= 0) {
                event.preventDefault();
                setInputValue(currentItems[activeIndex].value);
            } else if (event.key === 'Escape') {
                closeSuggestions();
            }
        });

        suggestionBox.addEventListener('click', (event) => {
            const button = event.target.closest('.suggestion-item');
            if (!button) {
                return;
            }

            setInputValue(button.dataset.value || '');
        });

        document.addEventListener('click', (event) => {
            if (!suggestionBox.contains(event.target) && event.target !== queryInput) {
                closeSuggestions();
            }
        });
    }
</script>
@if($googleMapsKey !== '')
    <script>
        function initGymmapResultsMap() {
            const hasSearchCenter = {{ $hasSearchCenter ? 'true' : 'false' }};
            const hasLocationQuery = {{ $query !== '' ? 'true' : 'false' }};
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
                styles: [
                    { elementType: "geometry", stylers: [{ color: "#e8f1f7" }] },
                    { elementType: "labels.text.fill", stylers: [{ color: "#2a4866" }] },
                    { elementType: "labels.text.stroke", stylers: [{ color: "#ffffff" }] },
                    { featureType: "administrative", elementType: "geometry.stroke", stylers: [{ color: "#a8c0d6" }] },
                    { featureType: "poi", elementType: "geometry", stylers: [{ color: "#d8e9d8" }] },
                    { featureType: "poi.park", elementType: "geometry", stylers: [{ color: "#cce4cf" }] },
                    { featureType: "road", elementType: "geometry", stylers: [{ color: "#ffffff" }] },
                    { featureType: "road.arterial", elementType: "geometry", stylers: [{ color: "#f2f7fb" }] },
                    { featureType: "road.highway", elementType: "geometry", stylers: [{ color: "#d5e4f4" }] },
                    { featureType: "transit", elementType: "geometry", stylers: [{ color: "#dce8f3" }] },
                    { featureType: "water", elementType: "geometry", stylers: [{ color: "#9ed1ee" }] },
                    { featureType: "water", elementType: "labels.text.fill", stylers: [{ color: "#21506f" }] },
                ],
            });

            const getCategory = (sports) => {
                const joined = Array.isArray(sports)
                    ? sports.join(" ").toLowerCase()
                    : "";
                if (joined.includes("crossfit")) return "crossfit";
                if (joined.includes("bok")) return "boxing";
                if (joined.includes("yoga")) return "yoga";
                if (joined.includes("fitness") || joined.includes("kracht")) return "fitness";
                return "other";
            };

            const categoryMeta = {
                fitness: { emoji: "🏋️", color: "#0d8f6f" },
                boxing: { emoji: "🥊", color: "#2f76d0" },
                yoga: { emoji: "🧘", color: "#4d9860" },
                crossfit: { emoji: "🏋️‍♂️", color: "#195f9b" },
                other: { emoji: "📍", color: "#4f6b85" },
            };

            const markerIcon = (category) => {
                const config = categoryMeta[category] ?? categoryMeta.other;
                const svg = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="46" height="46" viewBox="0 0 46 46">
                        <circle cx="23" cy="23" r="20" fill="${config.color}" opacity="0.94" />
                        <circle cx="23" cy="23" r="20" fill="none" stroke="#ffffff" stroke-width="2.8" />
                    </svg>
                `.trim();

                return {
                    url: `data:image/svg+xml;charset=UTF-8,${encodeURIComponent(svg)}`,
                    scaledSize: new google.maps.Size(46, 46),
                    anchor: new google.maps.Point(23, 23),
                    labelOrigin: new google.maps.Point(23, 24),
                };
            };

            if (hasSearchCenter) {
                new google.maps.Marker({
                    position: center,
                    map,
                    title: "Zoekcentrum",
                    icon: {
                        path: google.maps.SymbolPath.CIRCLE,
                        fillColor: "#174f86",
                        fillOpacity: 1,
                        strokeColor: "#ffffff",
                        strokeWeight: 2,
                        scale: 8,
                    },
                });
            }

            const infoWindow = new google.maps.InfoWindow();
            const bounds = new google.maps.LatLngBounds();
            bounds.extend(center);

            const markers = [];
            let clustererInstance = null;
            let activeCategory = 'all';
            const shouldShowMarkers = hasLocationQuery;

            locations.forEach((location) => {
                const category = getCategory(location.sports);
                const marker = new google.maps.Marker({
                    position: { lat: location.lat, lng: location.lng },
                    map: shouldShowMarkers ? map : null,
                    title: location.name,
                    icon: markerIcon(category),
                    label: {
                        text: (categoryMeta[category] ?? categoryMeta.other).emoji,
                        color: "#ffffff",
                        fontSize: "16px",
                        fontWeight: "700",
                    },
                });
                marker.gymmapsCategory = category;
                markers.push(marker);

                if (shouldShowMarkers) {
                    bounds.extend(marker.getPosition());
                }

                marker.addListener("click", () => {
                    const distanceLine = location.distance !== null
                        ? `<br>Afstand: ${escapeHtml(location.distance.toFixed(1))} km`
                        : "";
                    const photoLine = (location.logo_url || location.photo_url)
                        ? `<br><img src="${escapeHtml(location.logo_url || location.photo_url)}" alt="${escapeHtml(location.name)}" style="width:160px;height:90px;object-fit:${location.logo_url ? 'contain' : 'cover'};padding:${location.logo_url ? '8px' : '0'};background:#fff;border-radius:6px;margin-top:6px;" ${location.logo_url ? `data-fallback="${escapeHtml(location.photo_url || '')}"` : ''} data-final-fallback="${escapeHtml(location.fallback_photo_url || '')}" onerror="if(this.dataset.fallback){this.src=this.dataset.fallback;this.style.objectFit='cover';this.style.padding='0';this.dataset.fallback='';return;}if(this.dataset.finalFallback){this.src=this.dataset.finalFallback;this.dataset.finalFallback='';return;}this.onerror=null;">`
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

            if (shouldShowMarkers && locations.length > 0) {
                map.fitBounds(bounds);
            }

            if (shouldShowMarkers && window.markerClusterer && markers.length > 1) {
                const clusterRenderer = {
                    render({ count, position, markers: clusterMarkers }) {
                        const counts = clusterMarkers.reduce((acc, marker) => {
                            const key = marker.gymmapsCategory || "other";
                            acc[key] = (acc[key] ?? 0) + 1;
                            return acc;
                        }, {});
                        const dominantCategory = Object.keys(counts).sort((a, b) => counts[b] - counts[a])[0] || "other";
                        const meta = categoryMeta[dominantCategory] ?? categoryMeta.other;
                        const svg = `
                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 64 64">
                                <circle cx="32" cy="32" r="29" fill="${meta.color}" fill-opacity="0.88" />
                                <circle cx="32" cy="32" r="29" fill="none" stroke="#ffffff" stroke-width="3" />
                            </svg>
                        `.trim();

                        return new google.maps.Marker({
                            position,
                            icon: {
                                url: `data:image/svg+xml;charset=UTF-8,${encodeURIComponent(svg)}`,
                                scaledSize: new google.maps.Size(64, 64),
                                anchor: new google.maps.Point(32, 32),
                            },
                            label: {
                                text: `${meta.emoji} ${count}`,
                                color: "#ffffff",
                                fontSize: "15px",
                                fontWeight: "700",
                            },
                            zIndex: Number(google.maps.Marker.MAX_ZINDEX) + count,
                        });
                    },
                };

                clustererInstance = new markerClusterer.MarkerClusterer({ map, markers, renderer: clusterRenderer });
            }

            const legendItems = Array.from(document.querySelectorAll('.map-legend-item'));

            const fitVisibleBounds = () => {
                const visibleMarkers = markers.filter((marker) => marker.getVisible());
                if (!visibleMarkers.length) {
                    return;
                }

                const filteredBounds = new google.maps.LatLngBounds();
                visibleMarkers.forEach((marker) => filteredBounds.extend(marker.getPosition()));
                map.fitBounds(filteredBounds);
            };

            const applyLegendFilter = (category) => {
                activeCategory = category;
                legendItems.forEach((item) => {
                    item.classList.toggle('active', item.dataset.category === category);
                });

                markers.forEach((marker) => {
                    const isVisible = shouldShowMarkers && (category === 'all' || marker.gymmapsCategory === category);
                    marker.setVisible(isVisible);
                });

                if (clustererInstance) {
                    const visibleMarkers = markers.filter((marker) => marker.getVisible());
                    clustererInstance.clearMarkers();
                    if (visibleMarkers.length > 1) {
                        clustererInstance.addMarkers(visibleMarkers);
                    }
                }

                fitVisibleBounds();
            };

            legendItems.forEach((item) => {
                item.addEventListener('click', () => {
                    const category = item.dataset.category || 'all';
                    applyLegendFilter(category);
                });
            });

            const initialLegend = legendItems.find((item) => item.dataset.category === activeCategory);
            if (initialLegend) {
                initialLegend.classList.add('active');
            }

            if (!shouldShowMarkers) {
                markers.forEach((marker) => marker.setVisible(false));
                legendItems.forEach((item) => item.classList.remove('active'));
            }
        }
    </script>
    <script src="https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js"></script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key={{ $googleMapsKey }}&callback=initGymmapResultsMap"></script>
@else
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        (function initGymmapLeafletFallback() {
            const hasSearchCenter = {{ $hasSearchCenter ? 'true' : 'false' }};
            const hasLocationQuery = {{ $query !== '' ? 'true' : 'false' }};
            const center = [{{ $mapCenterLat }}, {{ $mapCenterLng }}];
            const locations = @json($mapLocations);
            const escapeHtml = (value) =>
                String(value ?? '')
                    .replaceAll('&', '&amp;')
                    .replaceAll('<', '&lt;')
                    .replaceAll('>', '&gt;')
                    .replaceAll('"', '&quot;')
                    .replaceAll("'", '&#039;');
            const map = L.map('results-map', { zoomControl: true }).setView(center, {{ $mapZoom }});

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap-bijdragers',
            }).addTo(map);

            if (hasSearchCenter) {
                L.circleMarker(center, {
                    radius: 8,
                    color: '#ffffff',
                    weight: 2,
                    fillColor: '#174f86',
                    fillOpacity: 1,
                }).addTo(map).bindPopup('Zoekcentrum');
            }

            const markerRefs = [];

            const getCategory = (sports) => {
                const joined = Array.isArray(sports) ? sports.join(' ').toLowerCase() : '';
                if (joined.includes('crossfit')) return 'crossfit';
                if (joined.includes('bok')) return 'boxing';
                if (joined.includes('yoga')) return 'yoga';
                if (joined.includes('fitness') || joined.includes('kracht')) return 'fitness';
                return 'other';
            };

            const categoryMeta = {
                fitness: { emoji: '🏋️' },
                boxing: { emoji: '🥊' },
                yoga: { emoji: '🧘' },
                crossfit: { emoji: '🏋️‍♂️' },
                other: { emoji: '📍' },
            };

            locations.forEach((location) => {
                const category = getCategory(location.sports);
                const icon = L.divIcon({
                    html: `<div style="width:34px;height:34px;border-radius:50%;background:#0f5f8b;border:2px solid #fff;color:#fff;display:flex;align-items:center;justify-content:center;font-size:18px;box-shadow:0 2px 8px rgba(0,0,0,.3);">${(categoryMeta[category] || categoryMeta.other).emoji}</div>`,
                    className: '',
                    iconSize: [34, 34],
                    iconAnchor: [17, 17],
                });

                const marker = L.marker([location.lat, location.lng], { icon });
                if (hasLocationQuery) {
                    marker.addTo(map);
                }
                marker.gymmapsCategory = category;
                const distanceLine = location.distance !== null ? `<br>Afstand: ${location.distance.toFixed(1)} km` : '';
                const photoCandidate = location.logo_url || location.photo_url || location.fallback_photo_url;
                const photoLine = photoCandidate
                    ? `<br><img src="${escapeHtml(photoCandidate)}" alt="${escapeHtml(location.name)}" style="width:160px;height:90px;object-fit:cover;border-radius:6px;margin-top:6px;" onerror="this.onerror=null;this.src='${escapeHtml(location.fallback_photo_url || '')}'">`
                    : '';
                const detailLink = location.detail_url
                    ? `<br><a href="${escapeHtml(location.detail_url)}" style="display:inline-block;margin-top:8px;padding:7px 10px;background:#0f8a5f;color:#fff;text-decoration:none;border-radius:7px;">Bekijk sportschool</a>`
                    : '';
                marker.bindPopup(`<strong>${escapeHtml(location.name)}</strong><br>${escapeHtml(location.address)}${distanceLine}${photoLine}${detailLink}`);
                markerRefs.push(marker);
            });

            const legendItems = Array.from(document.querySelectorAll('.map-legend-item'));

            const fitVisibleBounds = () => {
                const visible = markerRefs.filter((marker) => map.hasLayer(marker));
                if (!visible.length) {
                    return;
                }
                const bounds = L.latLngBounds(visible.map((marker) => marker.getLatLng()));
                map.fitBounds(bounds, { padding: [28, 28] });
            };

            const applyLegendFilter = (category) => {
                legendItems.forEach((item) => {
                    item.classList.toggle('active', item.dataset.category === category);
                });

                markerRefs.forEach((marker) => {
                    const match = hasLocationQuery && (category === 'all' || marker.gymmapsCategory === category);
                    if (match && !map.hasLayer(marker)) {
                        marker.addTo(map);
                    }
                    if (!match && map.hasLayer(marker)) {
                        map.removeLayer(marker);
                    }
                });

                fitVisibleBounds();
            };

            legendItems.forEach((item) => {
                item.addEventListener('click', () => {
                    applyLegendFilter(item.dataset.category || 'all');
                });
            });

            applyLegendFilter(hasLocationQuery ? 'all' : 'none');
        })();
    </script>
@endif
@include('partials.site-footer')
</body>
</html>
