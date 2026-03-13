<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500&display=swap');

    :root {
        --gm-brand-accent: #FF5C39;
        --gm-brand-accent-dark: #E24C2B;
    }

    .gm-site-nav { width: 100%; background: #ffffff; border-bottom: 1px solid #d9e4ee; }

    .gm-site-nav-inner {
        max-width: 1400px;
        margin: 0 auto;
        padding: 8px 18px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
    }

    .gm-nav-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        width: 100%;
    }

    .gm-nav-logo {
        display: inline-flex;
        align-items: center;
        text-decoration: none;
        width: clamp(320px, 34vw, 520px);
        min-width: 320px;
        height: 72px;
        overflow: visible;
    }

    .gm-nav-logo img {
        width: auto;
        max-width: 100%;
        height: auto;
        max-height: 72px;
        object-fit: contain;
        object-position: left center;
        display: block;
        transform: none;
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

    .gm-nav-toggle {
        display: none;
        align-items: center;
        justify-content: center;
        width: 42px;
        height: 42px;
        border-radius: 10px;
        border: 1px solid #c9daea;
        background: #ffffff;
        color: #23486b;
        cursor: pointer;
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

    .btn,
    .btn-primary,
    .btn-secondary,
    .btn-light,
    .btn-ghost {
        background: var(--gm-brand-accent) !important;
        border-color: var(--gm-brand-accent) !important;
        color: #fff !important;
    }

    .btn:hover,
    .btn-primary:hover,
    .btn-secondary:hover,
    .btn-light:hover,
    .btn-ghost:hover {
        background: var(--gm-brand-accent-dark) !important;
        border-color: var(--gm-brand-accent-dark) !important;
        color: #fff !important;
    }

    @media (max-width: 1024px) {
        .gm-site-nav-inner {
            flex-direction: column;
            align-items: stretch;
            gap: 8px;
            padding: 8px 14px;
        }

        .gm-nav-toggle { display: inline-flex; }

        .gm-nav-logo {
            width: 280px;
            min-width: 0;
            height: 52px;
        }

        .gm-nav-logo img { max-height: 52px; }

        .gm-nav-menu {
            display: none;
            width: 100%;
            flex-direction: column;
            gap: 0;
            border-top: 1px solid #e1ebf4;
            padding-top: 6px;
            font-size: 0.86rem;
        }

        .gm-site-nav.is-open .gm-nav-menu {
            display: flex;
        }

        .gm-nav-menu a {
            padding: 10px 2px;
        }
    }
</style>

@php
    $headerLogoPath = config('branding.header_logo_path', 'logo/gymmaps_logo_treatwell_style.png');
    $isExternalLogo = str_starts_with($headerLogoPath, 'http://') || str_starts_with($headerLogoPath, 'https://');
    $normalizedPath = ltrim($headerLogoPath, '/');
    $localLogoExists = !$isExternalLogo && is_file(public_path($normalizedPath));
    $effectiveLogoPath = $localLogoExists ? $normalizedPath : 'logo/gymmaps_logo_treatwell_style.png';
    $headerLogoSrc = $isExternalLogo
        ? $headerLogoPath
        : asset($effectiveLogoPath).'?v='.(is_file(public_path($effectiveLogoPath)) ? filemtime(public_path($effectiveLogoPath)) : time());
@endphp

<nav class="gm-site-nav" id="gmSiteNav">
    <div class="gm-site-nav-inner">
        <div class="gm-nav-top">
            <a class="gm-nav-logo" href="{{ route('home') }}">
                <img src="{{ $headerLogoSrc }}" alt="GymMaps.nl logo">
            </a>
            <button class="gm-nav-toggle" id="gmNavToggle" type="button" aria-expanded="false" aria-controls="gmNavMenu" aria-label="Menu openen of sluiten">
                <span aria-hidden="true">☰</span>
            </button>
        </div>

        <div class="gm-nav-menu" id="gmNavMenu">
            <a href="{{ route('listing-requests.create') }}">Sportschool aanmelden</a>
            <a href="{{ route('gymbuddy.index') }}">Gymbuddy gezocht</a>
            <a href="{{ route('pages.personal-trainer') }}">Personal trainer</a>
            <a href="{{ route('pages.blog') }}">Blog</a>
            <a href="{{ route('pages.faq') }}">FAQ</a>
            <a href="{{ route('pages.contact') }}">Contact</a>
            <a href="{{ route('login') }}">Inloggen</a>
        </div>
    </div>
</nav>
<script>
    (function () {
        const nav = document.getElementById('gmSiteNav');
        const toggle = document.getElementById('gmNavToggle');
        if (!nav || !toggle) return;

        toggle.addEventListener('click', function () {
            const isOpen = nav.classList.toggle('is-open');
            toggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });

        document.addEventListener('click', function (event) {
            if (!nav.contains(event.target)) {
                nav.classList.remove('is-open');
                toggle.setAttribute('aria-expanded', 'false');
            }
        });
    })();
</script>
