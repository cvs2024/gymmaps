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

        return view('locations.show', [
            'location' => $location,
            'photoUrl' => $photoUrl,
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
}
