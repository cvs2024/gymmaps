<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @include('partials.google-site-verification')
    <title>Gymmap.nl - Sportlocaties in Nederland</title>
    <meta name="description" content="GymMaps.nl helpt je snel sportscholen, personal trainers en sportlocaties in Nederland te vinden op kaart, inclusief adres, afstand en sportfilter.">
    @include('partials.favicon')
    @include('partials.brand-theme')
    @php
        $heroBackdropCandidates = [
            'hero/filter-map-bg.png',
            'hero/filter-map-bg.jpg',
            'hero/filter-map-bg.jpeg',
            'hero/filter-map-bg.webp',
        ];
        $heroBackdropPath = collect($heroBackdropCandidates)
            ->first(fn ($path) => is_file(public_path($path)));
        $heroBackdropUrl = $heroBackdropPath ? asset($heroBackdropPath).'?v='.filemtime(public_path($heroBackdropPath)) : null;

        $heroSideImageCandidates = [
            'hero/filter-side-art.png',
            'hero/filter-side-art.jpg',
            'hero/filter-side-art.jpeg',
            'hero/filter-side-art.webp',
            'hero/left-filter-art.png',
            'hero/left-filter-art.jpg',
            'hero/left-filter-art.jpeg',
            'hero/left-filter-art.webp',
        ];
        $heroSideImagePath = collect($heroSideImageCandidates)
            ->first(fn ($path) => is_file(public_path($path)));
        $heroSideImageUrl = $heroSideImagePath ? asset($heroSideImagePath).'?v='.filemtime(public_path($heroSideImagePath)) : null;

        $gymbuddyCardBgCandidates = [
            'gymbuddy.png',
            'hero/gymbuddy-card-bg.png',
            'hero/gymbuddy-card-bg.jpg',
            'hero/gymbuddy-card-bg.jpeg',
            'hero/gymbuddy-card-bg.webp',
        ];
        $gymbuddyCardBgPath = collect($gymbuddyCardBgCandidates)
            ->first(fn ($path) => is_file(public_path($path)));
        $gymbuddyCardBgUrl = $gymbuddyCardBgPath ? asset($gymbuddyCardBgPath).'?v='.filemtime(public_path($gymbuddyCardBgPath)) : null;

        $trainerCardImageCandidates = [
            'stockfoto PT.png',
            'stockfoto-pt.png',
            'personal-trainer.png',
            'trainer.png',
        ];
        $trainerCardImagePath = collect($trainerCardImageCandidates)
            ->first(fn ($path) => is_file(public_path($path)));
        $trainerCardImageUrl = $trainerCardImagePath ? asset($trainerCardImagePath).'?v='.filemtime(public_path($trainerCardImagePath)) : null;

        $ownerGrowthImageCandidates = [
            'owner-growth.png',
            'owner-growth.jpg',
            'sportschool-stock.png',
            'sportschool-stock.jpg',
            'premium-stock.png',
            'premium-stock.jpg',
            'hero/hero-stock.jpg',
        ];
        $ownerGrowthImagePath = collect($ownerGrowthImageCandidates)
            ->first(fn ($path) => is_file(public_path($path)));
        $ownerGrowthImageUrl = $ownerGrowthImagePath ? asset($ownerGrowthImagePath).'?v='.filemtime(public_path($ownerGrowthImagePath)) : null;
    @endphp
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300&display=swap');

        :root {
            color-scheme: light;
            --brand-teal: #0f5b57;
            --brand-teal-dark: #0b4945;
            --brand-peach: #fff1e8;
            --brand-peach-soft: #fff7f2;
            --brand-orange: #ff6a3d;
            --brand-orange-dark: #ea5530;
            --brand-navy: #11304a;
            --bg: #fff8f3;
            --card: #fffefe;
            --text: #163b4b;
            --muted: #5f7383;
            --accent: var(--brand-orange);
            --accent-dark: var(--brand-orange-dark);
            --border: #e7ddd6;
            --shadow-soft: 0 10px 30px rgba(18, 47, 70, 0.08);
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: "Segoe UI", Roboto, sans-serif;
            background: #F2C1B3;
            color: var(--text);
            min-height: 100vh;
        }

        .gm-site-nav {
            background: #f7fcfa;
            border-bottom: 1px solid #deece8;
            backdrop-filter: blur(3px);
        }

        .gm-site-nav-inner {
            padding: 5px 18px;
            gap: 28px;
        }

        .gm-nav-menu {
            gap: 18px;
            font-size: 0.88rem;
        }

        .gm-nav-link,
        .gm-nav-item > a {
            color: #21465a;
        }

        .gm-nav-link:hover,
        .gm-nav-item > a:hover {
            color: var(--brand-teal);
        }

        .gm-nav-link::after,
        .gm-nav-item > a::after {
            background: var(--brand-teal);
        }

        .container {
            max-width: 1220px;
            margin: 0 auto;
            padding: 12px 16px 40px;
            position: relative;
        }

        h1 { margin: 0 0 8px; font-size: 2.1rem; line-height: 1.16; color: var(--brand-teal-dark); }
        p { margin: 0; color: inherit; }

        .toolbar {
            margin-top: 16px;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .btn {
            border: 0;
            border-radius: 14px;
            padding: 12px 18px;
            font-weight: 700;
            text-decoration: none;
            display: inline-block;
            cursor: pointer;
            transition: transform 0.14s ease, box-shadow 0.2s ease, background-color 0.2s ease;
        }

        .btn-primary { background: var(--brand-orange); color: #fff; box-shadow: 0 10px 18px rgba(255, 106, 61, 0.24); }
        .btn-primary:hover { background: var(--brand-orange-dark); transform: translateY(-1px); }
        .btn-light { background: var(--brand-orange); color: #fff; }
        .btn-light:hover { background: var(--brand-orange-dark); transform: translateY(-1px); }

        .card {
            background: var(--card);
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 16px;
            margin-bottom: 14px;
            box-shadow: var(--shadow-soft);
        }

        .hero-stage {
            position: relative;
            background:
                linear-gradient(180deg, rgba(255, 247, 242, 0.72) 0%, rgba(255, 247, 242, 0.62) 100%)
                @if($heroBackdropUrl)
                    , url('{{ $heroBackdropUrl }}')
                @endif
            ;
            background-size:
                100% 100%
                @if($heroBackdropUrl)
                    , 100% auto
                @endif
            ;
            background-position:
                center center
                @if($heroBackdropUrl)
                    , center bottom
                @endif
            ;
            background-repeat: no-repeat;
            border: 1px solid #f2dfd2;
            border-radius: 20px;
            padding: 12px;
            margin-bottom: 14px;
            overflow: hidden;
            box-shadow: 0 14px 36px rgba(34, 48, 62, 0.08);
        }

        .hero-stage::before,
        .hero-stage::after {
            content: "";
            position: absolute;
            pointer-events: none;
            z-index: 0;
            background-repeat: no-repeat;
            background-size: contain;
            opacity: 0.18;
        }

        .hero-stage::before {
            width: 238px;
            height: 238px;
            left: -56px;
            bottom: -42px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 220 220'%3E%3Cg fill='%231f6b5c'%3E%3Cellipse cx='96' cy='52' rx='22' ry='46' transform='rotate(-30 96 52)'/%3E%3Cellipse cx='132' cy='82' rx='22' ry='46' transform='rotate(-12 132 82)'/%3E%3Cellipse cx='147' cy='124' rx='20' ry='42' transform='rotate(8 147 124)'/%3E%3Cellipse cx='128' cy='160' rx='20' ry='40' transform='rotate(26 128 160)'/%3E%3Crect x='92' y='46' width='10' height='140' rx='5' transform='rotate(18 97 116)'/%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.2;
        }

        .hero-stage::after {
            width: 194px;
            height: 194px;
            right: -58px;
            top: -44px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 200 200'%3E%3Cg fill='%23215f53'%3E%3Cellipse cx='68' cy='78' rx='16' ry='36' transform='rotate(-28 68 78)'/%3E%3Cellipse cx='104' cy='62' rx='16' ry='34' transform='rotate(18 104 62)'/%3E%3Cellipse cx='126' cy='98' rx='14' ry='30' transform='rotate(36 126 98)'/%3E%3Crect x='90' y='56' width='8' height='100' rx='4'/%3E%3C/g%3E%3C/svg%3E");
            opacity: 0.14;
        }

        .owner-growth {
            position: relative;
            overflow: hidden;
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(260px, 360px);
            align-items: center;
            gap: 16px;
            background: #DCDDD2;
            border: 1px solid #c8ccb9;
            border-radius: 18px;
            padding: 16px 18px;
            margin-top: 0;
        }

        .owner-growth::before,
        .owner-growth::after {
            content: "";
            position: absolute;
            pointer-events: none;
            z-index: 0;
            background-repeat: no-repeat;
            background-size: contain;
        }

        .owner-growth::before {
            width: 172px;
            height: 172px;
            left: -56px;
            bottom: -62px;
            opacity: 0.16;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 220 220'%3E%3Cg fill='%231f6b5c'%3E%3Cellipse cx='88' cy='60' rx='20' ry='40' transform='rotate(-24 88 60)'/%3E%3Cellipse cx='124' cy='90' rx='19' ry='38' transform='rotate(-8 124 90)'/%3E%3Cellipse cx='136' cy='132' rx='18' ry='34' transform='rotate(14 136 132)'/%3E%3Crect x='96' y='56' width='8' height='118' rx='4'/%3E%3C/g%3E%3C/svg%3E");
        }

        .owner-growth::after {
            width: 148px;
            height: 148px;
            right: -42px;
            top: -26px;
            opacity: 0.12;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 200 200'%3E%3Cg fill='%23205a4f'%3E%3Cellipse cx='72' cy='78' rx='15' ry='32' transform='rotate(-30 72 78)'/%3E%3Cellipse cx='104' cy='60' rx='15' ry='32' transform='rotate(18 104 60)'/%3E%3Cellipse cx='122' cy='96' rx='13' ry='26' transform='rotate(34 122 96)'/%3E%3Crect x='92' y='58' width='7' height='90' rx='3.5'/%3E%3C/g%3E%3C/svg%3E");
        }

        .owner-growth > * {
            position: relative;
            z-index: 1;
        }

        .owner-growth h2 {
            margin: 0 0 6px;
            font-size: 1.62rem;
            color: var(--brand-teal-dark);
        }

        .owner-growth p {
            color: #526d7f;
            line-height: 1.5;
        }

        .owner-growth-benefits {
            margin-top: 10px;
        }

        .owner-growth-benefits-title {
            margin: 0 0 6px;
            font-size: 0.95rem;
            font-weight: 700;
            color: var(--brand-teal-dark);
        }

        .owner-growth-benefits-list {
            margin: 0;
            padding-left: 18px;
            color: #4c687b;
            display: grid;
            gap: 4px;
            font-size: 0.92rem;
            line-height: 1.45;
        }

        .owner-growth .btn {
            white-space: nowrap;
            justify-self: end;
            align-self: end;
            position: relative;
            z-index: 3;
        }

        .owner-growth-media {
            position: relative;
            z-index: 2;
            width: 100%;
            height: 176px;
            border-radius: 16px;
            overflow: hidden;
            border: 1px solid #d3e4d9;
            background: linear-gradient(160deg, #edf7f1 0%, #e5f0ea 100%);
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.5);
        }

        .owner-growth-media img {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
            object-position: center;
        }

        .owner-growth-media .btn {
            position: absolute;
            right: 12px;
            bottom: 12px;
            z-index: 4;
            margin: 0;
            box-shadow: 0 8px 20px rgba(255, 106, 61, 0.34);
        }

        .top-layout {
            display: grid;
            grid-template-columns: minmax(320px, 1.04fr) minmax(0, 1.6fr);
            gap: 14px;
            align-items: stretch;
            margin-bottom: 0;
            position: relative;
            z-index: 1;
        }

        .filter-intro {
            margin: 0 0 12px;
            color: #1d4a45;
        }

        .filter-intro h1 {
            margin-bottom: 8px;
            max-width: 20ch;
            color: #0b4945;
        }

        .filter-intro p {
            font-size: 1.02rem;
            line-height: 1.4;
            color: #335861;
            max-width: 34ch;
        }

        .filter-panel {
            display: flex;
            flex-direction: column;
            height: 100%;
            background: #E6A08F;
            border-color: #d18f80;
            backdrop-filter: blur(2px);
        }

        .filter-panel form {
            display: grid;
            gap: 10px;
        }

        .filter-intro p {
            margin: 0;
            color: inherit;
        }

        .field-search { flex: 2 1 0; min-width: 0; position: relative; }
        .field-radius { flex: 1 1 0; min-width: 0; }
        .field-options { flex: 1 1 0; min-width: 0; position: relative; }

        .field-label {
            display: block;
            font-size: 0.86rem;
            font-weight: 600;
            color: #224f58;
            margin-bottom: 7px;
        }

        input[type="text"], select, .multi-trigger {
            width: 100%;
            height: 44px;
            border: 1px solid #dfd8d2;
            border-radius: 11px;
            padding: 10px 12px;
            font: inherit;
            background: #fffdfb;
            color: #193e50;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.5);
        }

        .multi-trigger {
            text-align: left;
            cursor: pointer;
        }

        .multi-menu {
            position: absolute;
            z-index: 120;
            left: 0;
            right: 0;
            top: calc(100% + 6px);
            background: #fff;
            border: 1px solid #eaded5;
            border-radius: 13px;
            padding: 8px;
            display: none;
            max-height: 230px;
            overflow: auto;
            box-shadow: 0 14px 28px rgba(19, 47, 68, 0.14);
        }

        .multi-menu.open { display: block; }

        .suggestions {
            position: absolute;
            z-index: 130;
            left: 0;
            right: 0;
            top: calc(100% + 6px);
            background: #fff;
            border: 1px solid #eaded5;
            border-radius: 13px;
            box-shadow: 0 14px 28px rgba(19, 47, 68, 0.14);
            overflow: hidden;
            display: none;
        }

        .suggestions.open { display: block; }

        .suggestion-item {
            width: 100%;
            border: 0;
            background: #fff;
            text-align: left;
            padding: 10px 12px;
            font: inherit;
            color: var(--text);
            cursor: pointer;
            border-bottom: 1px solid #edf2f7;
        }

        .suggestion-item:last-child { border-bottom: 0; }
        .suggestion-item:hover, .suggestion-item.active { background: #f3f8fd; }

        .opt {
            display: flex;
            gap: 8px;
            align-items: center;
            padding: 6px 4px;
            font-size: 0.95rem;
        }

        .actions {
            margin-top: 12px;
            display: flex;
            justify-content: flex-end;
        }

        .filter-panel .actions {
            justify-content: stretch;
            margin-top: 4px;
        }

        .filter-panel .actions .btn {
            width: 100%;
            text-align: center;
            height: 46px;
            font-size: 1rem;
            border-radius: 11px;
        }

        .list {
            display: grid;
            gap: 12px;
        }

        .map-wrap {
            overflow: hidden;
            padding: 0 0 8px;
            display: flex;
            flex-direction: column;
            height: 100%;
            background: #fffffe;
            border-color: #eadfd5;
        }

        #results-map {
            width: 100%;
            min-height: 420px;
            flex: 1 1 auto;
            border-radius: 18px 18px 0 0;
        }

        .map-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            padding: 10px 12px 8px;
            border-top: 1px solid #f0e2d7;
            background: #fffaf6;
        }

        .map-legend-item {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            border-radius: 999px;
            border: 1px solid #e8d9ce;
            background: #fffcfa;
            padding: 6px 12px;
            font-size: 0.85rem;
            color: #2d4b5f;
            cursor: pointer;
            user-select: none;
            transition: all 0.15s ease;
        }

        .map-legend-item.active {
            border-color: #f7bc99;
            background: #fff2e8;
            color: var(--brand-teal-dark);
            box-shadow: 0 6px 16px rgba(238, 123, 71, 0.22);
        }

        .location-name {
            font-size: 1.72rem;
            font-weight: 700;
            margin: 0;
            color: var(--brand-teal-dark);
        }

        .muted {
            color: var(--muted);
            font-size: 1.04rem;
            margin-top: 4px;
            line-height: 1.5;
        }

        .owner-subtle {
            margin-top: 8px;
            font-size: 0.86rem;
            color: #5d7286;
        }

        .owner-subtle a {
            color: var(--brand-teal-dark);
            font-weight: 600;
            text-decoration: none;
        }

        .owner-subtle a:hover {
            text-decoration: underline;
        }

        .tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 10px;
        }

        .tag {
            background: #e9f3ee;
            color: #24584d;
            font-size: 0.87rem;
            border-radius: 99px;
            padding: 6px 11px;
            border: 1px solid #d2e4da;
        }

        .location-card {
            display: grid;
            grid-template-columns: 260px minmax(0, 1fr) 290px;
            gap: 18px;
            align-items: stretch;
        }

        .opening-card {
            border: 1px solid #e8ddd3;
            border-radius: 16px;
            background: #fffaf6;
            padding: 14px 14px;
            align-self: center;
        }

        .opening-title {
            margin: 0 0 8px;
            font-size: 0.92rem;
            font-weight: 700;
            color: var(--brand-navy);
        }

        .opening-today {
            margin: 0 0 8px;
            font-size: 0.9rem;
            color: #365d77;
        }

        .opening-today strong {
            color: #FF5C39;
        }

        .opening-list {
            margin: 0;
            padding: 0;
            list-style: none;
            display: grid;
            gap: 4px;
        }

        .opening-list li {
            font-size: 0.83rem;
            color: #4e647a;
            line-height: 1.35;
            border-top: 1px solid #e7eef6;
            padding-top: 4px;
        }

        .opening-list li:first-child {
            border-top: 0;
            padding-top: 0;
        }

        .location-photo {
            width: 100%;
            height: 190px;
            object-fit: cover;
            border-radius: 16px;
            border: 1px solid var(--border);
            background: #edf2f7;
        }

        .location-photo.logo {
            object-fit: contain;
            padding: 10px;
            background: #fff;
        }

        .location-photo.placeholder {
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.92rem;
            font-weight: 600;
            color: #5c6f82;
            background: linear-gradient(180deg, #eef4fa 0%, #e7f0f8 100%);
        }

        .flash {
            margin-bottom: 10px;
            background: #e8f7f0;
            color: #14563c;
            padding: 12px;
            border-radius: 10px;
            border: 1px solid #bce8d4;
        }

        .overview-label {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin: 0 0 10px;
            padding: 8px 12px;
            border-radius: 999px;
            border: 1px solid #e8ddd3;
            background: #fff9f5;
            color: var(--brand-teal-dark);
            font-size: 0.9rem;
            font-weight: 700;
            box-shadow: 0 6px 14px rgba(17, 48, 74, 0.07);
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 10px;
        }

        .stat-item {
            border: 1px solid #e6dacf;
            border-radius: 14px;
            padding: 12px 14px;
            background: #fffaf5;
        }

        .stat-label {
            color: var(--muted);
            font-size: 0.85rem;
            margin: 0 0 4px;
        }

        .stat-value {
            margin: 0;
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--brand-navy);
        }

        .pager {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .pager a, .pager span {
            font-size: 0.92rem;
            color: #21415e;
            text-decoration: none;
            padding: 8px 10px;
            border-radius: 10px;
            border: 1px solid #e5d9cd;
            background: #fffaf7;
        }

        .pager span.disabled {
            color: #90a0b1;
            border-color: #dde6ef;
            background: #f4f7fb;
        }

        @media (max-width: 980px) {
            .location-card {
                grid-template-columns: 220px minmax(0, 1fr);
            }

            .opening-card {
                grid-column: 1 / -1;
            }
        }

        @media (max-width: 760px) {
            .top-layout {
                grid-template-columns: 1fr;
            }
            .hero-stage {
                padding: 10px;
            }
            .owner-growth {
                grid-template-columns: 1fr;
                align-items: start;
            }
            .owner-growth-media {
                height: 150px;
                max-width: 360px;
            }
            .owner-growth-media .btn {
                right: 10px;
                bottom: 10px;
            }
            .stats { grid-template-columns: 1fr; }
            .field-search, .field-radius, .field-options { flex: 1 1 auto; }
            h1 { font-size: 1.62rem; }
            .location-card { grid-template-columns: 1fr; }
            #results-map { min-height: 320px; }
            .hero-stage {
                background-size:
                    100% 100%
                    @if($heroBackdropUrl)
                        , cover
                    @endif
                ;
            }
            .hero-stage::before {
                width: 162px;
                height: 162px;
                left: -48px;
                bottom: -40px;
            }
            .hero-stage::after {
                width: 130px;
                height: 130px;
                right: -40px;
                top: -38px;
            }
            .owner-growth::before {
                width: 128px;
                height: 128px;
                left: -44px;
                bottom: -52px;
            }
            .owner-growth::after {
                width: 110px;
                height: 110px;
                right: -34px;
                top: -24px;
            }
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 14px;
            margin-top: 16px;
        }

        .feature-card {
            position: relative;
            overflow: hidden;
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(200px, 40%);
            align-items: stretch;
            gap: 12px;
            background: linear-gradient(145deg, #f6fbf8 0%, #eef8f3 56%, #fff8f1 100%);
            border: 1px solid #dce9e2;
            border-radius: 22px;
            padding: 22px;
            box-shadow: var(--shadow-soft);
            transition: transform 0.2s ease, box-shadow 0.24s ease;
        }

        .feature-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 32px rgba(17, 48, 74, 0.12);
        }

        .card-content {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            height: 100%;
        }

        .card-illustration {
            position: relative;
            z-index: 2;
            min-height: 220px;
            border-radius: 18px;
            overflow: hidden;
            align-self: stretch;
            background: rgba(255, 255, 255, 0.52);
            border: 1px solid rgba(222, 234, 226, 0.95);
            display: flex;
            align-items: flex-end;
            justify-content: center;
            pointer-events: none;
        }

        .card-illustration svg {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
        }

        .card-illustration img {
            width: 100%;
            height: 100%;
            display: block;
            object-fit: cover;
            object-position: center;
        }

        .feature-card::before,
        .feature-card::after {
            content: "";
            position: absolute;
            pointer-events: none;
            background-repeat: no-repeat;
            background-size: contain;
            z-index: 0;
        }

        .feature-card::before {
            width: 152px;
            height: 152px;
            left: -52px;
            top: -34px;
            opacity: 0.16;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 200 200'%3E%3Cg fill='%231f6b5c'%3E%3Cellipse cx='72' cy='80' rx='16' ry='34' transform='rotate(-28 72 80)'/%3E%3Cellipse cx='106' cy='62' rx='16' ry='33' transform='rotate(18 106 62)'/%3E%3Cellipse cx='124' cy='96' rx='14' ry='28' transform='rotate(34 124 96)'/%3E%3Crect x='92' y='58' width='7' height='92' rx='3.5'/%3E%3C/g%3E%3C/svg%3E");
        }

        .feature-card::after {
            width: 178px;
            height: 178px;
            right: -58px;
            bottom: -84px;
            opacity: 0.13;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 220 220'%3E%3Cg fill='%231f6b5c'%3E%3Cellipse cx='90' cy='60' rx='20' ry='40' transform='rotate(-24 90 60)'/%3E%3Cellipse cx='126' cy='90' rx='19' ry='38' transform='rotate(-8 126 90)'/%3E%3Cellipse cx='138' cy='132' rx='18' ry='34' transform='rotate(14 138 132)'/%3E%3Crect x='98' y='56' width='8' height='118' rx='4'/%3E%3C/g%3E%3C/svg%3E");
        }

        .feature-card h3 {
            margin: 0 0 10px;
            color: var(--brand-teal-dark);
            font-size: 2.08rem;
            line-height: 1.1;
        }

        .feature-card p {
            color: #4f6677;
            font-size: 1.06rem;
            line-height: 1.55;
            max-width: 32ch;
        }

        .feature-icon {
            position: absolute;
            top: 18px;
            right: 18px;
            width: 56px;
            height: 56px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(255, 255, 255, 0.75);
            border: 1px solid #e8ddd2;
            box-shadow: 0 8px 18px rgba(17, 48, 74, 0.08);
            opacity: 0.96;
        }

        .feature-icon svg {
            width: 32px;
            height: 32px;
        }

        .feature-card .btn {
            margin-top: auto;
        }

        .feature-card.gymbuddy-card {
            background: linear-gradient(130deg, #eef8f1 0%, #e6f4ea 52%, #f8fff8 100%);
            border-color: #d2e6db;
        }

        .feature-card.gymbuddy-card .card-illustration {
            background: linear-gradient(180deg, rgba(230, 244, 236, 0.35) 0%, rgba(230, 244, 236, 0.55) 100%);
            background-repeat: no-repeat;
        }

        .feature-card.trainer-card {
            background: linear-gradient(130deg, #fff6ef 0%, #fdf2e8 52%, #fffaf5 100%);
            border-color: #eadfd6;
        }

        .feature-card.gymbuddy-card p {
            max-width: 23ch;
            font-size: 0.99rem;
            color: #2f495d;
        }

        .feature-card.trainer-card p {
            max-width: 26ch;
        }

        .hero-stage::before,
        .hero-stage::after,
        .owner-growth::before,
        .owner-growth::after,
        .feature-card::before,
        .feature-card::after {
            content: none;
        }

        .gm-site-footer {
            margin-top: 34px;
            background:
                radial-gradient(780px 240px at 20% -20%, rgba(255, 154, 106, 0.18), transparent 62%),
                linear-gradient(135deg, #103f48 0%, #0e5b57 56%, #0d4f5d 100%);
            border-top: 1px solid rgba(255, 255, 255, 0.08);
        }

        .gm-site-footer-inner {
            padding: 24px 20px;
        }

        .gm-site-footer a {
            color: #ffe1d0;
        }

        @media (max-width: 980px) {
            .feature-grid {
                grid-template-columns: 1fr;
            }

            .feature-card {
                grid-template-columns: minmax(0, 1fr) minmax(180px, 34%);
            }
        }

        @media (max-width: 760px) {
            .feature-card {
                grid-template-columns: 1fr;
                padding: 18px;
            }

            .feature-card h3 {
                font-size: 1.72rem;
            }

            .card-illustration {
                min-height: 180px;
                order: 2;
            }

            .feature-card::before {
                width: 96px;
                height: 96px;
                left: -34px;
                top: -24px;
            }
            .feature-card::after {
                width: 102px;
                height: 102px;
                right: -46px;
                bottom: -58px;
            }
            .gm-nav-logo {
                width: clamp(190px, 44vw, 250px);
                min-width: 190px;
            }
        }
    </style>
</head>
<body>
@include('partials.site-header')

<div class="container">

    @if(session('status'))
        <div class="flash">{{ session('status') }}</div>
    @endif

    @if(request()->routeIs('overview'))
        <p class="overview-label">📍 Resultatenoverzicht</p>
    @endif

    <section class="hero-stage">
        <div class="top-layout">
            <aside class="card filter-panel">
                <div class="filter-intro">
                    <h1>Zin om te sporten?</h1>
                    <p>Vind hier de sportschool of andere sportactiviteit bij jou in de buurt!</p>
                </div>
                <form method="GET" action="{{ route('overview') }}">
                    <div class="field-search">
                        <label class="field-label" for="q">Zoek op locatie / adres / postcode</label>
                        <input id="q" type="text" name="q" value="{{ $query }}" placeholder="Bijv. Utrecht, 3511 NS of Oudegracht 100">
                        <div class="suggestions" id="searchSuggestions"></div>
                    </div>

                    <div class="field-options" id="sportsFilter">
                        <label class="field-label">Sport opties</label>
                        <button type="button" class="multi-trigger" id="multiTrigger">Kies sporten</button>
                        <div class="multi-menu" id="multiMenu">
                            @foreach($sports as $sport)
                                <label class="opt">
                                    <input type="checkbox" name="sports[]" value="{{ $sport->id }}" @checked($selectedSports->contains($sport->id))>
                                    {{ $sport->name }}
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div class="field-radius">
                        <label class="field-label" for="radius">Radius</label>
                        <select id="radius" name="radius">
                            <option value="5" @selected($radius === 5)>5 km</option>
                            <option value="10" @selected($radius === 10)>10 km</option>
                            <option value="20" @selected($radius === 20)>20 km</option>
                            <option value="50" @selected($radius === 50)>50 km</option>
                            <option value="250" @selected($radius === 250)>50+ km</option>
                        </select>
                    </div>

                    <div class="actions">
                        <button class="btn btn-primary" type="submit">zoek alle gyms</button>
                    </div>
                </form>
            </aside>

            <article class="card map-wrap">
                <div id="results-map"></div>
                <div class="map-legend">
                    <button type="button" class="map-legend-item active" data-category="all">📍 Alles</button>
                    <button type="button" class="map-legend-item" data-category="fitness">🏋️ Fitness / krachttraining</button>
                    <button type="button" class="map-legend-item" data-category="boxing">🥊 Boksschool</button>
                    <button type="button" class="map-legend-item" data-category="yoga">🧘 Yogastudio</button>
                    <button type="button" class="map-legend-item" data-category="crossfit">🏋️‍♂️ CrossFit</button>
                    <button type="button" class="map-legend-item" data-category="other">📌 Overig</button>
                </div>
            </article>
        </div>
    </section>

    <section class="card owner-growth">
            <div>
                <h2>Heb jij een sportschool?</h2>
                <p>Vergroot je zichtbaarheid en bereik sporters in jouw omgeving.</p>
                <div class="owner-growth-benefits">
                    <p class="owner-growth-benefits-title">Voordelen</p>
                    <ul class="owner-growth-benefits-list">
                        <li>Meer zichtbaarheid in jouw stad</li>
                        <li>Bovenaan in zoekresultaten</li>
                        <li>Proefles aanvragen ontvangen</li>
                        <li>Professioneel profiel</li>
                    </ul>
                </div>
            </div>
            <div class="owner-growth-media" aria-hidden="true">
                @if($ownerGrowthImageUrl)
                    <img src="{{ $ownerGrowthImageUrl }}" alt="Premium sportschool stockfoto">
                @endif
                <a class="btn btn-primary" href="{{ route('pages.pricing') }}">Bekijk Premium</a>
            </div>
    </section>

    @if($showOverview ?? true)
    <section class="list">
        @if($isUsingFallbackSource)
            <article class="card">
                <p class="location-name">KVK-locaties nog niet beschikbaar</p>
                <p class="muted">Er zijn nog geen KVK sportscholen met coördinaten geïmporteerd. Tijdelijk tonen we alle beschikbare locaties met coördinaten.</p>
            </article>
        @endif

        @if($query !== '' && !$center && $results->isEmpty())
            <article class="card">
                <p class="location-name">Geen middelpunt gevonden voor "{{ $query }}"</p>
                <p class="muted">Tip: probeer een plaatsnaam of postcode die al bekend is in de database.</p>
            </article>
        @endif

        @if($center)
            <article class="card">
                <p class="muted">Zoekcentrum: <strong>{{ $center->city }}</strong> ({{ $center->postcode }}) · {{ $results->count() }} resultaten · pagina {{ $paginatedResults?->currentPage() ?? 1 }} van {{ $paginatedResults?->lastPage() ?? 1 }}</p>
            </article>
        @endif

        @if($center && $results->isEmpty())
            <article class="card">
                <p class="location-name">Geen resultaten binnen deze straal</p>
                <p class="muted">Probeer een grotere radius zoals 20 of 50 km.</p>
            </article>
        @endif

        @foreach(($paginatedResults?->items() ?? []) as $location)
            <article class="card">
                <div class="location-card">
                    <div>
                        @if($location->display_logo_url || $location->display_photo_url)
                            <img
                                class="location-photo {{ $location->display_logo_url ? 'logo' : '' }}"
                                src="{{ $location->display_logo_url ?: $location->display_photo_url }}"
                                alt="Foto van {{ $location->name }}"
                                @if($location->display_logo_url)
                                    data-fallback="{{ $location->display_photo_url }}"
                                    data-final-fallback="{{ $location->fallback_photo_url }}"
                                    onerror="if(this.dataset.fallback){this.src=this.dataset.fallback;this.classList.remove('logo');this.dataset.fallback='';return;}if(this.dataset.finalFallback){this.src=this.dataset.finalFallback;this.dataset.finalFallback='';this.classList.remove('logo');return;}this.onerror=null;"
                                @else
                                    data-final-fallback="{{ $location->fallback_photo_url }}"
                                    onerror="if(this.dataset.finalFallback){this.src=this.dataset.finalFallback;this.dataset.finalFallback='';return;}this.onerror=null;"
                                @endif
                            >
                        @else
                            <div class="location-photo placeholder">Geen foto beschikbaar</div>
                        @endif
                    </div>
                    <div>
                        <p class="location-name">{{ $location->name }}</p>
                        <p class="muted">{{ $location->address }}, {{ $location->postcode }} {{ $location->city }}</p>
                        @if($location->distance_km !== null)
                            <p class="muted">Afstand: {{ number_format($location->distance_km, 1, ',', '.') }} km</p>
                        @endif
                        <div class="tags">
                            @foreach($location->sports as $sport)
                                <span class="tag">{{ $sport->name }}</span>
                            @endforeach
                        </div>
                        @if($location->phone)
                            <p class="muted">
                                {{ $location->phone }}
                            </p>
                        @endif
                        <p style="margin-top: 10px;">
                            <a class="btn btn-primary" href="{{ route('locations.show', $location) }}">Bekijk sportschool</a>
                        </p>
                        <p class="owner-subtle">
                            Ben jij eigenaar van deze sportschool?
                            <a href="{{ route('pages.pricing') }}">Bekijk de mogelijkheden op GymMaps.</a>
                        </p>
                    </div>
                    <aside class="opening-card">
                        <p class="opening-title">Openingstijden</p>
                        @if(!empty($location->opening_hours_today))
                            <p class="opening-today"><strong>Vandaag:</strong> {{ $location->opening_hours_today }}</p>
                        @else
                            <p class="opening-today">Nog niet beschikbaar</p>
                        @endif
                    </aside>
                </div>
            </article>
        @endforeach

        @if($paginatedResults && $paginatedResults->lastPage() > 1)
            <article class="card">
                <div class="pager">
                    @if($paginatedResults->onFirstPage())
                        <span class="disabled">Vorige</span>
                    @else
                        <a href="{{ $paginatedResults->previousPageUrl() }}">Vorige</a>
                    @endif

                    <span>Pagina {{ $paginatedResults->currentPage() }} / {{ $paginatedResults->lastPage() }}</span>

                    @if($paginatedResults->hasMorePages())
                        <a href="{{ $paginatedResults->nextPageUrl() }}">Volgende</a>
                    @else
                        <span class="disabled">Volgende</span>
                    @endif
                </div>
            </article>
        @endif

        @if(($paginatedResults?->count() ?? 0) === 0)
            <article class="card">
                <p class="location-name">Nog geen resultaten zichtbaar</p>
                <p class="muted">Controleer de filters en radius, of importeer KVK-data opnieuw zodat kaart en overzicht gevuld worden.</p>
            </article>
        @endif
    </section>
    @endif

    <section class="feature-grid" aria-label="GymMaps extra opties">
        <article class="feature-card gymbuddy-card">
            <div class="card-content">
                <h3>Gymbuddy</h3>
                <p>Vindt jouw sportmaatje makkelijk en snel door het plaatsen van een oproep.</p>
                <a class="btn btn-primary" href="{{ route('gymbuddy.index') }}">Vind hier jouw Gymbuddy</a>
            </div>
            <div class="card-illustration" aria-hidden="true">
                @if($gymbuddyCardBgUrl)
                    <img src="{{ $gymbuddyCardBgUrl }}" alt="Gymbuddy illustratie">
                @else
                    <svg viewBox="0 0 420 300" role="img" focusable="false">
                        <defs>
                            <linearGradient id="buddyBg" x1="0" y1="0" x2="1" y2="1">
                                <stop offset="0%" stop-color="#dff2e8"/>
                                <stop offset="100%" stop-color="#effaf3"/>
                            </linearGradient>
                        </defs>
                        <rect width="420" height="300" fill="url(#buddyBg)"/>
                        <g opacity=".16" fill="#1f6b5c">
                            <ellipse cx="68" cy="68" rx="42" ry="20"/>
                            <ellipse cx="366" cy="64" rx="38" ry="18"/>
                        </g>
                        <g opacity=".2" fill="#2a7d6a">
                            <ellipse cx="42" cy="252" rx="58" ry="34"/>
                            <ellipse cx="392" cy="244" rx="64" ry="38"/>
                        </g>
                        <g>
                            <circle cx="154" cy="116" r="22" fill="#ffbc8a"/>
                            <rect x="125" y="138" width="62" height="80" rx="24" fill="#1f6b5c"/>
                            <rect x="132" y="218" width="22" height="54" rx="10" fill="#6da893"/>
                            <rect x="160" y="218" width="22" height="54" rx="10" fill="#6da893"/>
                            <path d="M178 146c22 8 42 24 58 42" stroke="#ff955f" stroke-width="14" stroke-linecap="round"/>
                            <rect x="145" y="176" width="20" height="32" rx="8" fill="#5bbca7"/>
                        </g>
                        <g>
                            <circle cx="264" cy="114" r="21" fill="#ffc49a"/>
                            <rect x="236" y="136" width="58" height="84" rx="22" fill="#2c8a73"/>
                            <rect x="240" y="220" width="22" height="52" rx="10" fill="#2f7365"/>
                            <rect x="270" y="220" width="22" height="52" rx="10" fill="#2f7365"/>
                            <path d="M238 150c-15 9-24 19-39 34" stroke="#ff955f" stroke-width="13" stroke-linecap="round"/>
                            <rect x="254" y="174" width="18" height="34" rx="8" fill="#5bbca7"/>
                        </g>
                        <circle cx="215" cy="164" r="11" fill="#ff6a3d"/>
                    </svg>
                @endif
            </div>
        </article>

        <article class="feature-card trainer-card">
            <div class="card-content">
                <h3>Personal Trainer</h3>
                <p>Plaats een oproep als je op zoek bent naar een ervaren personal trainer die je helpt jouw doelen te bereiken.</p>
                <a class="btn btn-primary" href="{{ route('pages.personal-trainer') }}">Vindt hier jouw Personal Trainer</a>
            </div>
            <div class="card-illustration" aria-hidden="true">
                @if($trainerCardImageUrl)
                    <img src="{{ $trainerCardImageUrl }}" alt="Personal trainer illustratie">
                @else
                    <svg viewBox="0 0 420 300" role="img" focusable="false">
                        <defs>
                            <linearGradient id="trainerBg" x1="0" y1="0" x2="1" y2="1">
                                <stop offset="0%" stop-color="#fff1e6"/>
                                <stop offset="100%" stop-color="#fff8f2"/>
                            </linearGradient>
                        </defs>
                        <rect width="420" height="300" fill="url(#trainerBg)"/>
                        <g opacity=".18" fill="#1f6b5c">
                            <ellipse cx="58" cy="252" rx="66" ry="34"/>
                            <ellipse cx="372" cy="248" rx="58" ry="30"/>
                        </g>
                        <g>
                            <circle cx="154" cy="112" r="21" fill="#ffc6a1"/>
                            <rect x="124" y="134" width="62" height="82" rx="22" fill="#1f6b5c"/>
                            <rect x="128" y="216" width="22" height="58" rx="10" fill="#487f70"/>
                            <rect x="157" y="216" width="22" height="58" rx="10" fill="#487f70"/>
                            <path d="M186 152c26 0 42 18 60 24" stroke="#ff6a3d" stroke-width="12" stroke-linecap="round"/>
                        </g>
                        <g>
                            <circle cx="262" cy="120" r="20" fill="#ffd2b3"/>
                            <rect x="236" y="140" width="56" height="78" rx="22" fill="#2e8e77"/>
                            <rect x="240" y="218" width="20" height="56" rx="10" fill="#286c60"/>
                            <rect x="266" y="218" width="20" height="56" rx="10" fill="#286c60"/>
                            <rect x="210" y="162" width="26" height="12" rx="6" fill="#ff6a3d"/>
                            <rect x="286" y="162" width="26" height="12" rx="6" fill="#ff6a3d"/>
                            <path d="M236 156c-16 4-28 8-42 12" stroke="#ffc59e" stroke-width="10" stroke-linecap="round"/>
                        </g>
                        <g fill="#2f7f6f" opacity=".38">
                            <ellipse cx="334" cy="86" rx="18" ry="34" transform="rotate(-24 334 86)"/>
                            <ellipse cx="358" cy="112" rx="14" ry="28" transform="rotate(12 358 112)"/>
                        </g>
                    </svg>
                @endif
            </div>
        </article>
    </section>
</div>
<script>
    const filterTrigger = document.getElementById('multiTrigger');
    const filterMenu = document.getElementById('multiMenu');
    const filterContainer = document.getElementById('sportsFilter');
    const queryInput = document.getElementById('q');
    const suggestionBox = document.getElementById('searchSuggestions');
    const suggestionEndpoint = @json(route('home.suggestions'));

    if (filterTrigger && filterMenu && filterContainer) {
        const filterChecks = Array.from(filterMenu.querySelectorAll('input[type="checkbox"]'));

        const updateFilterLabel = () => {
            const selected = filterChecks
                .filter((checkbox) => checkbox.checked)
                .map((checkbox) => checkbox.parentElement.textContent.trim());
            filterTrigger.textContent = selected.length ? selected.join(', ') : 'Kies sporten';
        };

        filterTrigger.addEventListener('click', () => {
            filterMenu.classList.toggle('open');
        });

        filterChecks.forEach((checkbox) => checkbox.addEventListener('change', updateFilterLabel));

        document.addEventListener('click', (event) => {
            if (!filterContainer.contains(event.target)) {
                filterMenu.classList.remove('open');
            }
        });

        updateFilterLabel();
    }

    if (queryInput && suggestionBox) {
        let debounceTimer = null;
        let abortController = null;
        let currentItems = [];
        let activeIndex = -1;

        const closeSuggestions = () => {
            suggestionBox.classList.remove('open');
            suggestionBox.innerHTML = '';
            currentItems = [];
            activeIndex = -1;
        };

        const setInputValue = (value) => {
            queryInput.value = value;
            closeSuggestions();
        };

        const updateActive = () => {
            const buttons = Array.from(suggestionBox.querySelectorAll('.suggestion-item'));
            buttons.forEach((button, index) => {
                button.classList.toggle('active', index === activeIndex);
            });
        };

        const renderSuggestions = (items) => {
            currentItems = items;
            activeIndex = -1;
            if (!items.length) {
                closeSuggestions();
                return;
            }

            suggestionBox.innerHTML = items
                .map((item) => `<button type="button" class="suggestion-item" data-value="${item.value.replace(/"/g, '&quot;')}">${item.label}</button>`)
                .join('');
            suggestionBox.classList.add('open');
        };

        queryInput.addEventListener('input', () => {
            const value = queryInput.value.trim();
            if (value.length < 1) {
                closeSuggestions();
                return;
            }

            if (debounceTimer) {
                clearTimeout(debounceTimer);
            }

            debounceTimer = setTimeout(async () => {
                if (abortController) {
                    abortController.abort();
                }

                abortController = new AbortController();

                try {
                    const response = await fetch(`${suggestionEndpoint}?q=${encodeURIComponent(value)}&limit=8`, {
                        method: 'GET',
                        headers: { 'Accept': 'application/json' },
                        signal: abortController.signal,
                    });

                    if (!response.ok) {
                        closeSuggestions();
                        return;
                    }

                    const payload = await response.json();
                    renderSuggestions(Array.isArray(payload.items) ? payload.items : []);
                } catch (error) {
                    closeSuggestions();
                }
            }, 180);
        });

        queryInput.addEventListener('keydown', (event) => {
            if (!currentItems.length) {
                return;
            }

            if (event.key === 'ArrowDown') {
                event.preventDefault();
                activeIndex = Math.min(activeIndex + 1, currentItems.length - 1);
                updateActive();
            } else if (event.key === 'ArrowUp') {
                event.preventDefault();
                activeIndex = Math.max(activeIndex - 1, 0);
                updateActive();
            } else if (event.key === 'Enter' && activeIndex >= 0) {
                event.preventDefault();
                setInputValue(currentItems[activeIndex].value);
            } else if (event.key === 'Escape') {
                closeSuggestions();
            }
        });

        suggestionBox.addEventListener('click', (event) => {
            const button = event.target.closest('.suggestion-item');
            if (!button) {
                return;
            }

            setInputValue(button.dataset.value || '');
        });

        document.addEventListener('click', (event) => {
            if (!suggestionBox.contains(event.target) && event.target !== queryInput) {
                closeSuggestions();
            }
        });
    }
</script>
@if($googleMapsKey !== '')
    <script>
        function initGymmapLeafletFallback() {
            const hasSearchCenter = {{ $hasSearchCenter ? 'true' : 'false' }};
            const hasLocationQuery = {{ $query !== '' ? 'true' : 'false' }};
            const center = [{{ $mapCenterLat }}, {{ $mapCenterLng }}];
            const locations = @json($mapLocations);
            const escapeHtml = (value) =>
                String(value ?? '')
                    .replaceAll('&', '&amp;')
                    .replaceAll('<', '&lt;')
                    .replaceAll('>', '&gt;')
                    .replaceAll('"', '&quot;')
                    .replaceAll("'", '&#039;');

            if (typeof L === 'undefined') {
                return;
            }

            const map = L.map('results-map', { zoomControl: true }).setView(center, {{ $mapZoom }});
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap-bijdragers',
            }).addTo(map);

            if (hasSearchCenter) {
                L.circleMarker(center, {
                    radius: 8,
                    color: '#ffffff',
                    weight: 2,
                    fillColor: '#174f86',
                    fillOpacity: 1,
                }).addTo(map).bindPopup('Zoekcentrum');
            }

            const markerRefs = [];
            const getCategory = (sports) => {
                const joined = Array.isArray(sports) ? sports.join(' ').toLowerCase() : '';
                if (joined.includes('crossfit')) return 'crossfit';
                if (joined.includes('bok')) return 'boxing';
                if (joined.includes('yoga')) return 'yoga';
                if (joined.includes('fitness') || joined.includes('kracht')) return 'fitness';
                return 'other';
            };

            const categoryMeta = {
                fitness: { emoji: '🏋️' },
                boxing: { emoji: '🥊' },
                yoga: { emoji: '🧘' },
                crossfit: { emoji: '🏋️‍♂️' },
                other: { emoji: '📍' },
            };

            locations.forEach((location) => {
                const category = getCategory(location.sports);
                const icon = L.divIcon({
                    html: `<div style="width:34px;height:34px;border-radius:50%;background:#0f5f8b;border:2px solid #fff;color:#fff;display:flex;align-items:center;justify-content:center;font-size:18px;box-shadow:0 2px 8px rgba(0,0,0,.3);">${(categoryMeta[category] || categoryMeta.other).emoji}</div>`,
                    className: '',
                    iconSize: [34, 34],
                    iconAnchor: [17, 17],
                });

                const marker = L.marker([location.lat, location.lng], { icon });
                if (hasLocationQuery) {
                    marker.addTo(map);
                }
                marker.gymmapsCategory = category;
                const distanceLine = location.distance !== null ? `<br>Afstand: ${location.distance.toFixed(1)} km` : '';
                const photoCandidate = location.logo_url || location.photo_url;
                const photoLine = photoCandidate
                    ? `<br><img src="${escapeHtml(photoCandidate)}" alt="${escapeHtml(location.name)}" style="width:160px;height:90px;object-fit:cover;border-radius:6px;margin-top:6px;" onerror="this.onerror=null;this.src='${escapeHtml(location.fallback_photo_url || '')}'">`
                    : '<br><span style="display:inline-block;margin-top:8px;color:#5c6f82;">Geen foto beschikbaar</span>';
                const detailLink = location.detail_url
                    ? `<br><a href="${escapeHtml(location.detail_url)}" style="display:inline-block;margin-top:8px;padding:7px 10px;background:#ff6a3d;color:#fff;text-decoration:none;border-radius:7px;">Bekijk sportschool</a>`
                    : '';
                marker.bindPopup(`<strong>${escapeHtml(location.name)}</strong><br>${escapeHtml(location.address)}${distanceLine}${photoLine}${detailLink}`);
                markerRefs.push(marker);
            });

            const legendItems = Array.from(document.querySelectorAll('.map-legend-item'));
            const fitVisibleBounds = () => {
                const visible = markerRefs.filter((marker) => map.hasLayer(marker));
                if (!visible.length) {
                    return;
                }
                const bounds = L.latLngBounds(visible.map((marker) => marker.getLatLng()));
                map.fitBounds(bounds, { padding: [28, 28] });
            };

            const applyLegendFilter = (category) => {
                legendItems.forEach((item) => {
                    item.classList.toggle('active', item.dataset.category === category);
                });

                markerRefs.forEach((marker) => {
                    const match = hasLocationQuery && (category === 'all' || marker.gymmapsCategory === category);
                    if (match && !map.hasLayer(marker)) {
                        marker.addTo(map);
                    }
                    if (!match && map.hasLayer(marker)) {
                        map.removeLayer(marker);
                    }
                });

                fitVisibleBounds();
            };

            legendItems.forEach((item) => {
                item.addEventListener('click', () => {
                    applyLegendFilter(item.dataset.category || 'all');
                });
            });

            applyLegendFilter(hasLocationQuery ? 'all' : 'none');
        }

        function loadGymmapLeafletAssetsAndInit() {
            if (window.__gymmapLeafletBootstrapped) {
                initGymmapLeafletFallback();
                return;
            }
            window.__gymmapLeafletBootstrapped = true;

            const css = document.createElement('link');
            css.rel = 'stylesheet';
            css.href = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.css';
            css.integrity = 'sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=';
            css.crossOrigin = '';
            document.head.appendChild(css);

            const script = document.createElement('script');
            script.src = 'https://unpkg.com/leaflet@1.9.4/dist/leaflet.js';
            script.integrity = 'sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=';
            script.crossOrigin = '';
            script.onload = () => initGymmapLeafletFallback();
            document.body.appendChild(script);
        }

        window.gm_authFailure = function () {
            loadGymmapLeafletAssetsAndInit();
        };

        function initGymmapResultsMap() {
            try {
            const hasSearchCenter = {{ $hasSearchCenter ? 'true' : 'false' }};
            const hasLocationQuery = {{ $query !== '' ? 'true' : 'false' }};
            const center = {
                lat: {{ $mapCenterLat }},
                lng: {{ $mapCenterLng }},
            };

            const locations = @json($mapLocations);

            const escapeHtml = (value) =>
                String(value ?? "")
                    .replaceAll("&", "&amp;")
                    .replaceAll("<", "&lt;")
                    .replaceAll(">", "&gt;")
                    .replaceAll('"', "&quot;")
                    .replaceAll("'", "&#039;");

            const map = new google.maps.Map(document.getElementById("results-map"), {
                center,
                zoom: {{ $mapZoom }},
                mapTypeControl: false,
                streetViewControl: false,
                fullscreenControl: true,
                styles: [
                    { elementType: "geometry", stylers: [{ color: "#e8f1f7" }] },
                    { elementType: "labels.text.fill", stylers: [{ color: "#2a4866" }] },
                    { elementType: "labels.text.stroke", stylers: [{ color: "#ffffff" }] },
                    { featureType: "administrative", elementType: "geometry.stroke", stylers: [{ color: "#a8c0d6" }] },
                    { featureType: "poi", elementType: "geometry", stylers: [{ color: "#d8e9d8" }] },
                    { featureType: "poi.park", elementType: "geometry", stylers: [{ color: "#cce4cf" }] },
                    { featureType: "road", elementType: "geometry", stylers: [{ color: "#ffffff" }] },
                    { featureType: "road.arterial", elementType: "geometry", stylers: [{ color: "#f2f7fb" }] },
                    { featureType: "road.highway", elementType: "geometry", stylers: [{ color: "#d5e4f4" }] },
                    { featureType: "transit", elementType: "geometry", stylers: [{ color: "#dce8f3" }] },
                    { featureType: "water", elementType: "geometry", stylers: [{ color: "#9ed1ee" }] },
                    { featureType: "water", elementType: "labels.text.fill", stylers: [{ color: "#21506f" }] },
                ],
            });

            const getCategory = (sports) => {
                const joined = Array.isArray(sports)
                    ? sports.join(" ").toLowerCase()
                    : "";
                if (joined.includes("crossfit")) return "crossfit";
                if (joined.includes("bok")) return "boxing";
                if (joined.includes("yoga")) return "yoga";
                if (joined.includes("fitness") || joined.includes("kracht")) return "fitness";
                return "other";
            };

            const categoryMeta = {
                fitness: { emoji: "🏋️", color: "#0d8f6f" },
                boxing: { emoji: "🥊", color: "#2f76d0" },
                yoga: { emoji: "🧘", color: "#4d9860" },
                crossfit: { emoji: "🏋️‍♂️", color: "#195f9b" },
                other: { emoji: "📍", color: "#4f6b85" },
            };

            const markerIcon = (category) => {
                const config = categoryMeta[category] ?? categoryMeta.other;
                const svg = `
                    <svg xmlns="http://www.w3.org/2000/svg" width="46" height="46" viewBox="0 0 46 46">
                        <circle cx="23" cy="23" r="20" fill="${config.color}" opacity="0.94" />
                        <circle cx="23" cy="23" r="20" fill="none" stroke="#ffffff" stroke-width="2.8" />
                    </svg>
                `.trim();

                return {
                    url: `data:image/svg+xml;charset=UTF-8,${encodeURIComponent(svg)}`,
                    scaledSize: new google.maps.Size(46, 46),
                    anchor: new google.maps.Point(23, 23),
                    labelOrigin: new google.maps.Point(23, 24),
                };
            };

            if (hasSearchCenter) {
                new google.maps.Marker({
                    position: center,
                    map,
                    title: "Zoekcentrum",
                    icon: {
                        path: google.maps.SymbolPath.CIRCLE,
                        fillColor: "#174f86",
                        fillOpacity: 1,
                        strokeColor: "#ffffff",
                        strokeWeight: 2,
                        scale: 8,
                    },
                });
            }

            const infoWindow = new google.maps.InfoWindow();
            const bounds = new google.maps.LatLngBounds();
            bounds.extend(center);

            const markers = [];
            let clustererInstance = null;
            let activeCategory = 'all';
            const shouldShowMarkers = hasLocationQuery;

            locations.forEach((location) => {
                const category = getCategory(location.sports);
                const marker = new google.maps.Marker({
                    position: { lat: location.lat, lng: location.lng },
                    map: shouldShowMarkers ? map : null,
                    title: location.name,
                    icon: markerIcon(category),
                    label: {
                        text: (categoryMeta[category] ?? categoryMeta.other).emoji,
                        color: "#ffffff",
                        fontSize: "16px",
                        fontWeight: "700",
                    },
                });
                marker.gymmapsCategory = category;
                markers.push(marker);

                if (shouldShowMarkers) {
                    bounds.extend(marker.getPosition());
                }

                marker.addListener("click", () => {
                    const distanceLine = location.distance !== null
                        ? `<br>Afstand: ${escapeHtml(location.distance.toFixed(1))} km`
                        : "";
                    const photoLine = (location.logo_url || location.photo_url)
                        ? `<br><img src="${escapeHtml(location.logo_url || location.photo_url)}" alt="${escapeHtml(location.name)}" style="width:160px;height:90px;object-fit:${location.logo_url ? 'contain' : 'cover'};padding:${location.logo_url ? '8px' : '0'};background:#fff;border-radius:6px;margin-top:6px;" ${location.logo_url ? `data-fallback="${escapeHtml(location.photo_url || '')}"` : ''} data-final-fallback="${escapeHtml(location.fallback_photo_url || '')}" onerror="if(this.dataset.fallback){this.src=this.dataset.fallback;this.style.objectFit='cover';this.style.padding='0';this.dataset.fallback='';return;}if(this.dataset.finalFallback){this.src=this.dataset.finalFallback;this.dataset.finalFallback='';return;}this.onerror=null;">`
                        : "";
                    const detailLink = location.detail_url
                        ? `<br><a href="${escapeHtml(location.detail_url)}" style="display:inline-block;margin-top:8px;padding:7px 10px;background:#ff6a3d;color:#fff;text-decoration:none;border-radius:7px;">Bekijk sportschool</a>`
                        : "";
                    infoWindow.setContent(
                        `<strong>${escapeHtml(location.name)}</strong><br>${escapeHtml(location.address)}${distanceLine}${photoLine}${detailLink}`
                    );
                    infoWindow.open(map, marker);
                });
            });

            if (shouldShowMarkers && locations.length > 0) {
                map.fitBounds(bounds);
            }

            if (shouldShowMarkers && window.markerClusterer && markers.length > 1) {
                const clusterRenderer = {
                    render({ count, position, markers: clusterMarkers }) {
                        const counts = clusterMarkers.reduce((acc, marker) => {
                            const key = marker.gymmapsCategory || "other";
                            acc[key] = (acc[key] ?? 0) + 1;
                            return acc;
                        }, {});
                        const dominantCategory = Object.keys(counts).sort((a, b) => counts[b] - counts[a])[0] || "other";
                        const meta = categoryMeta[dominantCategory] ?? categoryMeta.other;
                        const svg = `
                            <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 64 64">
                                <circle cx="32" cy="32" r="29" fill="${meta.color}" fill-opacity="0.88" />
                                <circle cx="32" cy="32" r="29" fill="none" stroke="#ffffff" stroke-width="3" />
                            </svg>
                        `.trim();

                        return new google.maps.Marker({
                            position,
                            icon: {
                                url: `data:image/svg+xml;charset=UTF-8,${encodeURIComponent(svg)}`,
                                scaledSize: new google.maps.Size(64, 64),
                                anchor: new google.maps.Point(32, 32),
                            },
                            label: {
                                text: `${meta.emoji} ${count}`,
                                color: "#ffffff",
                                fontSize: "15px",
                                fontWeight: "700",
                            },
                            zIndex: Number(google.maps.Marker.MAX_ZINDEX) + count,
                        });
                    },
                };

                clustererInstance = new markerClusterer.MarkerClusterer({ map, markers, renderer: clusterRenderer });
            }

            const legendItems = Array.from(document.querySelectorAll('.map-legend-item'));

            const fitVisibleBounds = () => {
                const visibleMarkers = markers.filter((marker) => marker.getVisible());
                if (!visibleMarkers.length) {
                    return;
                }

                const filteredBounds = new google.maps.LatLngBounds();
                visibleMarkers.forEach((marker) => filteredBounds.extend(marker.getPosition()));
                map.fitBounds(filteredBounds);
            };

            const applyLegendFilter = (category) => {
                activeCategory = category;
                legendItems.forEach((item) => {
                    item.classList.toggle('active', item.dataset.category === category);
                });

                markers.forEach((marker) => {
                    const isVisible = shouldShowMarkers && (category === 'all' || marker.gymmapsCategory === category);
                    marker.setVisible(isVisible);
                });

                if (clustererInstance) {
                    const visibleMarkers = markers.filter((marker) => marker.getVisible());
                    clustererInstance.clearMarkers();
                    if (visibleMarkers.length > 1) {
                        clustererInstance.addMarkers(visibleMarkers);
                    }
                }

                fitVisibleBounds();
            };

            legendItems.forEach((item) => {
                item.addEventListener('click', () => {
                    const category = item.dataset.category || 'all';
                    applyLegendFilter(category);
                });
            });

            const initialLegend = legendItems.find((item) => item.dataset.category === activeCategory);
            if (initialLegend) {
                initialLegend.classList.add('active');
            }

            if (!shouldShowMarkers) {
                markers.forEach((marker) => marker.setVisible(false));
                legendItems.forEach((item) => item.classList.remove('active'));
            }
            } catch (error) {
                loadGymmapLeafletAssetsAndInit();
            }
        }

        window.setTimeout(() => {
            if (!window.google || !window.google.maps) {
                loadGymmapLeafletAssetsAndInit();
            }
        }, 4500);
    </script>
    <script src="https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js"></script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key={{ $googleMapsKey }}&callback=initGymmapResultsMap" onerror="loadGymmapLeafletAssetsAndInit()"></script>
@else
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        (function initGymmapLeafletFallback() {
            const hasSearchCenter = {{ $hasSearchCenter ? 'true' : 'false' }};
            const hasLocationQuery = {{ $query !== '' ? 'true' : 'false' }};
            const center = [{{ $mapCenterLat }}, {{ $mapCenterLng }}];
            const locations = @json($mapLocations);
            const escapeHtml = (value) =>
                String(value ?? '')
                    .replaceAll('&', '&amp;')
                    .replaceAll('<', '&lt;')
                    .replaceAll('>', '&gt;')
                    .replaceAll('"', '&quot;')
                    .replaceAll("'", '&#039;');
            const map = L.map('results-map', { zoomControl: true }).setView(center, {{ $mapZoom }});

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; OpenStreetMap-bijdragers',
            }).addTo(map);

            if (hasSearchCenter) {
                L.circleMarker(center, {
                    radius: 8,
                    color: '#ffffff',
                    weight: 2,
                    fillColor: '#174f86',
                    fillOpacity: 1,
                }).addTo(map).bindPopup('Zoekcentrum');
            }

            const markerRefs = [];

            const getCategory = (sports) => {
                const joined = Array.isArray(sports) ? sports.join(' ').toLowerCase() : '';
                if (joined.includes('crossfit')) return 'crossfit';
                if (joined.includes('bok')) return 'boxing';
                if (joined.includes('yoga')) return 'yoga';
                if (joined.includes('fitness') || joined.includes('kracht')) return 'fitness';
                return 'other';
            };

            const categoryMeta = {
                fitness: { emoji: '🏋️' },
                boxing: { emoji: '🥊' },
                yoga: { emoji: '🧘' },
                crossfit: { emoji: '🏋️‍♂️' },
                other: { emoji: '📍' },
            };

            locations.forEach((location) => {
                const category = getCategory(location.sports);
                const icon = L.divIcon({
                    html: `<div style="width:34px;height:34px;border-radius:50%;background:#0f5f8b;border:2px solid #fff;color:#fff;display:flex;align-items:center;justify-content:center;font-size:18px;box-shadow:0 2px 8px rgba(0,0,0,.3);">${(categoryMeta[category] || categoryMeta.other).emoji}</div>`,
                    className: '',
                    iconSize: [34, 34],
                    iconAnchor: [17, 17],
                });

                const marker = L.marker([location.lat, location.lng], { icon });
                if (hasLocationQuery) {
                    marker.addTo(map);
                }
                marker.gymmapsCategory = category;
                const distanceLine = location.distance !== null ? `<br>Afstand: ${location.distance.toFixed(1)} km` : '';
                const photoCandidate = location.logo_url || location.photo_url;
                const photoLine = photoCandidate
                    ? `<br><img src="${escapeHtml(photoCandidate)}" alt="${escapeHtml(location.name)}" style="width:160px;height:90px;object-fit:cover;border-radius:6px;margin-top:6px;" onerror="this.onerror=null;this.src='${escapeHtml(location.fallback_photo_url || '')}'">`
                    : '<br><span style="display:inline-block;margin-top:8px;color:#5c6f82;">Geen foto beschikbaar</span>';
                const detailLink = location.detail_url
                    ? `<br><a href="${escapeHtml(location.detail_url)}" style="display:inline-block;margin-top:8px;padding:7px 10px;background:#ff6a3d;color:#fff;text-decoration:none;border-radius:7px;">Bekijk sportschool</a>`
                    : '';
                marker.bindPopup(`<strong>${escapeHtml(location.name)}</strong><br>${escapeHtml(location.address)}${distanceLine}${photoLine}${detailLink}`);
                markerRefs.push(marker);
            });

            const legendItems = Array.from(document.querySelectorAll('.map-legend-item'));

            const fitVisibleBounds = () => {
                const visible = markerRefs.filter((marker) => map.hasLayer(marker));
                if (!visible.length) {
                    return;
                }
                const bounds = L.latLngBounds(visible.map((marker) => marker.getLatLng()));
                map.fitBounds(bounds, { padding: [28, 28] });
            };

            const applyLegendFilter = (category) => {
                legendItems.forEach((item) => {
                    item.classList.toggle('active', item.dataset.category === category);
                });

                markerRefs.forEach((marker) => {
                    const match = hasLocationQuery && (category === 'all' || marker.gymmapsCategory === category);
                    if (match && !map.hasLayer(marker)) {
                        marker.addTo(map);
                    }
                    if (!match && map.hasLayer(marker)) {
                        map.removeLayer(marker);
                    }
                });

                fitVisibleBounds();
            };

            legendItems.forEach((item) => {
                item.addEventListener('click', () => {
                    applyLegendFilter(item.dataset.category || 'all');
                });
            });

            applyLegendFilter(hasLocationQuery ? 'all' : 'none');
        })();
    </script>
@endif
@include('partials.site-footer')
</body>
</html>
