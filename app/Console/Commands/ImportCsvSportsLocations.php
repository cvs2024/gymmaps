<?php

namespace App\Console\Commands;

use App\Models\Sport;
use App\Services\GoogleGeocodingService;
use App\Services\LocationUpsertService;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class ImportCsvSportsLocations extends Command
{
    protected $signature = 'gymmap:import-csv-sports
                            {file : Pad naar CSV bestand}
                            {--delimiter=, : CSV delimiter}
                            {--enclosure=" : CSV enclosure}
                            {--source=csv-temp : Source label in locations.source}
                            {--default-sport=fitness : Default sportslug wanneer sports leeg is}
                            {--dry-run : Alleen valideren, geen writes}';

    protected $description = 'Tijdelijke CSV-import voor sportscholen (fallback als KVK API niet werkt).';

    public function handle(
        GoogleGeocodingService $geocodingService,
        LocationUpsertService $locationUpsertService
    ): int {
        $file = (string) $this->argument('file');
        if (!is_file($file) || !is_readable($file)) {
            $this->error("CSV niet gevonden of niet leesbaar: {$file}");
            return self::FAILURE;
        }

        $delimiter = (string) $this->option('delimiter');
        $enclosure = (string) $this->option('enclosure');
        $source = trim((string) $this->option('source'));
        $defaultSport = trim((string) $this->option('default-sport'));
        $dryRun = (bool) $this->option('dry-run');

        if ($delimiter === '') {
            $delimiter = ',';
        }
        if ($enclosure === '') {
            $enclosure = '"';
        }
        if ($source === '') {
            $source = 'csv-temp';
        }
        if ($defaultSport === '') {
            $defaultSport = 'fitness';
        }

        $this->ensureDefaultSportsExist();

        $handle = fopen($file, 'rb');
        if ($handle === false) {
            $this->error("Kon CSV niet openen: {$file}");
            return self::FAILURE;
        }

        $headers = fgetcsv($handle, 0, $delimiter, $enclosure);
        if (!is_array($headers) || $headers === []) {
            fclose($handle);
            $this->error('CSV heeft geen geldige header rij.');
            return self::FAILURE;
        }

        $normalizedHeaders = array_map(fn ($value) => $this->normalizeHeader((string) $value), $headers);

        $processed = 0;
        $upserted = 0;
        $skipped = 0;

        while (($row = fgetcsv($handle, 0, $delimiter, $enclosure)) !== false) {
            $processed++;
            $record = $this->combineRecord($normalizedHeaders, $row);

            $name = $this->firstRecordValue($record, ['name', 'naam', 'handelsnaam']);
            $address = $this->firstRecordValue($record, ['address', 'adres', 'street']);
            $postcode = $this->firstRecordValue($record, ['postcode', 'postalcode', 'zip']);
            $city = $this->firstRecordValue($record, ['city', 'plaats', 'town']);
            $phone = $this->firstRecordValue($record, ['phone', 'telefoon', 'telefoonnummer']);
            $website = $this->firstRecordValue($record, ['website', 'url', 'internetadres']);
            $externalId = $this->firstRecordValue($record, ['external_id', 'externalid', 'kvk', 'kvknummer']);
            $sportsRaw = $this->firstRecordValue($record, ['sports', 'sport', 'sporten', 'sport_slugs']);

            if ($name === null || $address === null || $postcode === null || $city === null) {
                $skipped++;
                $this->warn("Rij {$processed} overgeslagen: verplichte velden ontbreken (name/address/postcode/city).");
                continue;
            }

            $latitude = $this->toFloat($this->firstRecordValue($record, ['latitude', 'lat']));
            $longitude = $this->toFloat($this->firstRecordValue($record, ['longitude', 'lng', 'lon']));

            if ($latitude === null || $longitude === null) {
                $geo = $geocodingService->geocode("{$address}, {$postcode} {$city}, Nederland");
                if ($geo) {
                    $latitude = $geo['latitude'];
                    $longitude = $geo['longitude'];
                }
            }

            if ($latitude === null || $longitude === null) {
                $skipped++;
                $this->warn("Rij {$processed} overgeslagen: geen coördinaten en geocoding mislukt.");
                continue;
            }

            $sportSlugs = $this->extractSportSlugs($sportsRaw, $defaultSport);

            if ($dryRun) {
                $upserted++;
                continue;
            }

            $locationUpsertService->upsert([
                'name' => $name,
                'address' => $address,
                'postcode' => $postcode,
                'city' => $city,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'website' => $website,
                'phone' => $phone,
                'photo_url' => null,
                'source' => $source,
                'external_id' => $externalId ?: sha1($name.'|'.$address.'|'.$postcode.'|'.$city),
            ], $sportSlugs);

            $upserted++;
        }

        fclose($handle);

        $this->newLine();
        $this->info("Rijen verwerkt: {$processed}");
        $this->info("Rijen geupsert: {$upserted}");
        $this->info("Rijen overgeslagen: {$skipped}");

        return self::SUCCESS;
    }

    private function normalizeHeader(string $value): string
    {
        $value = trim(Str::lower($value));
        $value = str_replace([' ', '-', '.'], '_', $value);

        return $value;
    }

    private function combineRecord(array $headers, array $row): array
    {
        $record = [];

        foreach ($headers as $index => $header) {
            $record[$header] = isset($row[$index]) ? trim((string) $row[$index]) : null;
        }

        return $record;
    }

    private function firstRecordValue(array $record, array $keys): ?string
    {
        foreach ($keys as $key) {
            if (!array_key_exists($key, $record)) {
                continue;
            }

            $value = trim((string) ($record[$key] ?? ''));
            if ($value !== '') {
                return $value;
            }
        }

        return null;
    }

    private function toFloat(?string $value): ?float
    {
        if ($value === null) {
            return null;
        }

        $normalized = str_replace(',', '.', trim($value));
        if (!is_numeric($normalized)) {
            return null;
        }

        return (float) $normalized;
    }

    private function extractSportSlugs(?string $rawValue, string $defaultSport): array
    {
        if ($rawValue === null || trim($rawValue) === '') {
            return [$defaultSport];
        }

        $parts = preg_split('/[,\|;\/]+/', $rawValue) ?: [];
        $slugs = collect($parts)
            ->map(fn ($part) => Str::slug(trim((string) $part)))
            ->filter()
            ->values()
            ->all();

        if ($slugs === []) {
            return [$defaultSport];
        }

        return array_values(array_unique($slugs));
    }

    private function ensureDefaultSportsExist(): void
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
}
