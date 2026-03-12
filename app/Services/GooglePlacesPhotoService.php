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

        $queryVariants = $this->buildQueryVariants($name, $address, $postcode, $city);
        $candidate = null;

        foreach ($queryVariants as $queryVariant) {
            $candidate = $this->findBestCandidate($queryVariant, $apiKey, $latitude, $longitude);
            if (is_array($candidate)) {
                break;
            }
        }

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

    private function findBestCandidate(string $query, string $apiKey, ?float $latitude, ?float $longitude): ?array
    {
        $params = [
            'input' => $query,
            'inputtype' => 'textquery',
            'fields' => 'name,photos,place_id,geometry',
            'language' => 'nl',
            'key' => $apiKey,
        ];

        if ($latitude !== null && $longitude !== null) {
            $params['locationbias'] = 'point:'.$latitude.','.$longitude;
        }

        $response = Http::timeout(10)
            ->retry(2, 350)
            ->acceptJson()
            ->get('https://maps.googleapis.com/maps/api/place/findplacefromtext/json', $params);

        if ($response->failed()) {
            return null;
        }

        $payload = $response->json();
        if (($payload['status'] ?? null) !== 'OK') {
            return null;
        }

        $candidates = $payload['candidates'] ?? [];
        if (!is_array($candidates) || $candidates === []) {
            return null;
        }

        if ($latitude === null || $longitude === null) {
            return is_array($candidates[0] ?? null) ? $candidates[0] : null;
        }

        $bestCandidate = null;
        $bestDistance = INF;

        foreach ($candidates as $candidate) {
            if (!is_array($candidate)) {
                continue;
            }

            $candidateLat = $candidate['geometry']['location']['lat'] ?? null;
            $candidateLng = $candidate['geometry']['location']['lng'] ?? null;
            if (!is_numeric($candidateLat) || !is_numeric($candidateLng)) {
                continue;
            }

            $distance = $this->haversineDistanceKm(
                $latitude,
                $longitude,
                (float) $candidateLat,
                (float) $candidateLng
            );

            if ($distance < $bestDistance) {
                $bestDistance = $distance;
                $bestCandidate = $candidate;
            }
        }

        return is_array($bestCandidate) ? $bestCandidate : (is_array($candidates[0] ?? null) ? $candidates[0] : null);
    }

    private function buildQueryVariants(string $name, string $address, string $postcode, string $city): array
    {
        $variants = [
            trim($name.', '.$address.', '.$postcode.' '.$city.', Nederland'),
            trim($name.', '.$postcode.' '.$city.', Nederland'),
            trim($name.', '.$city.', Nederland'),
            trim($name.', Nederland'),
        ];

        return array_values(array_unique(array_filter($variants, fn ($query) => $query !== '')));
    }

    private function haversineDistanceKm(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadiusKm = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2)
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
            * sin($dLon / 2) * sin($dLon / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadiusKm * $c;
    }
}
