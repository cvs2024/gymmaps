<?php

namespace App\Console\Commands;

use App\Services\KvkApiService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Throwable;

class DiagnoseKvkApi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gymmap:diagnose-kvk
                            {--query=fitness : Handelsnaam zoekterm voor endpoint-test}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Diagnoseer KVK API bereikbaarheid, key-config en endpoint responses.';

    /**
     * Execute the console command.
     */
    public function handle(KvkApiService $kvkApiService): int
    {
        $baseUrl = (string) config('services.kvk.base_url');
        $apiKey = (string) config('services.kvk.key');
        $query = (string) $this->option('query');

        $this->info('KVK diagnose gestart');
        $this->line("Base URL: {$baseUrl}");
        $this->line('API key aanwezig: '.($apiKey !== '' ? 'ja' : 'nee'));

        $host = parse_url($baseUrl, PHP_URL_HOST);
        if (is_string($host) && $host !== '') {
            $resolved = gethostbyname($host);
            $this->line("DNS resolve {$host}: {$resolved}");
        } else {
            $this->warn('Kon host niet afleiden uit base URL.');
        }

        $this->newLine();
        $this->line('1) Test basisprofiel endpoint (verwachte 400 bij ongeldig nummer)');
        try {
            $response = $kvkApiService->getByKvkNumber('90000000');
            $this->warn('Onverwachte success response: '.json_encode($response));
        } catch (Throwable $e) {
            $this->line('Resultaat: '.$e->getMessage());
        }

        $this->newLine();
        $this->line("2) Test zoeken endpoint met handelsnaam=\"{$query}\"");
        try {
            $response = $kvkApiService->search([
                'handelsnaam' => $query,
                'pagina' => 1,
                'aantal' => 5,
            ]);
            $this->info('Zoeken success.');
            $this->line('Top-level keys: '.implode(', ', array_keys($response)));
        } catch (Throwable $e) {
            $this->line('Resultaat: '.$e->getMessage());
        }

        $this->newLine();
        $this->line('3) Directe ruwe call (zonder service) naar /api/v1/zoeken');
        try {
            $raw = Http::timeout(10)
                ->acceptJson()
                ->withHeaders([
                    (string) config('services.kvk.key_header', 'apikey') => $apiKey,
                ])
                ->get('https://api.kvk.nl/api/v1/zoeken', [
                    'handelsnaam' => $query,
                    'pagina' => 1,
                    'aantal' => 5,
                ]);

            $this->line('HTTP status: '.$raw->status());
            $this->line('Body (eerste 300 chars): '.mb_substr($raw->body(), 0, 300));
        } catch (Throwable $e) {
            $this->line('Resultaat: '.$e->getMessage());
        }

        return self::SUCCESS;
    }
}
