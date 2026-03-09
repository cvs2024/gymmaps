<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class ImportKvkBoxingLocations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gymmap:import-kvk-boxing
                            {--page=1 : Startpagina}
                            {--pages=0 : Aantal pagina\'s per zoekterm (0 = doorlopen tot lege pagina)}
                            {--per-page=25 : Aantal resultaten per pagina}
                            {--max-pages=120 : Veiligheidslimiet bij pages=0}
                            {--max-failures=8 : Stop na dit aantal opeenvolgende API-fouten per zoekterm}
                            {--dry-run : Alleen ophalen en in raw_imports zetten}
                            {--query=* : Optioneel extra zoektermen; zo niet opgegeven gebruikt command standaard bokstermen}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Importeer specifiek boksscholen en vechtsportlocaties uit KVK.';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $queries = $this->option('query');
        if (!is_array($queries) || $queries === []) {
            $queries = [
                'boksschool',
                'kickboksen',
                'boxing',
                'vechtsport',
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

        $this->line('Start import voor boksscholen en vechtsportlocaties...');
        $exitCode = Artisan::call('gymmap:import-kvk-sports', $parameters, $this->output);

        return is_int($exitCode) ? $exitCode : self::SUCCESS;
    }
}
