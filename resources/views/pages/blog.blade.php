<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Blogs - GymMaps.nl</title>
    <meta name="description" content="Lees blogs van GymMaps.nl over sportscholen, gymbuddy's en personal trainers in Nederland.">
    <link rel="icon" type="image/png" href="{{ asset('logo/gymmaps-logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('logo/gymmaps-logo.png') }}">
    <style>
        :root { --bg:#f4f8fc; --card:#fff; --ink:#10263d; --muted:#4f6276; --line:#d6e2ee; --green:#95c11f; --green-dark:#7ea61a; --blue:#0f4f7c; }
        * { box-sizing:border-box; }
        body { margin:0; font-family:"Segoe UI",Roboto,sans-serif; color:var(--ink); background:var(--bg); }
        .container { max-width:1120px; margin:0 auto; padding:28px 16px 44px; }
        .hero { background:linear-gradient(130deg,#0f3f73,#1f5e9a); border-radius:18px; color:#fff; padding:24px 22px; margin-bottom:16px; }
        .hero h1 { margin:0 0 8px; font-size:clamp(1.7rem,3.1vw,2.3rem); }
        .hero p { margin:0; color:rgba(255,255,255,.92); line-height:1.55; }
        .showcase { display:grid; grid-template-columns:minmax(0,2fr) minmax(280px,1fr); gap:14px; margin-bottom:16px; }
        .featured { position:relative; min-height:420px; border-radius:16px; overflow:hidden; text-decoration:none; display:block; border:1px solid #cfdeeb; }
        .featured img { width:100%; height:100%; object-fit:cover; display:block; }
        .featured::after { content:""; position:absolute; inset:0; background:linear-gradient(180deg,rgba(8,25,39,.2),rgba(8,28,46,.72)); }
        .featured .content { position:absolute; left:20px; right:20px; bottom:20px; z-index:2; color:#fff; }
        .pill { display:inline-block; font-size:.85rem; margin-bottom:8px; background:rgba(149,193,31,.9); border-radius:999px; padding:4px 10px; }
        .featured h2 { margin:0; color:#fff; font-size:clamp(1.6rem,3vw,2.8rem); line-height:1.14; }
        .sidebar { background:linear-gradient(160deg,#0f5e88,#0f4f7c); border-radius:16px; padding:14px; color:#fff; border:1px solid #2b6c92; }
        .sidebar h3 { margin:0 0 10px; color:#fff; }
        .recent { display:grid; grid-template-columns:86px 1fr; gap:10px; color:#fff; text-decoration:none; border-top:1px solid rgba(255,255,255,.2); padding-top:10px; margin-top:10px; }
        .recent:first-of-type { border-top:0; margin-top:0; padding-top:0; }
        .recent img { width:86px; height:66px; object-fit:cover; border-radius:8px; }
        .recent strong { display:block; line-height:1.22; margin-bottom:4px; }
        .recent span { font-size:.84rem; color:rgba(255,255,255,.9); }
        .grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:12px; }
        .card { display:grid; grid-template-columns:150px 1fr; gap:12px; align-items:center; background:var(--card); border:1px solid var(--line); border-radius:12px; padding:10px; text-decoration:none; color:#13375a; }
        .card img { width:100%; height:96px; border-radius:8px; object-fit:cover; background:#edf3fa; }
        .card strong { display:block; margin-bottom:6px; line-height:1.25; }
        .card p { margin:0; font-size:.9rem; color:#58708a; line-height:1.35; }
        .btn { display:inline-block; margin-top:16px; border-radius:10px; text-decoration:none; padding:10px 14px; font-weight:600; background:var(--green); color:#fff; }
        .btn:hover { background:var(--green-dark); }
        @media (max-width:980px){ .showcase{ grid-template-columns:1fr;} }
        @media (max-width:640px){ .grid{grid-template-columns:1fr;} .card{grid-template-columns:1fr;} .featured{min-height:320px;} }
    </style>
</head>
<body>
@include('partials.site-header')
<div class="container">
    <section class="hero">
        <h1>Blogs - GymMaps.nl</h1>
        <p>Klik op een blog om het volledige artikel op een aparte pagina te lezen.</p>
    </section>

    <section class="showcase">
        <a class="featured" href="{{ route('pages.blog.best-sportscholen') }}">
            <img src="{{ asset('blog-images/blog-1.jpg') }}" alt="De beste sportscholen in Nederland" onerror="this.onerror=null;this.src='{{ asset('hero/hero-stock.jpg') }}';">
            <div class="content">
                <span class="pill">Uitgelicht</span>
                <h2>De beste sportscholen in Nederland</h2>
            </div>
        </a>
        <aside class="sidebar">
            <h3>Recente blogs</h3>
            <a class="recent" href="{{ route('pages.blog.best-sportscholen') }}">
                <img src="{{ asset('blog-images/blog-1.jpg') }}" alt="" onerror="this.onerror=null;this.src='{{ asset('hero/hero-stock.jpg') }}';">
                <div><strong>De beste sportscholen in Nederland</strong><span>maart 2026</span></div>
            </a>
            <a class="recent" href="{{ route('pages.blog.sportschool-in-de-buurt') }}">
                <img src="{{ asset('blog-images/blog-2.jpg') }}" alt="" onerror="this.onerror=null;this.src='{{ asset('hero/hero-stock.jpg') }}';">
                <div><strong>Hoe vind je een sportschool bij jou in de buurt?</strong><span>maart 2026</span></div>
            </a>
            <a class="recent" href="{{ route('pages.blog.gymbuddy-effectief') }}">
                <img src="{{ asset('blog-images/blog-3.jpg') }}" alt="" onerror="this.onerror=null;this.src='{{ asset('hero/hero-stock.jpg') }}';">
                <div><strong>Waarom sporten met een gymbuddy zo effectief is</strong><span>maart 2026</span></div>
            </a>
            <a class="recent" href="{{ route('pages.blog.goede-personal-trainer') }}">
                <img src="{{ asset('blog-images/blog-4.jpg') }}" alt="" onerror="this.onerror=null;this.src='{{ asset('hero/hero-stock.jpg') }}';">
                <div><strong>Hoe kies je een goede personal trainer?</strong><span>maart 2026</span></div>
            </a>
        </aside>
    </section>

    <section class="grid">
        <a class="card" href="{{ route('pages.blog.best-sportscholen') }}">
            <img src="{{ asset('blog-images/blog-1.jpg') }}" alt="" onerror="this.onerror=null;this.src='{{ asset('hero/hero-stock.jpg') }}';">
            <div><strong>De beste sportscholen in Nederland</strong><p>Waar let je op en hoe vind je de juiste sportschool in jouw buurt?</p></div>
        </a>
        <a class="card" href="{{ route('pages.blog.sportschool-in-de-buurt') }}">
            <img src="{{ asset('blog-images/blog-2.jpg') }}" alt="" onerror="this.onerror=null;this.src='{{ asset('hero/hero-stock.jpg') }}';">
            <div><strong>Hoe vind je een sportschool bij jou in de buurt?</strong><p>Praktische stappen om slim te vergelijken en de beste keuze te maken.</p></div>
        </a>
        <a class="card" href="{{ route('pages.blog.gymbuddy-effectief') }}">
            <img src="{{ asset('blog-images/blog-3.jpg') }}" alt="" onerror="this.onerror=null;this.src='{{ asset('hero/hero-stock.jpg') }}';">
            <div><strong>Waarom sporten met een gymbuddy zo effectief is</strong><p>Meer motivatie en meer plezier tijdens het trainen.</p></div>
        </a>
        <a class="card" href="{{ route('pages.blog.goede-personal-trainer') }}">
            <img src="{{ asset('blog-images/blog-4.jpg') }}" alt="" onerror="this.onerror=null;this.src='{{ asset('hero/hero-stock.jpg') }}';">
            <div><strong>Hoe kies je een goede personal trainer?</strong><p>De belangrijkste punten om de juiste trainer te kiezen.</p></div>
        </a>
    </section>

    <a class="btn" href="{{ route('home') }}">Terug naar homepage</a>
</div>
</body>
</html>
