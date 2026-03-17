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
        position: relative;
        isolation: isolate;
    }

    body::before,
    body::after {
        content: "";
        position: fixed;
        pointer-events: none;
        z-index: -1;
        background-repeat: no-repeat;
        background-size: contain;
    }

    body::before {
        width: min(34vw, 420px);
        height: min(38vw, 470px);
        left: -84px;
        bottom: -88px;
        opacity: 0.16;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 260 260'%3E%3Cg fill='%231f6b5c'%3E%3Cellipse cx='92' cy='56' rx='24' ry='46' transform='rotate(-30 92 56)'/%3E%3Cellipse cx='132' cy='86' rx='22' ry='44' transform='rotate(-12 132 86)'/%3E%3Cellipse cx='148' cy='126' rx='20' ry='40' transform='rotate(6 148 126)'/%3E%3Cellipse cx='132' cy='164' rx='20' ry='40' transform='rotate(28 132 164)'/%3E%3Crect x='95' y='48' width='10' height='156' rx='5' transform='rotate(18 100 126)'/%3E%3C/g%3E%3C/svg%3E");
    }

    body::after {
        width: min(30vw, 340px);
        height: min(32vw, 380px);
        right: -74px;
        top: 36%;
        opacity: 0.13;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 220 220'%3E%3Cg fill='%23205a4f'%3E%3Cellipse cx='76' cy='82' rx='16' ry='36' transform='rotate(-28 76 82)'/%3E%3Cellipse cx='112' cy='64' rx='16' ry='34' transform='rotate(18 112 64)'/%3E%3Cellipse cx='130' cy='100' rx='14' ry='30' transform='rotate(36 130 100)'/%3E%3Crect x='96' y='58' width='8' height='104' rx='4'/%3E%3C/g%3E%3C/svg%3E");
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

    @media (max-width: 760px) {
        body::before {
            width: 210px;
            height: 230px;
            left: -90px;
            bottom: -82px;
            opacity: 0.13;
        }

        body::after {
            width: 156px;
            height: 176px;
            right: -84px;
            top: 44%;
            opacity: 0.11;
        }
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
