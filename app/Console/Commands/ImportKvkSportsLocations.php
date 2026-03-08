<?php

namespace App\Console\Commands;

use App\Models\RawImport;
use App\Models\Sport;
use App\Services\GoogleGeocodingService;
use App\Services\KvkApiService;
use App\Services\LocationUpsertService;
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
                            {--pages=3 : Aantal pagina\'s per zoekterm}
                            {--per-page=100 : Aantal resultaten per pagina}
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
        LocationUpsertService $locationUpsertService
    ): int
    {
        $queries = $this->option('query');
        if (!is_array($queries) || $queries === []) {
            $queries = [
                'sportschool',
                'fitness',
                'yoga studio',
                'crossfit',
                'boksschool',
                'tennis vereniging',
                'squash',
            ];
        }

        $startPage = max(1, (int) $this->option('page'));
        $pages = max(1, (int) $this->option('pages'));
        $perPage = max(1, min(100, (int) $this->option('per-page')));
        $dryRun = (bool) $this->option('dry-run');

        $this->ensureSportsExist();

        $rawCount = 0;
        $upsertedCount = 0;
        $skippedCount = 0;

        foreach ($queries as $query) {
            for ($page = $startPage; $page < ($startPage + $pages); $page++) {
                $this->line("Ophalen: query=\"{$query}\", pagina={$page}");

                try {
                    $payload = $kvkApiService->search([
                        'handelsnaam' => $query,
                        'pagina' => $page,
                        'aantal' => $perPage,
                    ]);
                } catch (\Throwable $e) {
                    $this->warn("KVK call mislukt voor query '{$query}' pagina {$page}: {$e->getMessage()}");
                    continue;
                }

                $records = $this->extractRecords($payload);
                if ($records === []) {
                    $this->line('Geen records op deze pagina.');
                    continue;
                }

                foreach ($records as $record) {
                    $rawImport = $this->storeRawImport($record);
                    $rawCount++;

                    if ($dryRun) {
                        continue;
                    }

                    try {
                        $normalized = $this->normalizeRecord($record, $query, $geocodingService);

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
            ['name' => 'Yoga', 'slug' => 'yoga'],
            ['name' => 'Boksen', 'slug' => 'boksen'],
            ['name' => 'CrossFit', 'slug' => 'crossfit'],
            ['name' => 'Tennis', 'slug' => 'tennis'],
            ['name' => 'Squash', 'slug' => 'squash'],
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
        GoogleGeocodingService $geocodingService
    ): ?array {
        $name = $this->firstString($record, [
            'naam',
            'handelsnaam',
            'organisatie.naam',
            'bedrijf.naam',
        ]);

        $street = $this->firstString($record, [
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
            'adres.postcode',
            'postcode',
            'bezoekadres.postcode',
        ]);
        $city = $this->firstString($record, [
            'adres.plaats',
            'plaats',
            'bezoekadres.plaats',
            'woonplaats',
        ]);

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
            'kvkNummer',
            'kvknummer',
            'kvk_nummer',
        ]);

        $textForClassification = mb_strtolower(
            implode(' ', [
                $query,
                $name,
                (string) ($record['omschrijving'] ?? ''),
                (string) ($record['activiteiten'] ?? ''),
                (string) ($record['sbiOmschrijving'] ?? ''),
            ])
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
            'photo_url' => null,
            'source' => 'kvk',
            'external_id' => $externalId,
            'sport_slugs' => $this->mapSports($textForClassification),
        ];
    }

    private function mapSports(string $text): array
    {
        $map = [
            'fitness' => ['fitness', 'sportschool', 'gym'],
            'yoga' => ['yoga', 'pilates'],
            'boksen' => ['boksen', 'boks', 'kickbok'],
            'crossfit' => ['crossfit'],
            'tennis' => ['tennis'],
            'squash' => ['squash'],
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
}
