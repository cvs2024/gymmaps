<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.google-site-verification')
    <title>Gymbuddy zoeken - GymMaps.nl</title>
    @include('partials.favicon')
    <style>
        :root {
            --bg: #f4f8fc;
            --card: #ffffff;
            --ink: #10263d;
            --muted: #5a6f84;
            --line: #d5e2ee;
            --blue: #0f5e88;
            --blue-dark: #0c4f74;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: "Segoe UI", Roboto, sans-serif;
            background:
                radial-gradient(980px 440px at 10% -12%, rgba(23, 84, 140, 0.18), transparent 60%),
                radial-gradient(760px 380px at 90% 0%, rgba(31, 118, 168, 0.16), transparent 58%),
                var(--bg);
            color: var(--ink);
            min-height: 100vh;
        }

        .container { max-width: 1260px; margin: 0 auto; padding: 24px 16px 44px; }

        .btn {
            border: 0;
            border-radius: 10px;
            padding: 10px 14px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
            cursor: pointer;
            transition: background-color .15s ease;
        }

        .btn-primary { background: var(--blue); color: #fff; }
        .btn-primary:hover { background: var(--blue-dark); }

        .btn-secondary {
            background: #edf3fa;
            color: #214360;
            border: 1px solid #cfdeec;
        }

        .btn-secondary:hover { background: #e1edf8; }

        .flash {
            margin-bottom: 12px;
            background: #e8f3fb;
            color: #124265;
            padding: 12px;
            border-radius: 10px;
            border: 1px solid #c5dbeb;
        }

        .top-action {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 12px;
        }

        .layout {
            display: grid;
            grid-template-columns: minmax(280px, 320px) minmax(0, 1fr);
            gap: 14px;
            align-items: start;
            margin-bottom: 14px;
        }

        .card {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 14px;
            padding: 16px;
        }

        .filter-card h2,
        .results-card h2,
        .form-shell h2 {
            margin: 0 0 10px;
            font-size: 1.2rem;
            color: #123f6a;
        }

        .field { margin-bottom: 12px; }
        label { display: block; margin-bottom: 6px; font-weight: 600; color: #1c4161; }

        input,
        select,
        textarea {
            width: 100%;
            border: 1px solid #c7d8e8;
            border-radius: 10px;
            padding: 11px;
            font: inherit;
            background: #f7fbff;
            color: var(--ink);
        }

        .filter-actions {
            display: grid;
            gap: 8px;
            margin-top: 6px;
        }

        .hint {
            margin-top: 10px;
            color: var(--muted);
            font-size: 0.9rem;
            line-height: 1.4;
        }

        .field-help {
            margin-top: 6px;
            color: var(--muted);
            font-size: 0.85rem;
            line-height: 1.35;
        }

        .posts-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 12px;
        }

        .post-card {
            border: 1px solid #d9e4ee;
            border-radius: 12px;
            padding: 12px;
            background: #fcfeff;
            display: grid;
            grid-template-columns: 78px 1fr;
            gap: 10px;
            align-items: start;
        }

        .post-photo {
            width: 78px;
            height: 78px;
            border-radius: 12px;
            object-fit: cover;
            border: 1px solid #ccdae8;
            background: #e9f1f8;
        }

        .post-title {
            margin: 0;
            font-size: 1.05rem;
            line-height: 1.25;
            color: #133b5f;
        }

        .meta {
            margin: 6px 0 0;
            color: var(--muted);
            font-size: 0.9rem;
            line-height: 1.35;
        }

        .chip-wrap {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 8px;
        }

        .chip {
            background: #e8f2fb;
            color: #134567;
            border: 1px solid #cfe1f0;
            border-radius: 999px;
            padding: 4px 8px;
            font-size: 0.78rem;
            font-weight: 600;
        }

        .post-footer {
            margin-top: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .pager {
            margin-top: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .pager a,
        .pager span {
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

        .form-shell {
            background: linear-gradient(180deg, rgba(255,255,255,0.98), rgba(247,250,254,0.97));
            border: 1px solid #d6e2ee;
            border-radius: 18px;
            padding: 18px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .error { color: #9f1d1d; font-size: 0.88rem; margin-top: 4px; }

        @media (max-width: 1040px) {
            .posts-grid { grid-template-columns: 1fr; }
        }

        @media (max-width: 840px) {
            .layout { grid-template-columns: 1fr; }
            .form-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
@include('partials.site-header')

<div class="container">
    <div class="top-action">
        <a class="btn btn-primary" href="#plaats-bericht">Oproep plaatsen</a>
    </div>

    @if(session('status'))
        <div class="flash">{{ session('status') }}</div>
    @endif

    <section class="layout" id="actieve-oproepen">
        <aside class="card filter-card">
            <h2>Filter oproepen</h2>
            <form method="GET" action="{{ route('gymbuddy.index') }}">
                <div class="field">
                    <label for="q">Plaats / regio / postcode</label>
                    <input id="q" type="text" name="q" value="{{ $query }}" placeholder="Bijv. Utrecht of 3511NS">
                </div>

                <div class="field">
                    <label for="sport">Sport</label>
                    <input id="sport" type="text" name="sport" value="{{ $sport }}" placeholder="Bijv. fitness, boksen, padel">
                </div>

                <div class="field">
                    <label for="radius">Radius</label>
                    <select id="radius" name="radius">
                        <option value="5" @selected($radius === 5)>5 km</option>
                        <option value="10" @selected($radius === 10)>10 km</option>
                        <option value="20" @selected($radius === 20)>20 km</option>
                        <option value="50" @selected($radius === 50)>50 km</option>
                        <option value="100" @selected($radius === 100)>100 km</option>
                    </select>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn btn-primary">Filter toepassen</button>
                    <a class="btn btn-secondary" href="{{ route('gymbuddy.index') }}">Reset</a>
                </div>

                @if($query !== '' && !$hasSearchCenter)
                    <p class="hint">Geen exact zoekcentrum gevonden voor deze invoer. Resultaten zijn gefilterd op tekstmatch.</p>
                @elseif($query !== '' && $hasSearchCenter)
                    <p class="hint">Zoekcentrum: <strong>{{ $searchCenterLabel }}</strong> · Radius: <strong>{{ $radius }} km</strong></p>
                @endif
            </form>
        </aside>

        <article class="card results-card">
            <h2>Actieve gymbuddy oproepen</h2>

            <div class="posts-grid">
                @forelse($posts as $post)
                    <article class="post-card">
                        <img class="post-photo" src="{{ $post->profile_photo_url }}" alt="Foto van {{ $post->name }}" onerror="this.onerror=null;this.src='data:image/svg+xml;charset=UTF-8,%3Csvg xmlns=%22http://www.w3.org/2000/svg%22 width=%2278%22 height=%2278%22 viewBox=%220 0 78 78%22%3E%3Crect width=%2278%22 height=%2278%22 rx=%2212%22 fill=%22%23dce9f5%22/%3E%3C/svg%3E';">
                        <div>
                            <p class="post-title">{{ $post->name }} zoekt een gymbuddy</p>
                            <p class="meta">{{ $post->city }}{{ $post->postcode ? ' · '.$post->postcode : '' }}{{ $post->distance_km !== null ? ' · '.number_format($post->distance_km, 1, ',', '.').' km' : '' }}</p>
                            <p class="meta">{{ \Illuminate\Support\Str::limit($post->search_message, 130) }}</p>

                            <div class="chip-wrap">
                                <span class="chip">{{ $post->sport }}</span>
                                <span class="chip">{{ $post->days_per_week }}</span>
                                @if($post->gender_preference)
                                    <span class="chip">{{ str_replace('_', ' ', $post->gender_preference) }}</span>
                                @endif
                            </div>

                            <div class="post-footer">
                                <span class="meta">Geplaatst op {{ $post->created_at->format('d-m-Y') }}</span>
                                <a class="btn btn-primary" href="mailto:{{ $post->email }}?subject={{ rawurlencode('Reactie op jouw gymbuddy oproep') }}">Reageer</a>
                            </div>
                        </div>
                    </article>
                @empty
                    <p class="meta">Nog geen oproepen gevonden met deze filters.</p>
                @endforelse
            </div>

            @if($posts->lastPage() > 1)
                <div class="pager">
                    @if($posts->onFirstPage())
                        <span class="disabled">Vorige</span>
                    @else
                        <a href="{{ $posts->previousPageUrl() }}">Vorige</a>
                    @endif

                    <span>Pagina {{ $posts->currentPage() }} / {{ $posts->lastPage() }}</span>

                    @if($posts->hasMorePages())
                        <a href="{{ $posts->nextPageUrl() }}">Volgende</a>
                    @else
                        <span class="disabled">Volgende</span>
                    @endif
                </div>
            @endif
        </article>
    </section>

    <section class="form-shell" id="plaats-bericht">
        <h2>Plaats jouw oproep</h2>
        <form method="POST" action="{{ route('gymbuddy.store') }}" enctype="multipart/form-data">
            @csrf

            <div class="form-grid">
                <div class="field">
                    <label for="name">Je naam</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required>
                    @error('name')<div class="error">{{ $message }}</div>@enderror
                </div>
                <div class="field">
                    <label for="email">E-mailadres voor reacties</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" required>
                    @error('email')<div class="error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-grid">
                <div class="field">
                    <label for="sport_form">Sport</label>
                    <input id="sport_form" name="sport" type="text" value="{{ old('sport') }}" placeholder="Bijv. fitness, boksen, yoga, padel" required>
                    @error('sport')<div class="error">{{ $message }}</div>@enderror
                </div>
                <div class="field">
                    <label for="days_per_week">Aantal dagen per week</label>
                    <input id="days_per_week" name="days_per_week" type="text" value="{{ old('days_per_week') }}" placeholder="Bijv. 2-3 dagen" required>
                    @error('days_per_week')<div class="error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-grid">
                <div class="field">
                    <label for="city">Plaats</label>
                    <input id="city" name="city" type="text" value="{{ old('city') }}" required>
                    @error('city')<div class="error">{{ $message }}</div>@enderror
                </div>
                <div class="field">
                    <label for="postcode">Postcode (optioneel)</label>
                    <input id="postcode" name="postcode" type="text" value="{{ old('postcode') }}">
                    @error('postcode')<div class="error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-grid">
                <div class="field">
                    <label for="address">Adres (optioneel)</label>
                    <input id="address" name="address" type="text" value="{{ old('address') }}">
                    @error('address')<div class="error">{{ $message }}</div>@enderror
                </div>
                <div class="field">
                    <label for="gender_preference">Voorkeur geslacht</label>
                    <select id="gender_preference" name="gender_preference">
                        <option value="geen_voorkeur" @selected(old('gender_preference', 'geen_voorkeur') === 'geen_voorkeur')>Geen voorkeur</option>
                        <option value="vrouw" @selected(old('gender_preference') === 'vrouw')>Vrouw</option>
                        <option value="man" @selected(old('gender_preference') === 'man')>Man</option>
                        <option value="maakt_niet_uit" @selected(old('gender_preference') === 'maakt_niet_uit')>Maakt niet uit</option>
                    </select>
                    @error('gender_preference')<div class="error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="field">
                <label for="profile_photo">Foto van jezelf (optioneel)</label>
                <input id="profile_photo" name="profile_photo" type="file" accept="image/png,image/jpeg,image/webp">
                <div class="field-help">Toegestaan: JPG, PNG of WEBP. Maximale bestandsgrootte: 10 MB.</div>
                @error('profile_photo')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="field">
                <label for="about_you">Vertel iets over jezelf</label>
                <textarea id="about_you" name="about_you" rows="4" required>{{ old('about_you') }}</textarea>
                @error('about_you')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="field">
                <label for="search_message">Waar ben je naar op zoek in een gymbuddy?</label>
                <textarea id="search_message" name="search_message" rows="4" required>{{ old('search_message') }}</textarea>
                @error('search_message')<div class="error">{{ $message }}</div>@enderror
            </div>

            <button class="btn btn-primary" type="submit">Plaats oproep</button>
        </form>
    </section>
</div>

@include('partials.site-footer')
</body>
</html>
