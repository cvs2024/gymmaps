<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class OverpassApiService
{
    public function fetchNetherlandsSports(): array
    {
        $baseUrl = (string) config('services.overpass.base_url', 'https://overpass-api.de/api/interpreter');
        $timeout = (int) config('services.overpass.timeout', 120);

        $query = <<<'OVERPASS'
[out:json][timeout:180];
area["ISO3166-1"="NL"][admin_level=2]->.nl;
(
  nwr(area.nl)["leisure"="fitness_centre"];
  nwr(area.nl)["amenity"="gym"];
  nwr(area.nl)["sport"~"^(fitness|yoga|boxing|martial_arts|tennis|squash|crossfit)$"];
);
out center tags;
OVERPASS;

        $response = Http::timeout($timeout)
            ->acceptJson()
            ->asForm()
            ->post($baseUrl, [
                'data' => $query,
            ]);

        if ($response->failed()) {
            throw new RuntimeException('Overpass request failed: HTTP '.$response->status());
        }

        $json = $response->json();

        if (!is_array($json)) {
            throw new RuntimeException('Overpass response is geen valide JSON array.');
        }

        return $json;
    }
}
