<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GoogleGeocodingService
{
    public function geocode(string $address): ?array
    {
        $apiKey = (string) config('services.google_maps.key');

        if ($apiKey === '') {
            return null;
        }

        $response = Http::timeout(10)
            ->acceptJson()
            ->get('https://maps.googleapis.com/maps/api/geocode/json', [
                'address' => $address,
                'region' => 'nl',
                'key' => $apiKey,
            ]);

        if ($response->failed()) {
            return null;
        }

        $payload = $response->json();

        if (($payload['status'] ?? null) !== 'OK') {
            return null;
        }

        $location = $payload['results'][0]['geometry']['location'] ?? null;

        if (!is_array($location) || !isset($location['lat'], $location['lng'])) {
            return null;
        }

        return [
            'latitude' => (float) $location['lat'],
            'longitude' => (float) $location['lng'],
        ];
    }
}
