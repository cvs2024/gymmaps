<?php

namespace App\Services;

use App\Models\Location;
use App\Models\Sport;
use Illuminate\Support\Str;

class LocationUpsertService
{
    public function upsert(array $data, array $sportSlugs = []): Location
    {
        $existing = $this->findExisting($data);

        $payload = [
            'name' => $data['name'],
            'address' => $data['address'],
            'postcode' => $data['postcode'],
            'city' => $data['city'],
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
            'website' => $data['website'] ?? null,
            'phone' => $data['phone'] ?? null,
            'photo_url' => $data['photo_url'] ?? null,
            'source' => $data['source'] ?? null,
            'external_id' => $data['external_id'] ?? null,
            'last_seen_at' => now(),
        ];

        if ($existing) {
            $existing->update($payload);
            $location = $existing;
        } else {
            $payload['imported_at'] = now();
            $location = Location::query()->create($payload);
        }

        $sportIds = Sport::query()
            ->whereIn('slug', array_values(array_unique($sportSlugs)))
            ->pluck('id')
            ->all();

        if ($sportIds !== []) {
            $location->sports()->syncWithoutDetaching($sportIds);
        }

        return $location;
    }

    private function findExisting(array $data): ?Location
    {
        $source = $data['source'] ?? null;
        $externalId = $data['external_id'] ?? null;

        if ($source && $externalId) {
            $byExternal = Location::query()
                ->where('source', $source)
                ->where('external_id', $externalId)
                ->first();

            if ($byExternal) {
                return $byExternal;
            }
        }

        $normalizedIncoming = $this->normalizeName($data['name']);

        $samePostcode = Location::query()
            ->where('postcode', $data['postcode'])
            ->get();

        foreach ($samePostcode as $candidate) {
            $normalizedCandidate = $this->normalizeName($candidate->name);
            if ($normalizedCandidate === $normalizedIncoming) {
                return $candidate;
            }
        }

        $nearby = Location::query()->get()->first(function (Location $candidate) use ($data, $normalizedIncoming) {
            $distanceKm = $this->distanceKm(
                (float) $candidate->latitude,
                (float) $candidate->longitude,
                (float) $data['latitude'],
                (float) $data['longitude']
            );

            if ($distanceKm > 0.2) {
                return false;
            }

            $normalizedCandidate = $this->normalizeName($candidate->name);

            return str_contains($normalizedCandidate, $normalizedIncoming)
                || str_contains($normalizedIncoming, $normalizedCandidate);
        });

        return $nearby instanceof Location ? $nearby : null;
    }

    private function normalizeName(string $value): string
    {
        $value = Str::lower($value);
        $value = preg_replace('/[^a-z0-9]+/', '', $value) ?? $value;

        return $value;
    }

    private function distanceKm(float $lat1, float $lon1, float $lat2, float $lon2): float
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
