<?php

namespace App\Http\Controllers;

use App\Models\Location;

class LocationProfileController extends Controller
{
    public function show(Location $location)
    {
        $location->load('sports');

        $googleMapsKey = (string) config('services.google_maps.key');
        $photoUrl = $this->resolvePhotoUrl($location, $googleMapsKey);
        $openingHours = $this->resolveOpeningHoursForDisplay($location);

        return view('locations.show', [
            'location' => $location,
            'photoUrl' => $photoUrl,
            'websiteUrl' => $this->resolveWebsiteUrl($location),
            'googleSearchUrl' => $this->resolveGoogleSearchUrl($location),
            'openingHoursToday' => $openingHours['today'],
            'openingHoursWeek' => $openingHours['week'],
            'googleMapsKey' => $googleMapsKey,
        ]);
    }

    private function resolvePhotoUrl(Location $location, string $googleMapsKey): string
    {
        if (is_string($location->photo_url) && trim($location->photo_url) !== '') {
            return $location->photo_url;
        }

        if ($googleMapsKey !== '') {
            $lat = (float) $location->latitude;
            $lng = (float) $location->longitude;
            $locationParam = rawurlencode($lat.','.$lng);

            return "https://maps.googleapis.com/maps/api/streetview?size=960x540&location={$locationParam}&fov=90&pitch=0&key={$googleMapsKey}";
        }

        return 'https://placehold.co/960x540/eaf1f7/587089?text=Geen+foto';
    }

    private function resolveWebsiteUrl(Location $location): ?string
    {
        $website = trim((string) $location->website);
        if ($website === '') {
            return $this->resolveOfficialWebsiteFromName($location->name);
        }

        if (!str_starts_with($website, 'http://') && !str_starts_with($website, 'https://')) {
            $website = 'https://' . $website;
        }

        if (!filter_var($website, FILTER_VALIDATE_URL)) {
            return $this->resolveOfficialWebsiteFromName($location->name);
        }

        return $website;
    }

    private function resolveOfficialWebsiteFromName(?string $name): ?string
    {
        $normalizedName = mb_strtolower(trim((string) $name));
        if ($normalizedName === '') {
            return null;
        }

        $brandWebsites = [
            ['keywords' => ['basic fit', 'basic-fit', 'basicfit'], 'url' => 'https://www.basic-fit.com'],
            ['keywords' => ['trainmore'], 'url' => 'https://trainmore.nl'],
            ['keywords' => ['fit for free', 'fitforfree', 'fit-for-free'], 'url' => 'https://www.fitforfree.nl'],
            ['keywords' => ['sportcity'], 'url' => 'https://www.sportcity.nl'],
            ['keywords' => ['mylife', 'my life'], 'url' => 'https://www.mylife.nl'],
            ['keywords' => ['anytime fitness', 'anytimefitness'], 'url' => 'https://www.anytimefitness.nl'],
            ['keywords' => ['biggym', 'big gym'], 'url' => 'https://www.biggym.nl'],
            ['keywords' => ['invictus'], 'url' => 'https://www.invictusgym.nl'],
            ['keywords' => ['club pellikaan', 'clubpellikaan'], 'url' => 'https://www.clubpellikaan.nl'],
            ['keywords' => ['snap fitness', 'snapfitness'], 'url' => 'https://www.snapfitness.com'],
            ['keywords' => ['david lloyd', 'davidlloyd'], 'url' => 'https://www.davidlloyd.nl'],
            ['keywords' => ['sportplaza'], 'url' => 'https://www.sportplaza.nl'],
        ];

        foreach ($brandWebsites as $brandWebsite) {
            foreach ($brandWebsite['keywords'] as $keyword) {
                if (str_contains($normalizedName, $keyword)) {
                    return $brandWebsite['url'];
                }
            }
        }

        return null;
    }

    private function resolveGoogleSearchUrl(Location $location): string
    {
        $query = trim(implode(' ', array_filter([
            (string) $location->name,
            (string) $location->city,
            'officiële website',
        ])));

        return 'https://www.google.com/search?q=' . rawurlencode($query);
    }

    private function resolveOpeningHoursForDisplay(Location $location): array
    {
        $raw = $location->opening_hours_json;
        if (is_string($raw) && trim($raw) !== '') {
            $decoded = json_decode($raw, true);
            $raw = is_array($decoded) ? $decoded : null;
        }

        if (!is_array($raw) || $raw === []) {
            return ['today' => null, 'week' => []];
        }

        $week = array_values(array_filter(array_map(
            fn ($line) => is_string($line) ? trim($line) : '',
            $raw
        )));

        if ($week === []) {
            return ['today' => null, 'week' => []];
        }

        $todayIndex = (int) now()->locale('nl')->dayOfWeekIso - 1; // 0 = maandag
        $todayLine = $week[$todayIndex] ?? null;

        return [
            'today' => is_string($todayLine) && $todayLine !== '' ? $todayLine : null,
            'week' => $week,
        ];
    }
}
