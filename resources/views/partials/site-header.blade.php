<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap');

    .gm-site-nav {
        width: 100%;
        background: #ffffff;
        border-bottom: 1px solid #d9e4ee;
    }

    .gm-site-nav-inner {
        max-width: 1400px;
        margin: 0 auto;
        padding: 12px 20px;
        display: grid;
        grid-template-columns: auto 1fr auto;
        gap: 14px;
        align-items: center;
    }

    .gm-nav-logo {
        display: inline-flex;
        align-items: center;
        text-decoration: none;
        width: 210px;
        height: 64px;
        overflow: hidden;
        margin-left: -8px;
    }

    .gm-nav-logo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: left center;
        display: block;
    }

    .gm-nav-menu {
        display: flex;
        justify-content: flex-end;
        gap: 10px;
        flex-wrap: wrap;
        font-family: "Poppins", "Segoe UI", sans-serif;
        font-weight: 500;
        font-size: 14px;
    }

    .gm-nav-menu a {
        color: #fff;
        text-decoration: none;
        border-radius: 10px;
        padding: 9px 13px;
        background: #95c11f;
        border: 1px solid #95c11f;
        line-height: 1;
    }

    .gm-nav-menu a:hover {
        background: #7ea61a;
        border-color: #7ea61a;
    }

    @media (max-width: 760px) {
        .gm-site-nav-inner {
            grid-template-columns: 1fr;
            gap: 12px;
        }

        .gm-nav-menu {
            justify-content: flex-start;
        }
    }
</style>

<nav class="gm-site-nav">
    <div class="gm-site-nav-inner">
        <a class="gm-nav-logo" href="{{ route('home') }}">
            <img src="{{ asset('logo/gymmaps-logo.png') }}" alt="GymMaps.nl logo">
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
