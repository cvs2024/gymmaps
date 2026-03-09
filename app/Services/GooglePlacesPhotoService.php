<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GooglePlacesPhotoService
{
    public function findPhotoUrl(
        string $name,
        string $address,
        string $postcode,
        string $city,
        ?float $latitude = null,
        ?float $longitude = null
    ): ?string {
        $apiKey = (string) config('services.google_maps.key');
        if ($apiKey === '') {
            return null;
        }

        $query = trim($name.', '.$address.', '.$postcode.' '.$city.', Nederland');
        $params = [
            'input' => $query,
            'inputtype' => 'textquery',
            'fields' => 'name,photos,place_id',
            'key' => $apiKey,
        ];

        if ($latitude !== null && $longitude !== null) {
            $params['locationbias'] = 'point:'.$latitude.','.$longitude;
        }

        $response = Http::timeout(10)
            ->retry(1, 300)
            ->acceptJson()
            ->get('https://maps.googleapis.com/maps/api/place/findplacefromtext/json', $params);

        if ($response->failed()) {
            return null;
        }

        $payload = $response->json();
        if (($payload['status'] ?? null) !== 'OK') {
            return null;
        }

        $photoReference = $payload['candidates'][0]['photos'][0]['photo_reference'] ?? null;
        if (!is_string($photoReference) || trim($photoReference) === '') {
            return null;
        }

        return 'https://maps.googleapis.com/maps/api/place/photo?maxwidth=640&photo_reference='
            .rawurlencode($photoReference)
            .'&key='
            .rawurlencode($apiKey);
    }
}
