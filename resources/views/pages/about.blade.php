<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.google-site-verification')
    <title>Over ons - GymMaps.nl</title>
    <meta name="description" content="Lees meer over GymMaps.nl en onze missie om sporters en sportlocaties te verbinden.">
    @include('partials.favicon')
    @include('partials.brand-theme')
    <style>
        :root { --bg:#f2f7fc; --card:#fff; --line:#d8e3ee; --ink:#143451; --muted:#5e7488; }
        *{ box-sizing:border-box; }
        body{ margin:0; font-family:"Segoe UI",Roboto,sans-serif; background:transparent; color:var(--gm-brand-text); }
        .container{ max-width:980px; margin:0 auto; padding:28px 16px 44px; }
        .card{ background:var(--card); border:1px solid var(--line); border-radius:14px; padding:18px; }
        h1{ margin:0 0 8px; font-size:2rem; }
        h2{ margin:18px 0 8px; font-size:1.2rem; }
        p,li{ color:var(--muted); line-height:1.55; }
    </style>
</head>
<body>
@include('partials.site-header')
<main class="container">
    <section class="card">
        <h1>Over ons</h1>
        <p>GymMaps.nl helpt sporters om eenvoudig sportscholen, personal trainers en sportactiviteiten in Nederland te vinden.</p>
        <h2>Onze missie</h2>
        <p>Een compleet en overzichtelijk platform bouwen waar iedereen snel de juiste sportlocatie in de buurt kan ontdekken.</p>
        <h2>Contact</h2>
        <p>Vragen of samenwerken? Gebruik het contactformulier op de contactpagina.</p>
    </section>
</main>
@include('partials.site-footer')
</body>
</html>
