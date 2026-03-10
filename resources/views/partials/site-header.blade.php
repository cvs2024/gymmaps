<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap');

    .gm-site-nav { width: 100%; background: #ffffff; border-bottom: 1px solid #d9e4ee; }

    .gm-site-nav-inner {
        max-width: 1400px;
        margin: 0 auto;
        padding: 16px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 18px;
    }

    .gm-nav-logo {
        display: inline-flex;
        align-items: center;
        text-decoration: none;
        width: 320px;
        height: 56px;
        overflow: hidden;
    }

    .gm-nav-logo img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        object-position: left center;
        display: block;
        transform: scale(2);
        transform-origin: left center;
    }

    .gm-nav-menu {
        display: flex;
        justify-content: flex-start;
        gap: 18px;
        flex-wrap: wrap;
        font-family: "Poppins", "Segoe UI", sans-serif;
        font-weight: 400;
        font-size: 0.92rem;
        letter-spacing: 0.015em;
        text-transform: uppercase;
    }

    .gm-nav-menu a {
        color: #29466a;
        text-decoration: none;
        line-height: 1.2;
        padding: 0;
        background: transparent;
        border: 0;
        position: relative;
    }

    .gm-nav-menu a:hover {
        color: #0f5e88;
    }

    .gm-nav-menu a::after {
        content: "";
        position: absolute;
        left: 0;
        bottom: -6px;
        width: 0;
        height: 2px;
        background: #0f5e88;
        transition: width 0.18s ease;
    }

    .gm-nav-menu a:hover::after { width: 100%; }

    @media (max-width: 760px) {
        .gm-site-nav-inner { flex-direction: column; align-items: flex-start; gap: 12px; }
        .gm-nav-logo { width: 250px; height: 46px; }
        .gm-nav-logo img { transform: scale(1.84); }
        .gm-nav-menu { gap: 12px; font-size: 0.86rem; }
    }
</style>

<nav class="gm-site-nav">
    <div class="gm-site-nav-inner">
        <a class="gm-nav-logo" href="{{ route('home') }}">
            <img src="{{ asset('logo/gymmaps-wordmark.png') }}" alt="GymMaps.nl logo">
        </a>

        <div class="gm-nav-menu">
            <a href="{{ route('listing-requests.create') }}">Sportschool vermelden?</a>
            <a href="{{ route('gymbuddy.index') }}">Gymbuddy gezocht</a>
            <a href="{{ route('pages.personal-trainer') }}">Personal trainer</a>
            <a href="{{ route('pages.blog') }}">Blog</a>
            <a href="{{ route('pages.faq') }}">FAQ</a>
            <a href="{{ route('pages.contact') }}">Contact</a>
            <a href="{{ route('login') }}">Inloggen</a>
        </div>
    </div>
</nav>
