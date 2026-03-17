<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.google-site-verification')
    <title>Personal trainer zoeken - GymMaps.nl</title>
    @include('partials.favicon')
    @include('partials.brand-theme')
    <meta name="description" content="Plaats eenvoudig een oproep voor een personal trainer in jouw regio via GymMaps.nl.">
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
            background: transparent;
            color: var(--gm-brand-text);
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

        .top-bar {
            display: grid;
            grid-template-columns: 1fr auto;
            align-items: center;
            gap: 12px;
            margin-bottom: 12px;
        }

        .intro-bar {
            background: linear-gradient(135deg, #eaf3fb, #dfeef9);
            border: 1px solid #cfe0ee;
            color: #123f6a;
            border-radius: 12px;
            padding: 12px 14px;
            margin-bottom: 12px;
            font-weight: 700;
            font-size: 1rem;
            margin-bottom: 0;
        }

        .flash {
            margin-bottom: 12px;
            background: #e8f3fb;
            color: #124265;
            padding: 12px;
            border-radius: 10px;
            border: 1px solid #c5dbeb;
        }

        .flash.error {
            background: #fff4f4;
            color: #8b1f1f;
            border-color: #f1c6c6;
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

        .post-avatar {
            width: 78px;
            height: 78px;
            border-radius: 12px;
            border: 1px solid #ccdae8;
            background: linear-gradient(135deg, #dceaf6, #b8d3e8);
            color: #10486f;
            font-size: 1.8rem;
            font-weight: 700;
            display: flex;
            align-items: center;
            justify-content: center;
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
            .top-bar { grid-template-columns: 1fr; }
            .layout { grid-template-columns: 1fr; }
            .form-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
@include('partials.site-header')

<div class="container">
    <div class="top-bar">
        <div class="intro-bar">Op zoek naar een personal trainer? Plaats hier je oproep.</div>
        <a class="btn btn-primary" href="#plaats-oproep">Oproep plaatsen</a>
    </div>

    @if(session('status'))
        <div class="flash">{{ session('status') }}</div>
    @endif
    @if($errors->has('db'))
        <div class="flash error">{{ $errors->first('db') }}</div>
    @endif

    <section class="layout" id="actieve-oproepen">
        <aside class="card filter-card">
            <h2>Filter oproepen</h2>
            <form method="GET" action="{{ route('pages.personal-trainer') }}">
                <div class="field">
                    <label for="q">Plaats / regio</label>
                    <input id="q" type="text" name="q" value="{{ $query }}" placeholder="Bijv. Utrecht of Rotterdam">
                </div>

                <div class="field">
                    <label for="sport">Sport / doel</label>
                    <input id="sport" type="text" name="sport" value="{{ $sport }}" placeholder="Bijv. afvallen of krachttraining">
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
                    <a class="btn btn-secondary" href="{{ route('pages.personal-trainer') }}">Reset</a>
                </div>

                @if($query !== '' && !$hasSearchCenter)
                    <p class="hint">Geen exact zoekcentrum gevonden voor deze invoer. Resultaten zijn gefilterd op tekstmatch.</p>
                @elseif($query !== '' && $hasSearchCenter)
                    <p class="hint">Zoekcentrum: <strong>{{ $searchCenterLabel }}</strong> · Radius: <strong>{{ $radius }} km</strong></p>
                @endif
            </form>
        </aside>

        <article class="card results-card">
            <h2>Actieve personal trainer oproepen</h2>

            <div class="posts-grid">
                @forelse($requests as $requestItem)
                    <article class="post-card">
                        <div class="post-avatar">{{ \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($requestItem->name, 0, 1)) }}</div>
                        <div>
                            <p class="post-title">{{ $requestItem->name }} zoekt een personal trainer</p>
                            <p class="meta">{{ $requestItem->city }}{{ $requestItem->distance_km !== null ? ' · '.number_format($requestItem->distance_km, 1, ',', '.').' km' : '' }}</p>
                            <p class="meta">{{ \Illuminate\Support\Str::limit($requestItem->goal ?: ($requestItem->message ?: 'Geen extra toelichting opgegeven.'), 130) }}</p>

                            <div class="chip-wrap">
                                <span class="chip">{{ $requestItem->sport_focus }}</span>
                                <span class="chip">{{ $requestItem->days_per_week }}</span>
                                <span class="chip">{{ $requestItem->training_location }}</span>
                                @if($requestItem->max_rate !== null)
                                    <span class="chip">Max € {{ number_format((float) $requestItem->max_rate, 0, ',', '.') }}</span>
                                @endif
                            </div>

                            <div class="post-footer">
                                <span class="meta">Geplaatst op {{ $requestItem->created_at->format('d-m-Y') }}</span>
                                <div style="display:flex; gap:6px; flex-wrap:wrap;">
                                    @if($requestItem->email)
                                        <a class="btn btn-primary" href="mailto:{{ $requestItem->email }}?subject={{ rawurlencode('Reactie op jouw personal trainer oproep') }}">Mail</a>
                                    @endif
                                    @if($requestItem->phone)
                                        <a class="btn btn-secondary" href="tel:{{ preg_replace('/\s+/', '', $requestItem->phone) }}">Bel</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </article>
                @empty
                    <p class="meta">Nog geen oproepen gevonden met deze filters.</p>
                @endforelse
            </div>

            @if($requests->lastPage() > 1)
                <div class="pager">
                    @if($requests->onFirstPage())
                        <span class="disabled">Vorige</span>
                    @else
                        <a href="{{ $requests->previousPageUrl() }}">Vorige</a>
                    @endif

                    <span>Pagina {{ $requests->currentPage() }} / {{ $requests->lastPage() }}</span>

                    @if($requests->hasMorePages())
                        <a href="{{ $requests->nextPageUrl() }}">Volgende</a>
                    @else
                        <span class="disabled">Volgende</span>
                    @endif
                </div>
            @endif
        </article>
    </section>

    <section class="form-shell" id="plaats-oproep">
        <h2>Plaats jouw personal trainer oproep</h2>
        <form method="POST" action="{{ route('pages.personal-trainer.store') }}">
            @csrf

            <div class="form-grid">
                <div class="field">
                    <label for="name">Je naam</label>
                    <input id="name" name="name" type="text" value="{{ old('name') }}" required>
                    @error('name')<div class="error">{{ $message }}</div>@enderror
                </div>
                <div class="field">
                    <label for="city">Plaats / regio</label>
                    <input id="city" name="city" type="text" value="{{ old('city') }}" placeholder="Bijv. Utrecht" required>
                    @error('city')<div class="error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-grid">
                <div class="field">
                    <label for="email">E-mailadres</label>
                    <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="naam@email.nl">
                    @error('email')<div class="error">{{ $message }}</div>@enderror
                </div>
                <div class="field">
                    <label for="phone">Telefoonnummer</label>
                    <input id="phone" name="phone" type="text" value="{{ old('phone') }}" placeholder="06...">
                    @error('phone')<div class="error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="field">
                <p class="hint" style="margin-top:0;">Vul minimaal een e-mailadres of telefoonnummer in, zodat trainers contact kunnen opnemen.</p>
            </div>

            <div class="form-grid">
                <div class="field">
                    <label for="training_location">Waar wil je personal training?</label>
                    <input id="training_location" name="training_location" type="text" value="{{ old('training_location') }}" placeholder="Bijv. sportschool, buiten of thuis" required>
                    @error('training_location')<div class="error">{{ $message }}</div>@enderror
                </div>
                <div class="field">
                    <label for="days_per_week">Voor hoeveel dagen per week?</label>
                    <input id="days_per_week" name="days_per_week" type="text" value="{{ old('days_per_week') }}" placeholder="Bijv. 2 keer per week" required>
                    @error('days_per_week')<div class="error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-grid">
                <div class="field">
                    <label for="sport_focus">Welk type sport / doel zoek je?</label>
                    <input id="sport_focus" name="sport_focus" type="text" value="{{ old('sport_focus') }}" placeholder="Bijv. afvallen, krachttraining of conditie" required>
                    @error('sport_focus')<div class="error">{{ $message }}</div>@enderror
                </div>
                <div class="field">
                    <label for="max_rate">Maximaal tarief per sessie (EUR)</label>
                    <input id="max_rate" name="max_rate" type="number" step="0.01" min="0" value="{{ old('max_rate') }}" placeholder="Bijv. 45.00">
                    @error('max_rate')<div class="error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="field">
                <label for="goal">Wat is je trainingsdoel? (optioneel)</label>
                <textarea id="goal" name="goal" rows="3" placeholder="Bijv. 8 kg afvallen in 4 maanden">{{ old('goal') }}</textarea>
                @error('goal')<div class="error">{{ $message }}</div>@enderror
            </div>

            <div class="field">
                <label for="message">Extra informatie (optioneel)</label>
                <textarea id="message" name="message" rows="4" placeholder="Bijv. voorkeur voor ochtendtraining of ervaring met blessures">{{ old('message') }}</textarea>
                @error('message')<div class="error">{{ $message }}</div>@enderror
            </div>

            <button class="btn btn-primary" type="submit">Plaats oproep</button>
        </form>
    </section>
</div>
@include('partials.site-footer')
</body>
</html>
