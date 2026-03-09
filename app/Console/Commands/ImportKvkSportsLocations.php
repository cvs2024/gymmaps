<?php

namespace App\Console\Commands;

use App\Models\RawImport;
use App\Models\Sport;
use App\Services\GoogleGeocodingService;
use App\Services\GooglePlacesPhotoService;
use App\Services\KvkApiService;
use App\Services\LocationUpsertService;
use Illuminate\Http\Client\RequestException;
use Illuminate\Console\Command;
use Illuminate\Support\Arr;

class ImportKvkSportsLocations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gymmap:import-kvk-sports
                            {--query=* : Zoektermen voor KVK, mag meerdere keren}
                            {--page=1 : Startpagina}
                            {--pages=0 : Aantal pagina\'s per zoekterm (0 = doorlopen tot lege pagina)}
                            {--per-page=100 : Aantal resultaten per pagina}
                            {--max-pages=250 : Veiligheidslimiet bij pages=0}
                            {--max-failures=12 : Stop na dit aantal opeenvolgende API-fouten per zoekterm}
                            {--dry-run : Alleen ophalen en in raw_imports zetten}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importeer sportlocaties vanuit KVK, met dedupe en geocoding.';

    /**
     * Execute the console command.
     */
    public function handle(
        KvkApiService $kvkApiService,
        GoogleGeocodingService $geocodingService,
        GooglePlacesPhotoService $photoService,
        LocationUpsertService $locationUpsertService
    ): int
    {
        $queries = $this->option('query');
        if (!is_array($queries) || $queries === []) {
            $queries = [
                'sport',
                'sportschool',
                'fitness',
                'gym',
                'personal training',
                'yoga studio',
                'crossfit',
                'boksschool',
                'kickboksen',
                'tennis vereniging',
                'squash',
                'sportvereniging',
                'sportclub',
            ];
        }

        $startPage = max(1, (int) $this->option('page'));
        $pages = max(0, (int) $this->option('pages'));
        $perPage = max(1, min(100, (int) $this->option('per-page')));
        $maxPages = max(1, (int) $this->option('max-pages'));
        $maxFailures = max(1, (int) $this->option('max-failures'));
        $dryRun = (bool) $this->option('dry-run');

        $this->ensureSportsExist();

        $rawCount = 0;
        $upsertedCount = 0;
        $skippedCount = 0;

        foreach ($queries as $query) {
            $page = $startPage;
            $processedPages = 0;
            $consecutiveFailures = 0;

            while (true) {
                if ($pages > 0 && $processedPages >= $pages) {
                    break;
                }

                if ($pages === 0 && $processedPages >= $maxPages) {
                    $this->warn("Stop voor query \"{$query}\": max-pages ({$maxPages}) bereikt.");
                    break;
                }

                $this->line("Ophalen: query=\"{$query}\", pagina={$page}");

                try {
                    $payload = $kvkApiService->search([
                        'naam' => $query,
                        'pagina' => $page,
                        'resultatenPerPagina' => $perPage,
                    ]);
                    $consecutiveFailures = 0;
                } catch (\Throwable $e) {
                    if ($this->isNoResultsError($e)) {
                        $this->line("Geen extra resultaten voor query \"{$query}\" vanaf pagina {$page}.");
                        break;
                    }

                    $this->warn("KVK call mislukt voor query '{$query}' pagina {$page}: {$e->getMessage()}");
                    $consecutiveFailures++;
                    if ($consecutiveFailures >= $maxFailures) {
                        $this->warn("Stop voor query \"{$query}\": {$consecutiveFailures} opeenvolgende API-fouten.");
                        break;
                    }
                    usleep(350000);
                    $page++;
                    $processedPages++;
                    continue;
                }

                $records = $this->extractRecords($payload);
                if ($records === []) {
                    $this->line('Geen records op deze pagina.');
                    if ($pages === 0) {
                        break;
                    }

                    $page++;
                    $processedPages++;
                    continue;
                }

                foreach ($records as $record) {
                    $rawImport = $this->storeRawImport($record);
                    $rawCount++;

                    if ($dryRun) {
                        continue;
                    }

                    try {
                        $normalized = $this->normalizeRecord($record, $query, $geocodingService, $kvkApiService, $photoService);

                        if ($normalized === null) {
                            $rawImport->update([
                                'status' => 'skipped',
                                'error_message' => 'Onvoldoende gegevens om locatie aan te maken.',
                                'processed_at' => now(),
                            ]);
                            $skippedCount++;
                            continue;
                        }

                        $locationUpsertService->upsert($normalized, $normalized['sport_slugs']);

                        $rawImport->update([
                            'status' => 'processed',
                            'error_message' => null,
                            'processed_at' => now(),
                        ]);
                        $upsertedCount++;
                    } catch (\Throwable $e) {
                        $rawImport->update([
                            'status' => 'failed',
                            'error_message' => mb_substr($e->getMessage(), 0, 1000),
                            'processed_at' => now(),
                        ]);
                        $skippedCount++;
                    }
                }

                $page++;
                $processedPages++;
            }
        }

        $this->newLine();
        $this->info("Raw imports opgeslagen: {$rawCount}");
        $this->info("Locaties geupsert: {$upsertedCount}");
        $this->info("Overgeslagen/mislukt: {$skippedCount}");

        return self::SUCCESS;
    }

    private function ensureSportsExist(): void
    {
        $sports = [
            ['name' => 'Fitness', 'slug' => 'fitness'],
            ['name' => 'Personal trainer', 'slug' => 'personal-trainer'],
            ['name' => 'Yoga', 'slug' => 'yoga'],
            ['name' => 'Boksen', 'slug' => 'boksen'],
            ['name' => 'CrossFit', 'slug' => 'crossfit'],
            ['name' => 'Tennis', 'slug' => 'tennis'],
            ['name' => 'Squash', 'slug' => 'squash'],
            ['name' => 'Padel', 'slug' => 'padel'],
        ];

        foreach ($sports as $sport) {
            Sport::query()->updateOrCreate(
                ['slug' => $sport['slug']],
                ['name' => $sport['name']]
            );
        }
    }

    private function extractRecords(array $payload): array
    {
        $candidates = [
            Arr::get($payload, 'resultaten'),
            Arr::get($payload, 'results'),
            Arr::get($payload, 'data'),
            Arr::get($payload, '_embedded.basisprofielen'),
        ];

        foreach ($candidates as $records) {
            if (is_array($records) && $records !== []) {
                return array_values(array_filter($records, 'is_array'));
            }
        }

        return [];
    }

    private function storeRawImport(array $record): RawImport
    {
        $externalId = (string) ($this->firstString($record, [
            'kvkNummer',
            'kvknummer',
            'kvk_nummer',
            'nummer',
        ]) ?? '');

        $payloadJson = json_encode($record, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '{}';
        $hash = sha1('kvk|'.$externalId.'|'.$payloadJson);

        return RawImport::query()->updateOrCreate(
            ['import_hash' => $hash],
            [
                'source' => 'kvk',
                'external_id' => $externalId !== '' ? $externalId : null,
                'payload' => $record,
                'status' => 'pending',
                'error_message' => null,
                'processed_at' => null,
            ]
        );
    }

    private function normalizeRecord(
        array $record,
        string $query,
        GoogleGeocodingService $geocodingService,
        KvkApiService $kvkApiService,
        GooglePlacesPhotoService $photoService
    ): ?array {
        $detail = $this->resolveVestigingDetail($record, $kvkApiService);

        $name = $this->firstString($record, [
            'naam',
            'handelsnaam',
            'organisatie.naam',
            'bedrijf.naam',
        ]);
        if ($name === null) {
            $name = $this->firstString($detail, [
                'eersteHandelsnaam',
                'statutaireNaam',
            ]);
        }

        $street = $this->firstString($record, [
            'adres.binnenlandsAdres.straatnaam',
            'adres.straatnaam',
            'straatnaam',
            'straat',
            'bezoekadres.straatnaam',
        ]);
        $houseNumber = $this->firstString($record, [
            'adres.huisnummer',
            'huisnummer',
            'bezoekadres.huisnummer',
        ]);
        $houseNumberSuffix = $this->firstString($record, [
            'adres.huisletter',
            'adres.huisnummertoevoeging',
            'bezoekadres.huisletter',
            'bezoekadres.huisnummertoevoeging',
        ]);
        $postcode = $this->firstString($record, [
            'adres.binnenlandsAdres.postcode',
            'adres.postcode',
            'postcode',
            'bezoekadres.postcode',
        ]);
        $city = $this->firstString($record, [
            'adres.binnenlandsAdres.plaats',
            'adres.plaats',
            'plaats',
            'bezoekadres.plaats',
            'woonplaats',
        ]);

        if ($street === null) {
            $street = $this->firstString($detail, ['adressen.0.straatnaam', 'adressen.1.straatnaam']);
        }
        if ($houseNumber === null) {
            $houseNumber = $this->firstString($detail, ['adressen.0.huisnummer', 'adressen.1.huisnummer']);
        }
        if ($houseNumberSuffix === null) {
            $houseNumberSuffix = $this->firstString($detail, ['adressen.0.huisnummerToevoeging', 'adressen.1.huisnummerToevoeging']);
        }
        if ($postcode === null) {
            $postcode = $this->firstString($detail, ['adressen.0.postcode', 'adressen.1.postcode']);
        }
        if ($city === null) {
            $city = $this->firstString($detail, ['adressen.0.plaats', 'adressen.1.plaats']);
        }

        if ($name === null || $street === null || $postcode === null || $city === null) {
            return null;
        }

        $address = trim($street.' '.trim(($houseNumber ?? '').' '.($houseNumberSuffix ?? '')));

        $latitude = $this->firstFloat($record, [
            'adres.coordinaten.lat',
            'geo.lat',
            'latitude',
        ]);
        $longitude = $this->firstFloat($record, [
            'adres.coordinaten.lon',
            'adres.coordinaten.lng',
            'geo.lon',
            'geo.lng',
            'longitude',
        ]);

        if ($latitude === null || $longitude === null) {
            $latitude = $this->firstFloat($detail, [
                'adressen.0.coordinaten.lat',
                'adressen.1.coordinaten.lat',
            ]) ?? $latitude;
            $longitude = $this->firstFloat($detail, [
                'adressen.0.coordinaten.lon',
                'adressen.0.coordinaten.lng',
                'adressen.1.coordinaten.lon',
                'adressen.1.coordinaten.lng',
            ]) ?? $longitude;
        }

        if ($latitude === null || $longitude === null) {
            $geo = $geocodingService->geocode("{$address}, {$postcode} {$city}, Nederland");
            if ($geo) {
                $latitude = $geo['latitude'];
                $longitude = $geo['longitude'];
            }
        }

        if ($latitude === null || $longitude === null) {
            return null;
        }

        $phone = $this->firstString($record, [
            'telefoonnummer',
            'telefoon',
            'contact.telefoon',
        ]);
        $website = $this->firstString($record, [
            'website',
            'internetadres',
            'contact.website',
        ]);
        $externalId = $this->firstString($record, [
            'vestigingsnummer',
            'kvkNummer',
            'kvknummer',
            'kvk_nummer',
        ]);
        if ($externalId === null) {
            $externalId = $this->firstString($detail, ['vestigingsnummer', 'kvkNummer']);
        }

        $textForClassification = mb_strtolower(
            implode(' ', [
                $query,
                $name,
                (string) ($record['omschrijving'] ?? ''),
                (string) ($record['activiteiten'] ?? ''),
                (string) ($record['sbiOmschrijving'] ?? ''),
                $this->extractSbiText($detail),
            ])
        );

        $photoUrl = $photoService->findPhotoUrl(
            $name,
            $address,
            $postcode,
            $city,
            $latitude,
            $longitude
        );

        return [
            'name' => $name,
            'address' => $address,
            'postcode' => $postcode,
            'city' => $city,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'website' => $website,
            'phone' => $phone,
            'photo_url' => $photoUrl,
            'source' => 'kvk',
            'external_id' => $externalId,
            'sport_slugs' => $this->mapSports($textForClassification),
        ];
    }

    private function mapSports(string $text): array
    {
        $map = [
            'personal-trainer' => ['personal trainer', 'personaltraining', 'pt studio', 'pt-studio', 'coach'],
            'fitness' => ['fitness', 'sportschool', 'gym', 'krachttraining', 'kracht train'],
            'yoga' => ['yoga', 'pilates'],
            'boksen' => ['boksen', 'boks', 'kickbok', 'boxing', 'vechtsport'],
            'crossfit' => ['crossfit'],
            'tennis' => ['tennis'],
            'squash' => ['squash'],
            'padel' => ['padel'],
        ];

        $found = [];
        foreach ($map as $slug => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($text, $keyword)) {
                    $found[] = $slug;
                    break;
                }
            }
        }

        return $found !== [] ? array_values(array_unique($found)) : ['fitness'];
    }

    private function extractSbiText(array $detail): string
    {
        $items = Arr::get($detail, 'sbiActiviteiten', []);
        if (!is_array($items) || $items === []) {
            return '';
        }

        return collect($items)
            ->map(function ($item) {
                if (!is_array($item)) {
                    return '';
                }

                return trim((string) ($item['sbiOmschrijving'] ?? ''));
            })
            ->filter()
            ->implode(' ');
    }

    private function resolveVestigingDetail(array $record, KvkApiService $kvkApiService): array
    {
        $vestigingsnummer = $this->firstString($record, ['vestigingsnummer', 'vestigingNummer']);
        if ($vestigingsnummer === null) {
            return [];
        }

        try {
            return $kvkApiService->getVestigingsprofiel($vestigingsnummer);
        } catch (\Throwable) {
            return [];
        }
    }

    private function firstString(array $record, array $paths): ?string
    {
        foreach ($paths as $path) {
            $value = Arr::get($record, $path);
            if (is_scalar($value)) {
                $string = trim((string) $value);
                if ($string !== '') {
                    return $string;
                }
            }
        }

        return null;
    }

    private function firstFloat(array $record, array $paths): ?float
    {
        foreach ($paths as $path) {
            $value = Arr::get($record, $path);
            if (is_numeric($value)) {
                return (float) $value;
            }
        }

        return null;
    }

    private function isNoResultsError(\Throwable $e): bool
    {
        if (!$e instanceof RequestException) {
            return false;
        }

        $response = $e->response;
        if (!$response || $response->status() !== 404) {
            return false;
        }

        $payload = $response->json();
        if (!is_array($payload)) {
            return false;
        }

        $errors = Arr::get($payload, 'fout', []);
        if (!is_array($errors)) {
            return false;
        }

        foreach ($errors as $error) {
            if (!is_array($error)) {
                continue;
            }

            $code = (string) ($error['code'] ?? '');
            if ($code === 'IPD5200') {
                return true;
            }
        }

        return false;
    }
}
