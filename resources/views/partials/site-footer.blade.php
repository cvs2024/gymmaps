<style>
    .gm-site-footer {
        margin-top: 34px;
        background:
            radial-gradient(780px 240px at 20% -20%, rgba(255, 154, 106, 0.16), transparent 62%),
            linear-gradient(135deg, #103f48 0%, #0e5b57 56%, #0d4f5d 100%);
        color: #f7ebe3;
        border-top: 1px solid rgba(255, 255, 255, 0.08);
    }

    .gm-site-footer-inner {
        max-width: 1400px;
        margin: 0 auto;
        padding: 24px 20px 18px;
        display: grid;
        grid-template-columns: minmax(170px, 1.2fr) 1fr 1fr;
        gap: 18px;
        align-items: start;
    }

    .gm-footer-brand {
        font-size: 2rem;
        line-height: 1;
        letter-spacing: 0.01em;
        font-weight: 700;
        color: #ffd8c3;
        margin: 0 0 8px;
        text-decoration: none;
        display: inline-block;
    }

    .gm-footer-muted {
        margin: 0;
        color: rgba(247, 235, 227, 0.84);
        line-height: 1.45;
        font-size: 0.94rem;
    }

    .gm-footer-title {
        margin: 0 0 8px;
        font-size: 0.95rem;
        font-weight: 700;
        color: #ffe5d4;
    }

    .gm-footer-links {
        display: grid;
        gap: 7px;
    }

    .gm-site-footer a {
        color: #ffe5d4;
        text-decoration: none;
        opacity: 0.98;
    }

    .gm-site-footer a:hover {
        opacity: 1;
        text-decoration: underline;
    }

    .gm-footer-bottom {
        max-width: 1400px;
        margin: 0 auto;
        border-top: 1px solid rgba(255, 255, 255, 0.12);
        padding: 10px 20px 18px;
        color: rgba(247, 235, 227, 0.76);
        font-size: 0.88rem;
    }

    @media (max-width: 860px) {
        .gm-site-footer-inner {
            grid-template-columns: 1fr;
            gap: 14px;
        }

        .gm-footer-brand {
            font-size: 1.72rem;
        }
    }
</style>

<footer class="gm-site-footer">
    <div class="gm-site-footer-inner">
        <div>
            <a class="gm-footer-brand" href="{{ route('home') }}">GymMaps</a>
            <p class="gm-footer-muted">Vind sportscholen, sportactiviteiten en trainers in jouw regio.</p>
        </div>

        <div>
            <p class="gm-footer-title">Navigatie</p>
            <nav class="gm-footer-links" aria-label="Footer links">
                <a href="{{ route('home') }}">Home</a>
                <a href="{{ route('pages.blog') }}">Blogs</a>
                <a href="{{ route('pages.faq') }}">Veelgestelde vragen</a>
                <a href="{{ route('pages.contact') }}">Contact</a>
            </nav>
        </div>

        <div>
            <p class="gm-footer-title">Voor ondernemers</p>
            <div class="gm-footer-links">
                <a href="{{ route('listing-requests.create') }}">Sportschool aanmelden</a>
                <a href="{{ route('pages.pricing') }}">Tarieven</a>
                <a href="{{ route('pages.personal-trainer') }}">Personal trainer</a>
            </div>
        </div>
    </div>
    <div class="gm-footer-bottom">
        &copy; {{ date('Y') }} GymMaps.nl - Alle rechten voorbehouden.
    </div>
</footer>

