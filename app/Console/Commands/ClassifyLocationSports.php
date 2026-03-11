<?php

namespace App\Console\Commands;

use App\Models\Location;
use App\Models\Sport;
use Illuminate\Console\Command;

class ClassifyLocationSports extends Command
{
    protected $signature = 'gymmap:classify-location-sports
                            {--source=kvk : Filter op source (leeg = alle sources)}
                            {--limit=10000 : Max aantal locaties per run}
                            {--append : Voeg labels toe i.p.v. bestaande labels te vervangen}
                            {--dry-run : Alleen classificeren, niet opslaan}';

    protected $description = 'Classificeer bestaande locaties opnieuw naar sportfilters (fitness/crossfit/tennis/squash/padel/etc).';

    public function handle(): int
    {
        $source = trim((string) $this->option('source'));
        $limit = max(1, (int) $this->option('limit'));
        $append = (bool) $this->option('append');
        $dryRun = (bool) $this->option('dry-run');

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

        $sportIdsBySlug = Sport::query()->pluck('id', 'slug')->all();
        $locations = Location::query()
            ->when($source !== '', fn ($q) => $q->where('source', $source))
            ->orderBy('id')
            ->limit($limit)
            ->get();

        if ($locations->isEmpty()) {
            $this->info('Geen locaties gevonden om te classificeren.');
            return self::SUCCESS;
        }

        $updated = 0;
        $skipped = 0;

        foreach ($locations as $location) {
            $text = mb_strtolower(trim(implode(' ', [
                (string) $location->name,
                (string) $location->address,
                (string) $location->city,
                (string) $location->website,
            ])));

            $slugs = $this->mapSports($text);
            $sportIds = collect($slugs)
                ->map(fn ($slug) => $sportIdsBySlug[$slug] ?? null)
                ->filter()
                ->values()
                ->all();

            if ($sportIds === []) {
                $skipped++;
                continue;
            }

            if (!$dryRun) {
                if ($append) {
                    $location->sports()->syncWithoutDetaching($sportIds);
                } else {
                    $location->sports()->sync($sportIds);
                }
            }

            $updated++;
        }

        $this->newLine();
        $this->info("Locaties geclassificeerd: {$updated}");
        $this->info("Locaties zonder match: {$skipped}");

        return self::SUCCESS;
    }

    private function mapSports(string $text): array
    {
        $map = [
            'personal-trainer' => ['personal trainer', 'personaltraining', 'pt studio', 'pt-studio', 'coach'],
            'fitness' => [
                'fitness',
                'sportschool',
                'gym',
                'krachttraining',
                'kracht train',
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
            ],
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

        return $found !== [] ? array_values(array_unique($found)) : [];
    }
}
