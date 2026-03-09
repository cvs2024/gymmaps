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

    protected $description = 'Verrijk locaties met een passende Google Places foto.';

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
            ->when(!$force, fn ($q) => $q->where(function ($sub) {
                $sub->whereNull('photo_url')
                    ->orWhere('photo_url', '');
            }))
            ->orderBy('id')
            ->limit($limit);

        $locations = $query->get();
        if ($locations->isEmpty()) {
            $this->info('Geen locaties gevonden om te verrijken.');
            return self::SUCCESS;
        }

        $updated = 0;
        $skipped = 0;

        foreach ($locations as $location) {
            $photoUrl = $photoService->findPhotoUrl(
                (string) $location->name,
                (string) $location->address,
                (string) $location->postcode,
                (string) $location->city,
                (float) $location->latitude,
                (float) $location->longitude
            );

            if ($photoUrl === null) {
                $skipped++;
                continue;
            }

            $location->update(['photo_url' => $photoUrl]);
            $updated++;

            if ($sleepMs > 0) {
                usleep($sleepMs * 1000);
            }
        }

        $this->newLine();
        $this->info("Locaties geupdate met foto: {$updated}");
        $this->info("Locaties zonder foto resultaat: {$skipped}");

        return self::SUCCESS;
    }
}
