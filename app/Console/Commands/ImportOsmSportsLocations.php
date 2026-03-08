<?php

namespace App\Console\Commands;

use App\Models\RawImport;
use App\Models\Sport;
use App\Services\LocationUpsertService;
use App\Services\OverpassApiService;
use Illuminate\Console\Command;

class ImportOsmSportsLocations extends Command
{
    protected $signature = 'gymmap:import-osm-sports
                            {--limit=0 : Max aantal records (0 = geen limiet)}
                            {--dry-run : Alleen raw_imports vullen}';

    protected $description = 'Importeer sportlocaties in Nederland vanuit OpenStreetMap (Overpass).';

    public function handle(OverpassApiService $overpassApiService, LocationUpsertService $upsertService): int
    {
        $this->ensureSportsExist();

        $limit = max(0, (int) $this->option('limit'));
        $dryRun = (bool) $this->option('dry-run');

        $payload = $overpassApiService->fetchNetherlandsSports();
        $elements = $payload['elements'] ?? [];

        if (!is_array($elements) || $elements === []) {
            $this->warn('Geen OSM elementen ontvangen.');
            return self::SUCCESS;
        }

        $rawCount = 0;
        $upsertedCount = 0;
        $skippedCount = 0;

        foreach ($elements as $index => $element) {
            if ($limit > 0 && $index >= $limit) {
                break;
            }

            if (!is_array($element)) {
                continue;
            }

            $rawImport = $this->storeRawImport($element);
            $rawCount++;

            if ($dryRun) {
                continue;
            }

            $normalized = $this->normalizeElement($element);
            if ($normalized === null) {
                $rawImport->update([
                    'status' => 'skipped',
                    'error_message' => 'Onvoldoende data in OSM element.',
                    'processed_at' => now(),
                ]);
                $skippedCount++;
                continue;
            }

            try {
                $upsertService->upsert($normalized, $normalized['sport_slugs']);

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

        $this->newLine();
        $this->info("OSM raw imports opgeslagen: {$rawCount}");
        $this->info("OSM locaties geupsert: {$upsertedCount}");
        $this->info("OSM overgeslagen/mislukt: {$skippedCount}");

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

    private function storeRawImport(array $element): RawImport
    {
        $type = (string) ($element['type'] ?? 'unknown');
        $id = (string) ($element['id'] ?? 'unknown');
        $externalId = $type.':'.$id;
        $payloadJson = json_encode($element, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?: '{}';
        $hash = sha1('osm|'.$externalId.'|'.$payloadJson);

        return RawImport::query()->updateOrCreate(
            ['import_hash' => $hash],
            [
                'source' => 'osm',
                'external_id' => $externalId,
                'payload' => $element,
                'status' => 'pending',
                'error_message' => null,
                'processed_at' => null,
            ]
        );
    }

    private function normalizeElement(array $element): ?array
    {
        $tags = is_array($element['tags'] ?? null) ? $element['tags'] : [];

        $name = trim((string) ($tags['name'] ?? ''));
        if ($name === '') {
            return null;
        }

        $lat = $element['lat'] ?? ($element['center']['lat'] ?? null);
        $lon = $element['lon'] ?? ($element['center']['lon'] ?? null);

        if (!is_numeric($lat) || !is_numeric($lon)) {
            return null;
        }

        $street = trim((string) ($tags['addr:street'] ?? ''));
        $houseNumber = trim((string) ($tags['addr:housenumber'] ?? ''));
        $postcode = trim((string) ($tags['addr:postcode'] ?? ''));
        $city = trim((string) ($tags['addr:city'] ?? ($tags['addr:town'] ?? ($tags['addr:village'] ?? ''))));

        $address = trim($street.' '.$houseNumber);
        if ($address === '') {
            $address = 'Adres onbekend';
        }
        if ($postcode === '') {
            $postcode = 'Onbekend';
        }
        if ($city === '') {
            $city = 'Onbekend';
        }

        $sportText = mb_strtolower(
            implode(' ', [
                (string) ($tags['sport'] ?? ''),
                (string) ($tags['leisure'] ?? ''),
                (string) ($tags['amenity'] ?? ''),
                $name,
            ])
        );

        $sportSlugs = $this->mapSports($sportText);

        $type = (string) ($element['type'] ?? 'unknown');
        $id = (string) ($element['id'] ?? 'unknown');

        return [
            'name' => $name,
            'address' => $address,
            'postcode' => $postcode,
            'city' => $city,
            'latitude' => (float) $lat,
            'longitude' => (float) $lon,
            'website' => $tags['website'] ?? null,
            'phone' => $tags['phone'] ?? null,
            'photo_url' => null,
            'source' => 'osm',
            'external_id' => $type.':'.$id,
            'sport_slugs' => $sportSlugs,
        ];
    }

    private function mapSports(string $text): array
    {
        $map = [
            'fitness' => ['fitness', 'gym', 'fitness_centre'],
            'yoga' => ['yoga', 'pilates'],
            'boksen' => ['boxing', 'boksen', 'martial_arts', 'kickbox'],
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
}
