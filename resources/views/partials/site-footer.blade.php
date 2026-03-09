<style>
    .gm-site-footer {
        margin-top: 28px;
        background: #0f3f73;
        color: #dce8f3;
        border-top: 1px solid #1e5a92;
    }

    .gm-site-footer-inner {
        max-width: 1400px;
        margin: 0 auto;
        padding: 18px 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        flex-wrap: wrap;
        font-size: 0.92rem;
    }

    .gm-site-footer a {
        color: #ffffff;
        text-decoration: none;
        opacity: 0.95;
    }

    .gm-site-footer a:hover {
        opacity: 1;
        text-decoration: underline;
    }

    .gm-site-footer-links {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }
</style>

<footer class="gm-site-footer">
    <div class="gm-site-footer-inner">
        <span>&copy; {{ date('Y') }} GymMaps.nl - Alle rechten voorbehouden.</span>
        <nav class="gm-site-footer-links" aria-label="Footer links">
            <a href="{{ route('home') }}">Home</a>
            <a href="{{ route('pages.blog') }}">Blogs</a>
            <a href="{{ route('pages.faq') }}">Veelgestelde vragen</a>
            <a href="{{ route('pages.contact') }}">Contact</a>
        </nav>
    </div>
</footer>
