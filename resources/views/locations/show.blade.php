<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.google-site-verification')
    <title>{{ $location->name }} - Gymmap.nl</title>
    @include('partials.favicon')
    <style>
        body { margin:0; font-family:"Segoe UI",Roboto,sans-serif; background:#f4f8fc; color:#0e2235; }
        .container { max-width: 980px; margin: 0 auto; padding: 24px 16px 40px; }
        .card { background:#fff; border:1px solid #d5e1ec; border-radius:14px; padding:18px; margin-bottom:14px; }
        .hero { display:grid; grid-template-columns:1.2fr 1fr; gap:14px; }
        .photo { width:100%; height:300px; object-fit:cover; border-radius:12px; border:1px solid #d5e1ec; background:#edf2f7; }
        .photo-placeholder {
            width:100%;
            height:300px;
            border-radius:12px;
            border:1px solid #d5e1ec;
            background:#edf2f7;
            color:#5f7182;
            display:flex;
            align-items:center;
            justify-content:center;
            font-weight:600;
        }
        .name { margin:0 0 8px; font-size:1.9rem; }
        .muted { color:#5f7182; margin:4px 0; }
        .tags { display:flex; flex-wrap:wrap; gap:8px; margin-top:10px; }
        .tag { background:#e8f7f0; color:#0f6f4c; font-size:0.82rem; border-radius:999px; padding:4px 9px; }
        .btn { display:inline-block; text-decoration:none; border-radius:10px; padding:10px 14px; font-weight:600; }
        .btn-primary { background:#0f8a5f; color:#fff; }
        .btn-ghost { background:#e8eff7; color:#173f60; }
        .toolbar { margin-top:14px; display:flex; gap:10px; flex-wrap:wrap; }
        .opening-card { margin-top: 12px; border: 1px solid #d7e4f0; border-radius: 10px; background: #f8fbff; padding: 10px 12px; }
        .opening-title { margin: 0 0 8px; font-size: 0.92rem; font-weight: 700; color: #123d60; }
        .opening-today { margin: 0 0 8px; font-size: 0.9rem; color: #204d70; }
        .opening-today strong { color: #FF5C39; }
        .opening-list { margin: 0; padding: 0; list-style: none; display: grid; gap: 4px; }
        .opening-list li { font-size: 0.83rem; color: #4e647a; line-height: 1.35; border-top: 1px solid #e7eef6; padding-top: 4px; }
        .opening-list li:first-child { border-top: 0; padding-top: 0; }
        #map { width:100%; min-height:280px; border-radius:12px; }
        @media (max-width: 860px) { .hero { grid-template-columns:1fr; } .photo { height:220px; } }
    </style>
</head>
<body>
@include('partials.site-header')
<div class="container">
    <article class="card hero">
        @if($photoUrl)
            <img class="photo" src="{{ $photoUrl }}" alt="Foto van {{ $location->name }}" onerror="this.onerror=null;this.style.display='none';this.nextElementSibling.style.display='flex';">
            <div class="photo-placeholder" style="display:none;">Geen foto beschikbaar</div>
        @else
            <div class="photo-placeholder">Geen foto beschikbaar</div>
        @endif
        <div>
            <h1 class="name">{{ $location->name }}</h1>
            <p class="muted">{{ $location->address }}</p>
            <p class="muted">{{ $location->postcode }} {{ $location->city }}</p>
            @if($location->phone)
                <p class="muted">Telefoon: {{ $location->phone }}</p>
            @endif
            <div class="tags">
                @foreach($location->sports as $sport)
                    <span class="tag">{{ $sport->name }}</span>
                @endforeach
            </div>

            <div class="toolbar">
                @if($websiteUrl)
                    <a class="btn btn-primary" href="{{ $websiteUrl }}" target="_blank" rel="noopener">Naar officiële website</a>
                @else
                    <a
                        class="btn btn-primary"
                        href="{{ $googleSearchUrl }}"
                        target="_blank"
                        rel="noopener"
                    >
                        Zoek website in Google
                    </a>
                @endif
                <a class="btn btn-ghost" href="{{ route('home') }}">Terug naar overzicht</a>
            </div>

            <aside class="opening-card">
                <p class="opening-title">Openingstijden</p>
                @if(!empty($openingHoursToday))
                    <p class="opening-today"><strong>Vandaag:</strong> {{ $openingHoursToday }}</p>
                @else
                    <p class="opening-today">Vandaag: niet beschikbaar</p>
                @endif

                @if(!empty($openingHoursWeek))
                    <ul class="opening-list">
                        @foreach($openingHoursWeek as $openingLine)
                            <li>{{ $openingLine }}</li>
                        @endforeach
                    </ul>
                @endif
            </aside>
        </div>
    </article>

    <article class="card">
        @if($googleMapsKey !== '')
            <div id="map"></div>
        @else
            <p class="muted">Kaart is niet actief: voeg <code>GOOGLE_MAPS_API_KEY</code> toe in je .env.</p>
        @endif
    </article>
</div>

@if($googleMapsKey !== '')
    <script>
        function initLocationMap() {
            const point = { lat: {{ (float) $location->latitude }}, lng: {{ (float) $location->longitude }} };
            const map = new google.maps.Map(document.getElementById('map'), {
                center: point,
                zoom: 15,
                mapTypeControl: false,
                streetViewControl: false,
            });

            new google.maps.Marker({ position: point, map, title: @json($location->name) });
        }
    </script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key={{ $googleMapsKey }}&callback=initLocationMap"></script>
@endif
@include('partials.site-footer')
</body>
</html>
