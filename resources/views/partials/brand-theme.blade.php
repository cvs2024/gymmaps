<style>
    :root {
        --gm-brand-teal: #0f5b57;
        --gm-brand-teal-dark: #0b4945;
        --gm-brand-peach: #fff1e8;
        --gm-brand-peach-soft: #fff7f2;
        --gm-brand-orange: #ff6a3d;
        --gm-brand-orange-dark: #ea5530;
        --gm-brand-navy: #11304a;
        --gm-brand-offwhite: #fffdfb;
        --gm-brand-border: #e8ddd4;
        --gm-brand-text: #163b4b;
        --gm-brand-muted: #5f7383;
    }

    body {
        color: var(--gm-brand-text);
        background: #F2C1B3 !important;
    }

    h1, h2, h3, h4 {
        color: var(--gm-brand-teal-dark);
    }

    .card {
        border-radius: 18px;
        border-color: rgba(145, 168, 151, 0.45);
        background: rgba(255, 255, 255, 0.92);
        box-shadow: 0 12px 28px rgba(17, 48, 74, 0.08);
    }

    .btn,
    .btn-primary,
    .btn-secondary,
    .btn-light,
    .btn-ghost {
        background: var(--gm-brand-orange);
        border-color: var(--gm-brand-orange);
        color: #fff;
    }

    .btn:hover,
    .btn-primary:hover,
    .btn-secondary:hover,
    .btn-light:hover,
    .btn-ghost:hover {
        background: var(--gm-brand-orange-dark);
        border-color: var(--gm-brand-orange-dark);
        color: #fff;
    }

    /* Cross-page visual unification (homepage look) */
    .hero,
    .intro-bar,
    .cta-bottom,
    .sidebar {
        background: linear-gradient(135deg, var(--gm-brand-teal) 0%, #1d7469 100%) !important;
        color: #fff !important;
        border-color: rgba(15, 91, 87, 0.42) !important;
    }

    .hero p,
    .intro-bar p,
    .cta-bottom p,
    .sidebar p,
    .sidebar span {
        color: rgba(255, 255, 255, 0.92) !important;
    }

    .panel,
    .section,
    .form-shell,
    .form-card,
    .article,
    .qa,
    .post-card,
    .price-card,
    .benefit {
        background: rgba(255, 255, 255, 0.92) !important;
        border-color: rgba(145, 168, 151, 0.45) !important;
        box-shadow: 0 12px 28px rgba(17, 48, 74, 0.08);
    }

    input,
    select,
    textarea {
        background: #fffefc !important;
        border-color: #d8cabc !important;
        color: var(--gm-brand-text) !important;
    }

    label,
    .section h2,
    .post-title,
    .price-name,
    .price,
    .location-name {
        color: var(--gm-brand-teal-dark) !important;
    }

    p,
    li,
    .muted,
    .meta,
    .hint,
    .field-help {
        color: var(--gm-brand-muted);
    }
</style>
