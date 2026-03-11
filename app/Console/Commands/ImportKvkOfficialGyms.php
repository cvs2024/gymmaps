<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ImportKvkOfficialGyms extends Command
{
    protected $signature = 'gymmap:import-kvk-official-gyms
                            {--page=1 : Startpagina}
                            {--pages=0 : Aantal pagina\'s per zoekterm (0 = doorlopen tot lege pagina)}
                            {--per-page=100 : Aantal resultaten per pagina}
                            {--max-pages=80 : Veiligheidslimiet bij pages=0}
                            {--max-failures=10 : Stop na dit aantal opeenvolgende API-fouten per zoekterm}
                            {--dry-run : Alleen ophalen en in raw_imports zetten}
                            {--query=* : Optioneel extra zoektermen; zo niet opgegeven gebruikt command standaard gymketens}';

    protected $description = 'Importeer gericht officiële fitnessketens en sportscholen uit KVK.';

    public function handle(): int
    {
        $queries = $this->option('query');
        if (!is_array($queries) || $queries === []) {
            $queries = [
                'Basic-Fit',
                'Basic Fit',
                'BasicFit',
                'TrainMore',
                'Fit For Free',
                'Fitforfree',
                'SportCity',
                'MyLife',
                'Mylife',
                'Anytime Fitness',
                'AnytimeFitness',
                'BigGym',
                'Big Gym',
                'Invictus',
                'Club Pellikaan',
                'ClubPellikaan',
                'Snap Fitness',
                'SnapFitness',
                'David Lloyd',
                'DavidLloyd',
                'SportPlaza',
            ];
        }

        $parameters = [
            '--query' => $queries,
            '--page' => max(1, (int) $this->option('page')),
            '--pages' => max(0, (int) $this->option('pages')),
            '--per-page' => max(1, min(100, (int) $this->option('per-page'))),
            '--max-pages' => max(1, (int) $this->option('max-pages')),
            '--max-failures' => max(1, (int) $this->option('max-failures')),
            '--dry-run' => (bool) $this->option('dry-run'),
        ];

        $this->line('Start import voor officiële fitnessketens en gyms...');
        $exitCode = Artisan::call('gymmap:import-kvk-sports', $parameters, $this->output);

        return is_int($exitCode) ? $exitCode : self::SUCCESS;
    }
}
