<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Sport;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $sports = Sport::query()->orderBy('name')->get();

        $query = trim((string) $request->string('q'));
        $radius = (int) $request->integer('radius', 10);
        $selectedSports = collect($request->input('sports', []))
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->values();

        $radius = in_array($radius, [5, 10, 20, 50, 250], true) ? $radius : 10;

        $center = null;
        if ($query !== '') {
            $center = Location::query()
                ->where('postcode', 'like', "%{$query}%")
                ->orWhere('city', 'like', "%{$query}%")
                ->orWhere('address', 'like', "%{$query}%")
                ->orWhere('name', 'like', "%{$query}%")
                ->first();
        }

        $locationsQuery = Location::query()
            ->with('sports')
            ->when($selectedSports->isNotEmpty(), function ($q) use ($selectedSports) {
                $q->whereHas('sports', function ($sportQuery) use ($selectedSports) {
                    $sportQuery->whereIn('sports.id', $selectedSports);
                });
            });

        $results = collect();
        $paginatedResults = null;
        $googleMapsKey = (string) config('services.google_maps.key');
        if ($center) {
            $results = (clone $locationsQuery)
                ->get()
                ->map(function (Location $location) use ($center) {
                    $distance = $this->haversineDistanceKm(
                        (float) $center->latitude,
                        (float) $center->longitude,
                        (float) $location->latitude,
                        (float) $location->longitude
                    );
                    $location->distance_km = round($distance, 1);

                    return $location;
                })
                ->filter(fn (Location $location) => $location->distance_km <= $radius)
                ->sortBy('distance_km')
                ->map(function (Location $location) use ($googleMapsKey) {
                    $location->display_photo_url = $this->resolvePhotoUrl($location, $googleMapsKey);

                    return $location;
                })
                ->values();

            $perPage = 20;
            $page = max(1, (int) $request->integer('page', 1));
            $paginatedResults = new LengthAwarePaginator(
                $results->forPage($page, $perPage)->values(),
                $results->count(),
                $perPage,
                $page,
                [
                    'path' => $request->url(),
                    'query' => $request->query(),
                ]
            );
        }

        $locationsForMap = $center
            ? $results
            : (clone $locationsQuery)->orderBy('city')->get()->map(function (Location $location) use ($googleMapsKey) {
                $location->distance_km = null;
                $location->display_photo_url = $this->resolvePhotoUrl($location, $googleMapsKey);

                return $location;
            });

        $mapLocations = $locationsForMap->map(function (Location $location) {
            return [
                'name' => $location->name,
                'address' => $location->address.', '.$location->postcode.' '.$location->city,
                'lat' => (float) $location->latitude,
                'lng' => (float) $location->longitude,
                'distance' => $location->distance_km !== null ? (float) $location->distance_km : null,
                'photo_url' => $location->display_photo_url,
                'detail_url' => route('locations.show', $location),
            ];
        })->values()->all();

        return view('search.index', [
            'sports' => $sports,
            'results' => $results,
            'paginatedResults' => $paginatedResults,
            'query' => $query,
            'radius' => $radius,
            'selectedSports' => $selectedSports,
            'center' => $center,
            'mapZoom' => $center ? $this->mapZoomForRadius($radius) : 7,
            'mapCenterLat' => $center ? (float) $center->latitude : 52.1326,
            'mapCenterLng' => $center ? (float) $center->longitude : 5.2913,
            'hasSearchCenter' => $center !== null,
            'googleMapsKey' => $googleMapsKey,
            'mapLocations' => $mapLocations,
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

            return "https://maps.googleapis.com/maps/api/streetview?size=640x360&location={$locationParam}&fov=90&pitch=0&key={$googleMapsKey}";
        }

        return 'https://placehold.co/640x360/eaf1f7/587089?text=Geen+foto';
    }

    private function mapZoomForRadius(int $radius): int
    {
        return match ($radius) {
            5 => 12,
            10 => 11,
            20 => 10,
            50 => 9,
            default => 8,
        };
    }

    private function haversineDistanceKm(
        float $lat1,
        float $lon1,
        float $lat2,
        float $lon2
    ): float {
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
