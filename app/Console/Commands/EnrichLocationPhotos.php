<?php

namespace App\Console\Commands;

use App\Models\Location;
use App\Services\GooglePlacesPhotoService;
use Illuminate\Console\Command;

class EnrichLocationPhotos extends Command
{
    protected $signature = 'gymmap:enrich-location-photos
                            {--source=kvk : Filter op source (leeg = alle sources)}
                            {--limit=500 : Max aantal records per run}
                            {--sleep-ms=120 : Wachttijd tussen requests in ms}
                            {--force : Ook records met bestaande photo_url opnieuw proberen}';

    protected $description = 'Verrijk locaties met Google Places foto en openingstijden.';

    public function handle(GooglePlacesPhotoService $photoService): int
    {
        $source = trim((string) $this->option('source'));
        $limit = max(1, (int) $this->option('limit'));
        $sleepMs = max(0, (int) $this->option('sleep-ms'));
        $force = (bool) $this->option('force');

        $query = Location::query()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->when($source !== '', fn ($q) => $q->where('source', $source))
            ->when(!$force, fn ($q) => $q->whereNull('opening_hours_updated_at'))
            ->orderBy('id')
            ->limit($limit);

        $locations = $query->get();
        if ($locations->isEmpty()) {
            $this->info('Geen locaties gevonden om te verrijken.');
            return self::SUCCESS;
        }

        $updated = 0;
        $skipped = 0;
        $processed = 0;

        foreach ($locations as $location) {
            if ($processed >= $limit) {
                break;
            }

            $profile = $photoService->findPlaceProfile(
                (string) $location->name,
                (string) $location->address,
                (string) $location->postcode,
                (string) $location->city,
                (float) $location->latitude,
                (float) $location->longitude
            );
            $processed++;

            if (!is_array($profile)) {
                if (!$force && $location->opening_hours_updated_at === null) {
                    // Markeer als "geprobeerd" zodat volgende runs kunnen doorpakken naar latere records.
                    $location->update(['opening_hours_updated_at' => now()]);
                }
                $skipped++;
                continue;
            }

            $payload = [];
            if (is_string($profile['photo_url'] ?? null) && trim((string) $profile['photo_url']) !== '') {
                $payload['photo_url'] = $profile['photo_url'];
            }
            if (is_string($profile['place_id'] ?? null) && trim((string) $profile['place_id']) !== '') {
                $payload['google_place_id'] = $profile['place_id'];
            }
            if (is_array($profile['opening_hours_weekday_text'] ?? null) && $profile['opening_hours_weekday_text'] !== []) {
                $payload['opening_hours_json'] = json_encode($profile['opening_hours_weekday_text'], JSON_UNESCAPED_UNICODE);
                $payload['opening_hours_updated_at'] = now();
            } elseif (!$force && $location->opening_hours_updated_at === null) {
                $payload['opening_hours_updated_at'] = now();
            }

            if ($payload === []) {
                $skipped++;
                continue;
            }

            $location->update($payload);
            $updated++;

            if ($sleepMs > 0) {
                usleep($sleepMs * 1000);
            }
        }

        $this->newLine();
        $this->info("Locaties verwerkt: {$processed}");
        $this->info("Locaties geupdate met Google-data: {$updated}");
        $this->info("Locaties zonder bruikbaar Google-resultaat: {$skipped}");

        return self::SUCCESS;
    }
}
