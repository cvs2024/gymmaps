<?php

namespace App\Console\Commands;

use App\Models\Location;
use Illuminate\Console\Command;

class CleanupBrokenLocationPhotos extends Command
{
    protected $signature = 'gymmap:cleanup-broken-location-photos
                            {--source=kvk : Filter op source (leeg = alle sources)}
                            {--dry-run : Alleen tonen hoeveel records worden opgeschoond}';

    protected $description = 'Verwijdert onbruikbare photo_url waarden (oude map placeholder/streetview/staticmap URL\'s).';

    public function handle(): int
    {
        $source = trim((string) $this->option('source'));
        $dryRun = (bool) $this->option('dry-run');

        $patterns = [
            '%maps.googleapis.com/maps/api/streetview%',
            '%maps.googleapis.com/maps/api/staticmap%',
            '%maps.gstatic.com/mapfiles%',
            '%gstatic.com/mapfiles%',
            '%/mapfiles/%',
            '%streetview?%',
            '%cbk?output=%',
        ];

        $toCleanupQuery = Location::query()
            ->whereNotNull('photo_url')
            ->when($source !== '', fn ($query) => $query->where('source', $source))
            ->where(function ($query) use ($patterns) {
                foreach ($patterns as $index => $pattern) {
                    if ($index === 0) {
                        $query->where('photo_url', 'like', $pattern);
                        continue;
                    }

                    $query->orWhere('photo_url', 'like', $pattern);
                }
            });

        $count = (clone $toCleanupQuery)->count();

        if ($count === 0) {
            $this->info('Geen onbruikbare photo_url records gevonden.');
            return self::SUCCESS;
        }

        $this->info("Records gevonden voor opschoning: {$count}");

        if ($dryRun) {
            $this->comment('Dry-run actief: er is niets aangepast.');
            return self::SUCCESS;
        }

        $updated = (clone $toCleanupQuery)->update(['photo_url' => null]);

        $this->info("Records opgeschoond: {$updated}");
        $this->newLine();
        $this->line('Tip: draai daarna `php artisan optimize:clear` en herlaad de homepage.');

        return self::SUCCESS;
    }
}

