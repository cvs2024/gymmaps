<?php

namespace App\Console\Commands;

use App\Models\Location;
use App\Models\Sport;
use Illuminate\Console\Command;

class CleanupBoxingFalsePositives extends Command
{
    protected $signature = 'gymmap:cleanup-boxing-false-positives
                            {--source=kvk : Filter op source (leeg = alle sources)}
                            {--limit=50000 : Max aantal locaties per run}
                            {--dry-run : Toon alleen wat aangepast zou worden}';

    protected $description = 'Verwijder boksen-labels van locaties die waarschijnlijk geen boksschool zijn.';

    public function handle(): int
    {
        $source = trim((string) $this->option('source'));
        $limit = max(1, (int) $this->option('limit'));
        $dryRun = (bool) $this->option('dry-run');

        $boxing = Sport::query()->where('slug', 'boksen')->first();
        if (!$boxing) {
            $this->error('Sport "boksen" bestaat niet.');
            return self::FAILURE;
        }

        $locations = Location::query()
            ->with('sports')
            ->when($source !== '', fn ($q) => $q->where('source', $source))
            ->whereHas('sports', fn ($q) => $q->where('sports.id', $boxing->id))
            ->orderBy('id')
            ->limit($limit)
            ->get();

        if ($locations->isEmpty()) {
            $this->info('Geen boksen-locaties gevonden voor cleanup.');
            return self::SUCCESS;
        }

        $boxingPositiveTerms = [
            'boksen',
            'boks',
            'kickbok',
            'boxing',
            'vechtsport',
            'martial',
            'mma',
            'muay thai',
            'jiu jitsu',
            'dojo',
            'fight',
            'kickfit',
        ];

        $nonSportBusinessTerms = [
            'mondzorg',
            'tandarts',
            'dental',
            'fysiotherapie',
            'fysio',
            'huisarts',
            'apotheek',
            'coaching',
            'personal trainer',
            'personaltraining',
            'pt studio',
            'pt-studio',
        ];

        $detached = 0;
        $kept = 0;
        $checked = 0;

        foreach ($locations as $location) {
            $checked++;
            $text = mb_strtolower(trim(implode(' ', array_filter([
                (string) $location->name,
                (string) $location->address,
                (string) $location->city,
                (string) $location->website,
            ]))));

            $hasBoxingSignals = $this->containsAny($text, $boxingPositiveTerms);
            $hasNonSportSignals = $this->containsAny($text, $nonSportBusinessTerms);

            if ($hasBoxingSignals && !$hasNonSportSignals) {
                $kept++;
                continue;
            }

            if (!$dryRun) {
                $location->sports()->detach($boxing->id);
            }

            $detached++;
            $this->line(sprintf(
                '%sBOKSEN %s · %s (%s %s)',
                $dryRun ? '[DRY-RUN] ' : '',
                $dryRun ? 'zou verwijderd worden van' : 'verwijderd van',
                $location->name,
                $location->postcode,
                $location->city
            ));
        }

        $this->newLine();
        $this->info("Gecontroleerd: {$checked}");
        $this->info($dryRun ? "Zou boksen loskoppelen: {$detached}" : "Boksen losgekoppeld: {$detached}");
        $this->info("Ongewijzigd: {$kept}");

        return self::SUCCESS;
    }

    private function containsAny(string $text, array $terms): bool
    {
        foreach ($terms as $term) {
            if ($term !== '' && str_contains($text, mb_strtolower($term))) {
                return true;
            }
        }

        return false;
    }
}

