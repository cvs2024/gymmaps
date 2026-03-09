<!doctype html>
<html lang="nl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Veelgestelde vragen</title>
    <link rel="icon" type="image/png" href="{{ asset('logo/gymmaps-logo.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('logo/gymmaps-logo.png') }}">
    <meta name="description" content="Veelgestelde vragen over GymMaps.nl: sportscholen zoeken, gymbuddy, personal trainers en bedrijfsprofielen.">
    <style>
        :root {
            --bg: #f4f8fc;
            --card: #ffffff;
            --ink: #10263d;
            --muted: #4f6276;
            --line: #d6e2ee;
            --brand: #95c11f;
            --brand-dark: #7ea61a;
            --blue: #123f6a;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: "Segoe UI", Roboto, sans-serif;
            background:
                radial-gradient(980px 440px at 10% -12%, rgba(23, 84, 140, 0.18), transparent 60%),
                radial-gradient(760px 380px at 90% 0%, rgba(149, 193, 31, 0.16), transparent 58%),
                var(--bg);
            color: var(--ink);
        }

        .container {
            max-width: 1060px;
            margin: 0 auto;
            padding: 30px 16px 44px;
        }

        .hero {
            background: linear-gradient(135deg, #0f3f73, #1f5e9a);
            color: #fff;
            border-radius: 18px;
            padding: 24px 22px;
            box-shadow: 0 16px 32px rgba(9, 40, 70, 0.25);
            margin-bottom: 16px;
        }

        .hero h1 { margin: 0 0 8px; font-size: clamp(1.6rem, 3vw, 2.2rem); }
        .hero p { margin: 0; color: rgba(255, 255, 255, 0.92); line-height: 1.55; }

        .section {
            background: var(--card);
            border: 1px solid var(--line);
            border-radius: 16px;
            padding: 18px;
            margin-bottom: 14px;
        }

        .section h2 {
            margin: 0 0 12px;
            color: var(--blue);
            font-size: 1.3rem;
        }

        .qa {
            border: 1px solid #e1eaf3;
            border-radius: 12px;
            background: #fbfdff;
            margin-bottom: 16px;
            overflow: hidden;
        }

        .qa:last-child { margin-bottom: 0; }

        .qa summary {
            list-style: none;
            cursor: pointer;
            padding: 14px 44px 14px 14px;
            margin: 0;
            font-size: 1.03rem;
            color: #0f3355;
            font-weight: 600;
            position: relative;
            user-select: none;
            transition: background-color 0.2s ease;
        }

        .qa summary::-webkit-details-marker { display: none; }

        .qa summary::after {
            content: "+";
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 1.25rem;
            color: #4f6781;
        }

        .qa[open] summary {
            background: #eef5fb;
            border-bottom: 1px solid #d9e5f0;
        }

        .qa[open] summary::after { content: "−"; }

        .qa-content {
            padding: 12px 14px 14px;
        }

        .qa p {
            margin: 0 0 8px;
            color: var(--muted);
            line-height: 1.55;
        }

        .qa p:last-child { margin-bottom: 0; }

        ul {
            margin: 0 0 8px 18px;
            color: var(--muted);
            line-height: 1.55;
            padding: 0;
        }

        li { margin-bottom: 5px; }

        .toolbar {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 14px;
        }

        .btn {
            display: inline-block;
            background: var(--brand);
            color: #fff;
            text-decoration: none;
            border-radius: 10px;
            padding: 10px 14px;
            font-weight: 600;
        }

        .btn:hover { background: var(--brand-dark); }
    </style>
</head>
<body>
@include('partials.site-header')
<div class="container">
    <section class="hero">
        <h1>Veelgestelde vragen</h1>
        <p>Hier vind je antwoorden op de meest gestelde vragen over zoeken, gymbuddy oproepen, personal trainers en bedrijfsprofielen op GymMaps.nl.</p>
    </section>

    <section class="section">
        <h2>GymMaps.nl</h2>

        <details class="qa">
            <summary>Wat is GymMaps.nl?</summary>
            <div class="qa-content">
            <p>GymMaps.nl is een platform waarop je eenvoudig sportscholen, sportactiviteiten en personal trainers in Nederland kunt vinden. Via onze kaart en zoekfunctie kun je snel zien welke sportlocaties er bij jou in de buurt zijn.</p>
            <p>Je kunt zoeken op stad, regio of postcode en direct doorklikken naar het bedrijfsprofiel van de sportlocatie voor meer informatie.</p>
        </div>
        </details>

        <details class="qa">
            <summary>Hoe vind ik een sportschool bij mij in de buurt?</summary>
            <div class="qa-content">
            <p>Via de zoekfunctie op GymMaps.nl kun je eenvoudig een sportschool bij jou in de buurt vinden. Je kunt zoeken op:</p>
            <ul>
                <li>stad</li>
                <li>postcode</li>
                <li>regio</li>
            </ul>
            <p>Op de kaart zie je direct welke sportlocaties er beschikbaar zijn en onder de kaart vind je een overzicht van alle sportactiviteiten in de omgeving.</p>
        </div>
        </details>

        <details class="qa">
            <summary>Wanneer is GymMaps.nl handig om te gebruiken?</summary>
            <div class="qa-content">
            <p>GymMaps.nl is vooral handig wanneer je:</p>
            <ul>
                <li>op zoek bent naar een sportschool of sportactiviteit bij jou in de buurt</li>
                <li>op vakantie bent en tijdelijk een sportschool zoekt</li>
                <li>net verhuisd bent naar een andere stad</li>
                <li>tijdelijk ergens anders woont of werkt</li>
                <li>nieuwe sportactiviteiten wilt ontdekken</li>
            </ul>
            <p>Met onze kaart zie je direct welke sportlocaties er in jouw omgeving beschikbaar zijn.</p>
        </div>
        </details>

        <details class="qa">
            <summary>Welke sportactiviteiten kan ik vinden op GymMaps.nl?</summary>
            <div class="qa-content">
            <p>Op GymMaps.nl kun je verschillende soorten sportlocaties vinden, zoals:</p>
            <ul>
                <li>sportscholen / fitness</li>
                <li>personal trainers</li>
                <li>CrossFit boxen</li>
                <li>yoga studios</li>
                <li>bootcamp trainingen</li>
                <li>boks- en vechtsportscholen</li>
                <li>groepslessen</li>
                <li>andere sportactiviteiten</li>
            </ul>
            <p>Ons doel is om een zo compleet mogelijk overzicht van sportlocaties in Nederland te bieden.</p>
        </div>
        </details>
    </section>

    <section class="section">
        <h2>Gymbuddy zoeken</h2>

        <details class="qa">
            <summary>Waar kan ik een gymbuddy vinden?</summary>
            <div class="qa-content">
            <p>Op GymMaps.nl kun je via de pagina Gymbuddy zoeken eenvoudig een sportmaatje vinden. Hier plaatsen sporters oproepen om samen te trainen.</p>
            <p>Dit kan bijvoorbeeld handig zijn wanneer je:</p>
            <ul>
                <li>samen met iemand wilt sporten voor extra motivatie</li>
                <li>nieuw bent in een stad</li>
                <li>nieuwe mensen wilt leren kennen die ook sporten</li>
            </ul>
        </div>
        </details>

        <details class="qa">
            <summary>Hoe werkt het plaatsen van een gymbuddy oproep?</summary>
            <div class="qa-content">
            <p>Je kunt eenvoudig een oproep plaatsen waarin je aangeeft:</p>
            <ul>
                <li>welke sport je wilt doen</li>
                <li>in welke stad of regio je sport</li>
                <li>hoe vaak per week je wilt trainen</li>
                <li>eventuele voorkeuren voor een gymbuddy</li>
                <li>wat je zoekt in een trainingsmaatje</li>
            </ul>
            <p>Andere sporters kunnen vervolgens reageren op jouw oproep.</p>
        </div>
        </details>

        <details class="qa">
            <summary>Waarom trainen met een gymbuddy?</summary>
            <div class="qa-content">
            <p>Samen sporten kan helpen om:</p>
            <ul>
                <li>gemotiveerd te blijven</li>
                <li>regelmatiger te trainen</li>
                <li>nieuwe mensen te leren kennen</li>
                <li>sport leuker te maken</li>
            </ul>
        </div>
        </details>
    </section>

    <section class="section">
        <h2>Bedrijfsprofiel voor sportscholen</h2>

        <details class="qa">
            <summary>Kan een sportschool zich aanmelden op GymMaps.nl?</summary>
            <div class="qa-content">
            <p>Ja. Sportscholen en andere sportlocaties kunnen een bedrijfsprofiel aanmaken op GymMaps.nl.</p>
            <p>Via dit profiel kunnen zij zelf hun informatie beheren en hun sportlocatie zichtbaar maken voor sporters die zoeken in hun regio.</p>
        </div>
        </details>

        <details class="qa">
            <summary>Wat kan een sportschool aanpassen in het bedrijfsprofiel?</summary>
            <div class="qa-content">
            <p>Via het bedrijfsprofiel kan een sportlocatie onder andere:</p>
            <ul>
                <li>een beschrijving van de sportschool toevoegen</li>
                <li>foto’s van de locatie uploaden</li>
                <li>contactgegevens invullen</li>
                <li>een link naar de eigen website plaatsen</li>
                <li>openingstijden vermelden</li>
                <li>extra informatie toevoegen voor bezoekers</li>
            </ul>
        </div>
        </details>

        <details class="qa">
            <summary>Waarom is het belangrijk om foto's van de sportschool toe te voegen?</summary>
            <div class="qa-content">
            <p>Foto’s geven bezoekers een goed beeld van:</p>
            <ul>
                <li>de trainingsruimte</li>
                <li>de apparatuur</li>
                <li>de sfeer van de sportschool</li>
            </ul>
            <p>Bedrijfsprofielen met duidelijke foto's worden vaak vaker bekeken door sporters.</p>
        </div>
        </details>

        <details class="qa">
            <summary>Waarom is het verstandig om de FAQ op het bedrijfsprofiel in te vullen?</summary>
            <div class="qa-content">
            <p>Bij elk bedrijfsprofiel kunnen standaard veelgestelde vragen worden ingevuld.</p>
            <p>Dit helpt sporters om snel antwoord te krijgen op vragen zoals:</p>
            <ul>
                <li>zijn er groepslessen?</li>
                <li>is er parkeergelegenheid?</li>
                <li>kan ik een proefles volgen?</li>
            </ul>
            <p>Daarnaast helpt het invullen van deze vragen ook om beter gevonden te worden in zoekmachines zoals Google.</p>
        </div>
        </details>
    </section>

    <section class="section">
        <h2>Personal trainers</h2>

        <details class="qa">
            <summary>Hoe vind ik een personal trainer in mijn stad?</summary>
            <div class="qa-content">
            <p>Via GymMaps.nl kun je eenvoudig zoeken naar personal trainers in jouw stad of regio. Je kunt trainers vinden via de kaart of via de pagina met personal trainers.</p>
            <p>Daarnaast kun je ook een oproep plaatsen wanneer je op zoek bent naar een personal trainer.</p>
        </div>
        </details>

        <details class="qa">
            <summary>Kunnen personal trainers ook een profiel aanmaken?</summary>
            <div class="qa-content">
            <p>Ja. Ook personal trainers kunnen een bedrijfsprofiel aanmaken op GymMaps.nl.</p>
            <p>Zo kunnen zij hun diensten presenteren aan sporters die op zoek zijn naar persoonlijke begeleiding.</p>
        </div>
        </details>

        <details class="qa">
            <summary>Wat is de pagina "Personal trainer zoeken"?</summary>
            <div class="qa-content">
            <p>Op de pagina Personal trainer zoeken kunnen sporters een oproep plaatsen wanneer zij op zoek zijn naar een personal trainer.</p>
            <p>Dit maakt het voor trainers makkelijker om nieuwe klanten te vinden.</p>
        </div>
        </details>

        <details class="qa">
            <summary>Wat moet ik vermelden wanneer ik een personal trainer zoek?</summary>
            <div class="qa-content">
            <p>Wanneer je een oproep plaatst voor een personal trainer is het handig om duidelijk te vermelden:</p>
            <ul>
                <li>in welke stad of regio je woont</li>
                <li>waar je wilt trainen (sportschool, buiten of thuis)</li>
                <li>hoe vaak per week je wilt trainen</li>
                <li>wat je doel is (bijvoorbeeld afvallen, spieropbouw of conditie)</li>
                <li>wat voor type trainer je zoekt</li>
            </ul>
            <p>Hoe duidelijker je oproep, hoe groter de kans dat je een geschikte trainer vindt.</p>
        </div>
        </details>
    </section>

    <div class="toolbar">
        <a class="btn" href="{{ route('home') }}">Terug naar homepage</a>
    </div>
</div>
<script>
    const faqItems = Array.from(document.querySelectorAll('.qa'));
    faqItems.forEach((item) => {
        item.addEventListener('toggle', () => {
            if (!item.open) {
                return;
            }

            faqItems.forEach((other) => {
                if (other !== item) {
                    other.open = false;
                }
            });
        });
    });
</script>
</body>
</html>
