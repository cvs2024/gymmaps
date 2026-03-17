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
        padding: 5px 18px;
        display: flex;
        justify-content: flex-start;
        align-items: center;
        gap: 22px;
    }

    .gm-nav-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        width: auto;
    }

    .gm-nav-logo {
        display: inline-flex;
        align-items: center;
        text-decoration: none;
        width: clamp(250px, 26vw, 360px);
        min-width: 250px;
        height: 56px;
        overflow: visible;
    }

    .gm-nav-logo img {
        width: auto;
        max-width: 100%;
        height: auto;
        max-height: 56px;
        object-fit: contain;
        object-position: left center;
        display: block;
        transform: none;
    }

    .gm-nav-menu {
        display: flex;
        justify-content: space-evenly;
        gap: 10px;
        flex-wrap: nowrap;
        align-items: center;
        flex: 1 1 auto;
        min-width: 0;
        font-family: "Poppins", "Segoe UI", sans-serif;
        font-weight: 400;
        font-size: 0.86rem;
        letter-spacing: 0.015em;
        text-transform: uppercase;
    }

    .gm-nav-item {
        position: relative;
        display: inline-flex;
        align-items: center;
        white-space: nowrap;
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

    .gm-nav-link,
    .gm-nav-item > a {
        color: #29466a;
        text-decoration: none;
        line-height: 1.2;
        padding: 4px 0;
        background: transparent;
        border: 0;
        position: relative;
        font: inherit;
        text-transform: inherit;
        cursor: pointer;
    }

    .gm-nav-link:hover,
    .gm-nav-item > a:hover {
        color: #0f5e88;
    }

    .gm-nav-link::after,
    .gm-nav-item > a::after {
        content: "";
        position: absolute;
        left: 0;
        bottom: -6px;
        width: 0;
        height: 2px;
        background: #0f5e88;
        transition: width 0.18s ease;
    }

    .gm-nav-link:hover::after,
    .gm-nav-item > a:hover::after { width: 100%; }

    .gm-nav-dropdown {
        position: relative;
    }

    .gm-nav-dropdown summary {
        list-style: none;
    }

    .gm-nav-dropdown summary::-webkit-details-marker {
        display: none;
    }

    .gm-nav-dropdown summary .caret {
        display: inline-block;
        margin-left: 6px;
        font-size: 0.68rem;
        transform: translateY(-1px);
    }

    .gm-nav-dropdown-panel {
        position: absolute;
        top: calc(100% + 8px);
        left: 0;
        min-width: 220px;
        background: #fff;
        border: 1px solid #d8e4ef;
        border-radius: 10px;
        box-shadow: 0 10px 24px rgba(12, 44, 72, 0.12);
        padding: 8px;
        display: none;
        z-index: 40;
    }

    .gm-nav-dropdown[open] .gm-nav-dropdown-panel {
        display: block;
    }

    .gm-nav-dropdown-panel a {
        display: block;
        text-transform: none;
        letter-spacing: 0;
        font-size: 0.9rem;
        padding: 8px 10px;
        border-radius: 8px;
        color: #29466a;
        text-decoration: none;
    }

    .gm-nav-dropdown-panel a:hover {
        background: #eef5fb;
        color: #0f5e88;
    }

    @media (min-width: 901px) {
        .gm-nav-dropdown:hover .gm-nav-dropdown-panel {
            display: block;
        }
    }

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

    @media (max-width: 900px) {
        .gm-site-nav-inner {
            flex-direction: column;
            align-items: stretch;
            gap: 8px;
            padding: 8px 14px;
        }

        .gm-nav-top { width: 100%; }

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
            flex-wrap: nowrap;
            border-top: 1px solid #e1ebf4;
            padding-top: 6px;
            font-size: 0.86rem;
        }

        .gm-site-nav.is-open .gm-nav-menu {
            display: flex;
        }

        .gm-nav-link,
        .gm-nav-item > a {
            padding: 10px 2px;
        }

        .gm-nav-item {
            width: 100%;
            display: block;
        }

        .gm-nav-dropdown-panel {
            position: static;
            min-width: 0;
            margin-top: 6px;
            border-radius: 10px;
            box-shadow: none;
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
            <div class="gm-nav-item">
                <details class="gm-nav-dropdown">
                    <summary class="gm-nav-link">Gyms <span class="caret">▾</span></summary>
                    <div class="gm-nav-dropdown-panel">
                        <a href="{{ route('listing-requests.create') }}">Sportschool aanmelden</a>
                        <a href="{{ route('pages.pricing') }}">Tarieven</a>
                    </div>
                </details>
            </div>

            <div class="gm-nav-item">
                <a href="{{ route('gymbuddy.index') }}">Gymbuddy</a>
            </div>

            <div class="gm-nav-item">
                <details class="gm-nav-dropdown">
                    <summary class="gm-nav-link">Personal Trainer <span class="caret">▾</span></summary>
                    <div class="gm-nav-dropdown-panel">
                        <a href="{{ route('pages.personal-trainer') }}#actieve-oproepen">Alle verzoeken</a>
                        <a href="{{ route('pages.pricing') }}">Tarieven</a>
                    </div>
                </details>
            </div>

            <div class="gm-nav-item">
                <a href="{{ route('pages.blog') }}">Blog</a>
            </div>

            <div class="gm-nav-item">
                <details class="gm-nav-dropdown">
                    <summary class="gm-nav-link">Contact <span class="caret">▾</span></summary>
                    <div class="gm-nav-dropdown-panel">
                        <a href="{{ route('pages.faq') }}">Veelgestelde vragen</a>
                        <a href="{{ route('pages.about') }}">Over ons</a>
                    </div>
                </details>
            </div>
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
