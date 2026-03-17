<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.google-site-verification')
    <title>Tarieven - GymMaps.nl</title>
    <meta name="description" content="Bekijk de tarieven en mogelijkheden voor zichtbaarheid op GymMaps.nl.">
    @include('partials.favicon')
    <style>
        :root { --bg:#f2f7fc; --card:#fff; --line:#d8e3ee; --ink:#143451; --muted:#5e7488; }
        *{ box-sizing:border-box; }
        body{ margin:0; font-family:"Segoe UI",Roboto,sans-serif; background:var(--bg); color:var(--ink); }
        .container{ max-width:980px; margin:0 auto; padding:28px 16px 44px; }
        .card{ background:var(--card); border:1px solid var(--line); border-radius:14px; padding:18px; }
        h1{ margin:0 0 8px; font-size:2rem; }
        h2{ margin:18px 0 8px; font-size:1.2rem; }
        p,li{ color:var(--muted); line-height:1.55; }
        ul{ margin:8px 0 0 18px; padding:0; }
    </style>
</head>
<body>
@include('partials.site-header')
<main class="container">
    <section class="card">
        <h1>Tarieven</h1>
        <p>Hier komt het tarievenoverzicht voor sportlocaties en personal trainers op GymMaps.nl.</p>

        <h2>Voor sportlocaties</h2>
        <ul>
            <li>Basisvermelding</li>
            <li>Uitgelichte vermelding</li>
            <li>Extra zichtbaarheid op homepage</li>
        </ul>

        <h2>Voor personal trainers</h2>
        <ul>
            <li>Profielvermelding</li>
            <li>Leadpakket</li>
            <li>Promotieopties</li>
        </ul>
    </section>
</main>
@include('partials.site-footer')
</body>
</html>
