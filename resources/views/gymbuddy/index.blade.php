<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gymbuddy zoeken - Gymmap.nl</title>
    <link rel="icon" type="image/png" href="{{ asset('logo/gymmaps-logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('logo/gymmaps-logo.png') }}">
    <style>
        :root {
            --blue-900: #0f3f73;
            --blue-700: #1f5e9a;
            --ink: #11395f;
            --muted: #5c7289;
            --line: #d6e2ee;
            --card: #f9fbfe;
            --field: #f4f8fc;
            --green: #95c11f;
            --green-dark: #7ea61a;
        }
        * { box-sizing:border-box; }
        body {
            margin:0;
            font-family:"Segoe UI",Roboto,sans-serif;
            background:
                radial-gradient(1000px 560px at 14% 8%, rgba(49, 120, 185, 0.34), transparent 58%),
                radial-gradient(860px 480px at 86% 92%, rgba(24, 84, 140, 0.38), transparent 56%),
                linear-gradient(140deg, var(--blue-900), var(--blue-700));
            color:#10273d;
            min-height:100vh;
        }
        .container { max-width: 1080px; margin:0 auto; padding:24px 16px 40px; }
        .card { background:#fff; border:1px solid #d4e1ed; border-radius:14px; padding:16px; margin-bottom:14px; box-shadow:0 10px 28px rgba(11,44,75,0.2); }
        h1 { margin:0 0 8px; }
        .muted { color:#5f7285; }
        .grid { display:grid; grid-template-columns:1fr 1fr; gap:10px; }
        label { display:block; font-weight:600; margin-bottom:6px; }
        input, select, textarea { width:100%; border:1px solid #c7d8e8; border-radius:12px; padding:12px; font:inherit; background:var(--field); }
        .field { margin-bottom:12px; }
        .btn { border:0; border-radius:10px; padding:10px 14px; font-weight:600; cursor:pointer; text-decoration:none; display:inline-block; }
        .btn-primary { background:linear-gradient(180deg, #9ecc21, #7ea61a); color:#fff; }
        .btn-ghost { background:#e9f0f7; color:#174266; }
        .row { display:flex; gap:10px; flex-wrap:wrap; }
        .error { color:#9f1d1d; font-size:0.9rem; margin-top:4px; }
        .flash { margin-bottom:10px; background:#e8f7f0; color:#14563c; padding:12px; border-radius:10px; border:1px solid #bce8d4; }
        .posts { display:grid; gap:10px; }
        .post-title { margin:0; font-size:1.15rem; }
        .meta { color:#5f7285; margin-top:4px; font-size:0.95rem; }
        .badge { display:inline-block; margin-top:8px; padding:4px 9px; border-radius:999px; background:#e8f7f0; color:#0f6d4b; font-size:0.8rem; }
        .pager { display:flex; justify-content:space-between; gap:12px; flex-wrap:wrap; }
        .pager a, .pager span { text-decoration:none; font-size:0.92rem; color:#21415e; padding:8px 10px; border-radius:8px; border:1px solid #c7d6e5; background:#f8fbfe; }
        .pager span.disabled { color:#90a0b1; border-color:#dde6ef; background:#f4f7fb; }
        .form-shell { background:linear-gradient(180deg, rgba(255,255,255,0.97), rgba(247,250,254,0.96)); border:1px solid rgba(202,218,234,.9); border-radius:28px; padding:22px; }
        .steps { display:grid; grid-template-columns:repeat(3,1fr); gap:12px; margin-bottom:16px; position:relative; }
        .steps::before { content:""; position:absolute; left:8%; right:8%; top:20px; height:3px; background:#d2dfeb; border-radius:999px; z-index:0; }
        .step { position:relative; z-index:1; text-align:center; color:#668097; font-weight:600; }
        .step-dot { width:42px; height:42px; margin:0 auto 8px; border-radius:50%; border:2px solid #bed0e3; background:#ecf3fa; display:grid; place-items:center; font-size:1.35rem; font-weight:700; }
        .step.active { color:#4a8f19; }
        .step.active .step-dot { border-color:var(--green); background:var(--green); color:#fff; box-shadow:0 8px 16px rgba(126,166,26,.34); }
        .form-card { background:var(--card); border:1px solid var(--line); border-radius:20px; padding:22px 24px; }
        @media (max-width: 820px) { .grid { grid-template-columns:1fr; } }
    </style>
</head>
<body>
@include('partials.site-header')
<div class="container">
    <article class="card">
        <h1>Gymbuddy zoeken</h1>
        <p class="muted">Bekijk alle actieve gymbuddy-berichten en reageer direct. Zelf ook een oproep plaatsen? Dat kan onderaan deze pagina.</p>
        <div class="row" style="margin-top:10px;">
            <a class="btn btn-ghost" href="{{ route('home') }}">Terug naar home</a>
            <a class="btn btn-primary" href="#plaats-bericht">Zelf een bericht plaatsen</a>
        </div>
    </article>

    @if(session('status'))
        <div class="flash">{{ session('status') }}</div>
    @endif

    <article class="card">
        <h2 style="margin-top:0;">Actieve gymbuddy oproepen</h2>
        <div class="posts">
            @forelse($posts as $post)
                <div class="card" style="margin-bottom:0;">
                    <p class="post-title">{{ $post->name }} zoekt een gymbuddy voor {{ $post->sport }}</p>
                    <p class="meta">Locatie: {{ $post->address ? $post->address . ', ' : '' }}{{ $post->postcode ? $post->postcode . ' ' : '' }}{{ $post->city }}</p>
                    <p class="meta">Trainingsfrequentie: {{ $post->days_per_week }} · Voorkeur: {{ str_replace('_', ' ', $post->gender_preference ?? 'geen voorkeur') }}</p>
                    <p class="meta"><strong>Over mij:</strong> {{ $post->about_you }}</p>
                    <p class="meta"><strong>Zoekopdracht:</strong> {{ $post->search_message }}</p>
                    <span class="badge">Geplaatst op {{ $post->created_at->format('d-m-Y') }}</span>
                    <div style="margin-top:10px;">
                        <a class="btn btn-primary" href="mailto:{{ $post->email }}?subject={{ rawurlencode('Reactie op jouw gymbuddy oproep') }}">Reageer op dit bericht</a>
                    </div>
                </div>
            @empty
                <p class="muted">Nog geen oproepen geplaatst.</p>
            @endforelse
        </div>

        @if($posts->lastPage() > 1)
            <div class="pager" style="margin-top:12px;">
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

    <article class="form-shell" id="plaats-bericht">
        <h2 style="margin-top:0;">Plaats jouw oproep</h2>
        <div class="steps" aria-hidden="true">
            <div class="step active">
                <div class="step-dot">1</div>
                Persoonlijk
            </div>
            <div class="step">
                <div class="step-dot">2</div>
                Sport
            </div>
            <div class="step">
                <div class="step-dot">3</div>
                Details
            </div>
        </div>
        <div class="form-card">
            <form method="POST" action="{{ route('gymbuddy.store') }}">
                @csrf

                <div class="grid">
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

                <div class="grid">
                    <div class="field">
                        <label for="sport">Soort sport</label>
                        <input id="sport" name="sport" type="text" value="{{ old('sport') }}" placeholder="Bijv. fitness, boksen, yoga of padel" required>
                        @error('sport')<div class="error">{{ $message }}</div>@enderror
                    </div>
                    <div class="field">
                        <label for="days_per_week">Aantal dagen per week</label>
                        <input id="days_per_week" name="days_per_week" type="text" value="{{ old('days_per_week') }}" placeholder="Bijv. 2-3 dagen" required>
                        @error('days_per_week')<div class="error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="grid">
                    <div class="field">
                        <label for="address">Adres (optioneel)</label>
                        <input id="address" name="address" type="text" value="{{ old('address') }}">
                        @error('address')<div class="error">{{ $message }}</div>@enderror
                    </div>
                    <div class="field">
                        <label for="postcode">Postcode (optioneel)</label>
                        <input id="postcode" name="postcode" type="text" value="{{ old('postcode') }}">
                        @error('postcode')<div class="error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="grid">
                    <div class="field">
                        <label for="city">Plaats</label>
                        <input id="city" name="city" type="text" value="{{ old('city') }}" required>
                        @error('city')<div class="error">{{ $message }}</div>@enderror
                    </div>
                    <div class="field">
                        <label for="gender_preference">Voorkeur geslacht</label>
                        <select id="gender_preference" name="gender_preference">
                            <option value="geen_voorkeur" @selected(old('gender_preference') === 'geen_voorkeur')>Geen voorkeur</option>
                            <option value="vrouw" @selected(old('gender_preference') === 'vrouw')>Vrouw</option>
                            <option value="man" @selected(old('gender_preference') === 'man')>Man</option>
                            <option value="maakt_niet_uit" @selected(old('gender_preference') === 'maakt_niet_uit')>Maakt niet uit</option>
                        </select>
                        @error('gender_preference')<div class="error">{{ $message }}</div>@enderror
                    </div>
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
        </div>
    </article>
</div>
</body>
</html>
