<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GooglePlacesPhotoService
{
    public function findPlaceProfile(
        string $name,
        string $address,
        string $postcode,
        string $city,
        ?float $latitude = null,
        ?float $longitude = null
    ): ?array {
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

        $candidate = $payload['candidates'][0] ?? null;
        if (!is_array($candidate)) {
            return null;
        }

        $placeId = $candidate['place_id'] ?? null;
        if (!is_string($placeId) || trim($placeId) === '') {
            return null;
        }

        $photoReference = $candidate['photos'][0]['photo_reference'] ?? null;
        $photoUrl = null;
        if (is_string($photoReference) && trim($photoReference) !== '') {
            $photoUrl = 'https://maps.googleapis.com/maps/api/place/photo?maxwidth=640&photo_reference='
                .rawurlencode($photoReference)
                .'&key='
                .rawurlencode($apiKey);
        }

        return [
            'place_id' => $placeId,
            'photo_url' => $photoUrl,
            'opening_hours_weekday_text' => $this->fetchOpeningHoursWeekdayText($placeId, $apiKey),
        ];
    }

    public function findPhotoUrl(
        string $name,
        string $address,
        string $postcode,
        string $city,
        ?float $latitude = null,
        ?float $longitude = null
    ): ?string {
        $profile = $this->findPlaceProfile($name, $address, $postcode, $city, $latitude, $longitude);
        if (!is_array($profile)) {
            return null;
        }

        $photoUrl = $profile['photo_url'] ?? null;
        if (!is_string($photoUrl) || trim($photoUrl) === '') {
            return null;
        }

        return $photoUrl;
    }

    private function fetchOpeningHoursWeekdayText(string $placeId, string $apiKey): ?array
    {
        $response = Http::timeout(10)
            ->retry(1, 300)
            ->acceptJson()
            ->get('https://maps.googleapis.com/maps/api/place/details/json', [
                'place_id' => $placeId,
                'fields' => 'current_opening_hours,opening_hours',
                'key' => $apiKey,
            ]);

        if ($response->failed()) {
            return null;
        }

        $payload = $response->json();
        if (($payload['status'] ?? null) !== 'OK') {
            return null;
        }

        $weekdayText = $payload['result']['current_opening_hours']['weekday_text']
            ?? $payload['result']['opening_hours']['weekday_text']
            ?? null;

        if (!is_array($weekdayText) || $weekdayText === []) {
            return null;
        }

        return array_values(array_filter(array_map(
            fn ($line) => is_string($line) ? trim($line) : '',
            $weekdayText
        )));
    }
}
