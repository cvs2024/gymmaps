<?php

namespace App\Console\Commands;

use App\Models\Location;
use App\Models\Sport;
use Illuminate\Console\Command;

class CleanupFitnessFromPersonalTrainers extends Command
{
    protected $signature = 'gymmap:cleanup-fitness-personal-trainers
                            {--source=kvk : Filter op source (leeg = alle sources)}
                            {--limit=50000 : Max aantal locaties per run}
                            {--dry-run : Toon alleen wat aangepast zou worden}';

    protected $description = 'Verwijder fitness-labels van locaties die waarschijnlijk personal trainers/woonadressen zijn.';

    public function handle(): int
    {
        $source = trim((string) $this->option('source'));
        $limit = max(1, (int) $this->option('limit'));
        $dryRun = (bool) $this->option('dry-run');

        $fitness = Sport::query()->where('slug', 'fitness')->first();
        if (!$fitness) {
            $this->error('Sport "fitness" bestaat niet.');
            return self::FAILURE;
        }

        $hasPersonalTrainerSport = Sport::query()->where('slug', 'personal-trainer')->exists();

        $locations = Location::query()
            ->with('sports')
            ->when($source !== '', fn ($q) => $q->where('source', $source))
            ->whereHas('sports', fn ($q) => $q->where('sports.id', $fitness->id))
            ->orderBy('id')
            ->limit($limit)
            ->get();

        if ($locations->isEmpty()) {
            $this->info('Geen fitnesslocaties gevonden voor cleanup.');
            return self::SUCCESS;
        }

        $officialGymTerms = [
            'basic fit',
            'basic-fit',
            'basicfit',
            'trainmore',
            'fit for free',
            'fitforfree',
            'fit-for-free',
            'sportcity',
            'mylife',
            'my life',
            'anytime fitness',
            'anytimefitness',
            'biggym',
            'big gym',
            'invictus',
            'club pellikaan',
            'clubpellikaan',
            'snap fitness',
            'snapfitness',
            'david lloyd',
            'davidlloyd',
            'sportplaza',
        ];

        $personalTrainerTerms = [
            'personal trainer',
            'personaltraining',
            'pt studio',
            'pt-studio',
            'coach',
            'coaching',
        ];

        $detached = 0;
        $kept = 0;
        $checked = 0;

        foreach ($locations as $location) {
            $checked++;
            $sportSlugs = $location->sports->pluck('slug')->all();
            $text = mb_strtolower(trim(implode(' ', array_filter([
                (string) $location->name,
                (string) $location->address,
                (string) $location->city,
                (string) $location->website,
            ]))));

            $hasOfficialGymTerm = $this->containsAny($text, $officialGymTerms);
            $hasPersonalTrainerTerm = $this->containsAny($text, $personalTrainerTerms)
                || ($hasPersonalTrainerSport && in_array('personal-trainer', $sportSlugs, true));

            if (!$hasPersonalTrainerTerm || $hasOfficialGymTerm) {
                $kept++;
                continue;
            }

            if (!$dryRun) {
                $location->sports()->detach($fitness->id);
            }

            $detached++;
            $this->line(sprintf(
                '%sFITNESS %s · %s (%s %s)',
                $dryRun ? '[DRY-RUN] ' : '',
                $dryRun ? 'zou verwijderd worden van' : 'verwijderd van',
                $location->name,
                $location->postcode,
                $location->city
            ));
        }

        $this->newLine();
        $this->info("Gecontroleerd: {$checked}");
        $this->info($dryRun ? "Zou fitness loskoppelen: {$detached}" : "Fitness losgekoppeld: {$detached}");
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
