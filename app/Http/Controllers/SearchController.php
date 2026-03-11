<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Sport;
use App\Services\GoogleGeocodingService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class SearchController extends Controller
{
    public function suggestions(Request $request)
    {
        $term = trim((string) $request->query('q', ''));
        $limit = max(4, min(12, (int) $request->integer('limit', 8)));

        if ($term === '') {
            return response()->json(['items' => []]);
        }

        $baseQuery = Location::query()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude');
        $this->applyPublicLocationExclusions($baseQuery);

        $hasKvk = (clone $baseQuery)
            ->where('source', 'kvk')
            ->exists();

        if ($hasKvk) {
            $baseQuery->where('source', 'kvk');
        }

        $postcode = $this->collectSuggestions($baseQuery, 'postcode', $term, 4, 'postcode');
        $city = $this->collectSuggestions($baseQuery, 'city', $term, 4, 'plaats');
        $address = $this->collectSuggestions($baseQuery, 'address', $term, 4, 'adres');
        $name = $this->collectSuggestions($baseQuery, 'name', $term, 4, 'locatie');

        $items = collect([...$postcode, ...$city, ...$address, ...$name])
            ->unique(fn (array $item) => mb_strtolower(trim($item['value'])))
            ->take($limit)
            ->values()
            ->all();

        return response()->json(['items' => $items]);
    }

    public function index(Request $request, GoogleGeocodingService $geocodingService)
    {
        $sports = Sport::query()->orderBy('name')->get();

        $query = trim((string) $request->string('q'));
        $radius = (int) $request->integer('radius', 10);
        $selectedSports = collect($request->input('sports', []))
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->values();

        $radius = in_array($radius, [5, 10, 20, 50, 250], true) ? $radius : 10;
        $applyFitnessWhitelist = $this->shouldApplyFitnessOfficialWhitelist($selectedSports, $sports);

        $center = null;
        $googleMapsKey = (string) config('services.google_maps.key');
        if ($query !== '') {
            $geo = $geocodingService->geocode($query . ', Nederland');
            if ($geo) {
                $center = (object) [
                    'latitude' => $geo['latitude'],
                    'longitude' => $geo['longitude'],
                    'city' => $query,
                    'postcode' => '',
                ];
            }

            if (!$center) {
                $center = Location::query()
                    ->where('source', 'kvk')
                    ->whereNotNull('latitude')
                    ->whereNotNull('longitude')
                    ->where(function ($subQuery) use ($query) {
                        $subQuery->where('postcode', 'like', "%{$query}%")
                            ->orWhere('city', 'like', "%{$query}%")
                            ->orWhere('address', 'like', "%{$query}%")
                            ->orWhere('name', 'like', "%{$query}%");
                    })
                    ->tap(fn (Builder $builder) => $this->applyPublicLocationExclusions($builder))
                    ->first();
            }
        }

        $locationsQuery = Location::query()
            ->with('sports')
            ->where('source', 'kvk')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->when($selectedSports->isNotEmpty(), function ($q) use ($selectedSports) {
                $q->whereHas('sports', function ($sportQuery) use ($selectedSports) {
                    $sportQuery->whereIn('sports.id', $selectedSports);
                });
            });
        if ($applyFitnessWhitelist) {
            $this->applyFitnessOfficialWhitelist($locationsQuery);
        }
        $this->applyPublicLocationExclusions($locationsQuery);
        $totalKvkLocations = Location::query()
            ->where('source', 'kvk')
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->tap(fn (Builder $builder) => $this->applyPublicLocationExclusions($builder))
            ->count();
        $isUsingFallbackSource = false;

        if ($totalKvkLocations === 0) {
            $isUsingFallbackSource = true;
            $locationsQuery = Location::query()
                ->with('sports')
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->when($selectedSports->isNotEmpty(), function ($q) use ($selectedSports) {
                    $q->whereHas('sports', function ($sportQuery) use ($selectedSports) {
                    $sportQuery->whereIn('sports.id', $selectedSports);
                });
            });
            if ($applyFitnessWhitelist) {
                $this->applyFitnessOfficialWhitelist($locationsQuery);
            }
            $this->applyPublicLocationExclusions($locationsQuery);
        }

        $results = collect();
        $paginatedResults = null;
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
                    $location->display_logo_url = $this->resolveLogoUrl($location);
                    $location->fallback_photo_url = $this->defaultPhotoDataUri($location->name);
                    $openingHours = $this->resolveOpeningHoursForDisplay($location);
                    $location->opening_hours_today = $openingHours['today'];
                    $location->opening_hours_week = $openingHours['week'];

                    return $location;
                })
                ->unique(function (Location $location) {
                    return mb_strtolower(trim(implode('|', [
                        (string) $location->name,
                        (string) $location->postcode,
                        (string) $location->city,
                        (string) $location->address,
                    ])));
                })
                ->values();
        } else {
            $results = (clone $locationsQuery)
                ->when($query !== '', function ($q) use ($query) {
                    $q->where(function ($subQuery) use ($query) {
                        $subQuery->where('postcode', 'like', "%{$query}%")
                            ->orWhere('city', 'like', "%{$query}%")
                            ->orWhere('address', 'like', "%{$query}%")
                            ->orWhere('name', 'like', "%{$query}%");
                    });
                })
                ->orderBy('city')
                ->orderBy('name')
                ->get()
                ->map(function (Location $location) use ($googleMapsKey) {
                    $location->distance_km = null;
                    $location->display_photo_url = $this->resolvePhotoUrl($location, $googleMapsKey);
                    $location->display_logo_url = $this->resolveLogoUrl($location);
                    $location->fallback_photo_url = $this->defaultPhotoDataUri($location->name);
                    $openingHours = $this->resolveOpeningHoursForDisplay($location);
                    $location->opening_hours_today = $openingHours['today'];
                    $location->opening_hours_week = $openingHours['week'];

                    return $location;
                })
                ->unique(function (Location $location) {
                    return mb_strtolower(trim(implode('|', [
                        (string) $location->name,
                        (string) $location->postcode,
                        (string) $location->city,
                        (string) $location->address,
                    ])));
                })
                ->values();
        }

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

        $locationsForMap = $results;

        $mapLocations = $locationsForMap->map(function (Location $location) {
            return [
                'name' => $location->name,
                'address' => $location->address.', '.$location->postcode.' '.$location->city,
                'lat' => (float) $location->latitude,
                'lng' => (float) $location->longitude,
                'distance' => $location->distance_km !== null ? (float) $location->distance_km : null,
                'photo_url' => $location->display_photo_url,
                'logo_url' => $location->display_logo_url,
                'fallback_photo_url' => $location->fallback_photo_url,
                'detail_url' => route('locations.show', $location),
                'sports' => $location->sports->pluck('name')->values()->all(),
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
            'totalKvkLocations' => $totalKvkLocations,
            'isUsingFallbackSource' => $isUsingFallbackSource,
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

        return $this->defaultPhotoDataUri($location->name);
    }

    private function resolveLogoUrl(Location $location): ?string
    {
        if (is_string($location->logo_url) && trim($location->logo_url) !== '') {
            return trim($location->logo_url);
        }

        $brandLogo = $this->resolveOfficialGymBrandLogo($location);
        if ($brandLogo !== null) {
            return $brandLogo;
        }

        if (!is_string($location->website) || trim($location->website) === '') {
            return null;
        }

        $website = trim($location->website);
        if (!str_starts_with($website, 'http://') && !str_starts_with($website, 'https://')) {
            $website = 'https://'.$website;
        }

        $host = parse_url($website, PHP_URL_HOST);
        if (!is_string($host) || trim($host) === '') {
            return null;
        }

        return 'https://www.google.com/s2/favicons?domain='.rawurlencode($host).'&sz=256';
    }

    private function resolveOfficialGymBrandLogo(Location $location): ?string
    {
        $name = mb_strtolower(trim((string) $location->name));
        if ($name === '') {
            return null;
        }

        $brandDomains = [
            'basic-fit' => [
                'keywords' => ['basic fit', 'basic-fit', 'basicfit'],
                'domain' => 'basic-fit.com',
                'logo_file' => 'basic-fit.png',
            ],
            'trainmore' => [
                'keywords' => ['trainmore'],
                'domain' => 'trainmore.nl',
                'logo_file' => 'trainmore.png',
            ],
            'fit-for-free' => [
                'keywords' => ['fit for free', 'fitforfree', 'fit-for-free'],
                'domain' => 'fitforfree.nl',
                'logo_file' => 'fit-for-free.png',
            ],
            'sportcity' => [
                'keywords' => ['sportcity'],
                'domain' => 'sportcity.nl',
                'logo_file' => 'sportcity.png',
            ],
            'mylife' => [
                'keywords' => ['mylife', 'my life'],
                'domain' => 'mylife.nl',
                'logo_file' => 'mylife.png',
            ],
            'anytime-fitness' => [
                'keywords' => ['anytime fitness', 'anytimefitness'],
                'domain' => 'anytimefitness.nl',
                'logo_file' => 'anytime-fitness.png',
            ],
            'biggym' => [
                'keywords' => ['biggym', 'big gym'],
                'domain' => 'biggym.nl',
                'logo_file' => 'biggym.png',
            ],
            'invictus' => [
                'keywords' => ['invictus'],
                'domain' => 'invictusgym.nl',
                'logo_file' => 'invictus.png',
            ],
            'club-pellikaan' => [
                'keywords' => ['club pellikaan', 'clubpellikaan'],
                'domain' => 'clubpellikaan.nl',
                'logo_file' => 'club-pellikaan.png',
            ],
            'snap-fitness' => [
                'keywords' => ['snap fitness', 'snapfitness'],
                'domain' => 'snapfitness.com',
                'logo_file' => 'snap-fitness.png',
            ],
            'david-lloyd' => [
                'keywords' => ['david lloyd', 'davidlloyd'],
                'domain' => 'davidlloyd.nl',
                'logo_file' => 'david-lloyd.png',
            ],
            'sportplaza' => [
                'keywords' => ['sportplaza'],
                'domain' => 'sportplaza.nl',
                'logo_file' => 'sportplaza.png',
            ],
        ];

        foreach ($brandDomains as $brand) {
            foreach ($brand['keywords'] as $keyword) {
                if (!str_contains($name, $keyword)) {
                    continue;
                }

                // Prefer local logo when available in public/brand-logos/<logo_file>.
                $logoFile = (string) ($brand['logo_file'] ?? '');
                if ($logoFile !== '') {
                    $localPath = public_path("brand-logos/{$logoFile}");
                    if (is_file($localPath)) {
                        return asset("brand-logos/{$logoFile}");
                    }
                }

                return 'https://logo.clearbit.com/'.rawurlencode($brand['domain']);
            }
        }

        return null;
    }

    private function defaultPhotoDataUri(string $name): string
    {
        $safeName = trim($name) !== '' ? trim($name) : 'GymMaps locatie';
        $safeName = htmlspecialchars($safeName, ENT_QUOTES, 'UTF-8');

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="640" height="360" viewBox="0 0 640 360">
  <defs>
    <linearGradient id="g" x1="0" x2="1" y1="0" y2="1">
      <stop offset="0%" stop-color="#e8f1f8"/>
      <stop offset="100%" stop-color="#d8e8f3"/>
    </linearGradient>
  </defs>
  <rect width="640" height="360" fill="url(#g)"/>
  <circle cx="120" cy="90" r="56" fill="#95c11f" fill-opacity="0.25"/>
  <circle cx="545" cy="286" r="72" fill="#0f4f7c" fill-opacity="0.18"/>
  <text x="320" y="162" text-anchor="middle" font-family="Segoe UI, Arial, sans-serif" font-size="28" fill="#1a4a70" font-weight="700">Geen foto beschikbaar</text>
  <text x="320" y="202" text-anchor="middle" font-family="Segoe UI, Arial, sans-serif" font-size="18" fill="#3f6280">{$safeName}</text>
</svg>
SVG;

        return 'data:image/svg+xml;charset=UTF-8,'.rawurlencode($svg);
    }

    private function applyPublicLocationExclusions(Builder $query): Builder
    {
        $personalTrainerTerms = [
            'personal trainer',
            'personaltraining',
            'pt studio',
            'pt-studio',
            'pt ',
            'coach',
        ];
        $nonSportBusinessTerms = [
            'mondzorg',
            'tandarts',
            'dental',
            'fysiotherapie',
            'fysio',
            'huisarts',
            'apotheek',
        ];

        $query->whereDoesntHave('sports', function (Builder $sportQuery) {
            $sportQuery->where('slug', 'personal-trainer');
        });

        $query->where(function (Builder $q) use ($personalTrainerTerms) {
            foreach ($personalTrainerTerms as $term) {
                $like = '%'.mb_strtolower($term).'%';
                $q->whereRaw('LOWER(name) NOT LIKE ?', [$like]);
            }
        });

        $query->where(function (Builder $q) use ($nonSportBusinessTerms) {
            foreach ($nonSportBusinessTerms as $term) {
                $like = '%'.mb_strtolower($term).'%';
                $q->whereRaw('LOWER(name) NOT LIKE ?', [$like]);
            }
        });

        return $query;
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

    private function collectSuggestions($baseQuery, string $column, string $term, int $max, string $type): array
    {
        $startsWith = (clone $baseQuery)
            ->where($column, 'like', $term.'%')
            ->where($column, '!=', '')
            ->distinct()
            ->limit($max)
            ->pluck($column)
            ->map(fn ($value) => trim((string) $value))
            ->filter()
            ->values();

        $remaining = max(0, $max - $startsWith->count());
        $contains = collect();
        if ($remaining > 0) {
            $contains = (clone $baseQuery)
                ->where($column, 'like', '%'.$term.'%')
                ->where($column, 'not like', $term.'%')
                ->where($column, '!=', '')
                ->distinct()
                ->limit($remaining)
                ->pluck($column)
                ->map(fn ($value) => trim((string) $value))
                ->filter()
                ->values();
        }

        return $startsWith
            ->concat($contains)
            ->map(fn (string $value) => [
                'value' => $value,
                'type' => $type,
                'label' => $value.' ('.$type.')',
            ])
            ->values()
            ->all();
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

    private function shouldApplyFitnessOfficialWhitelist(Collection $selectedSports, Collection $sports): bool
    {
        if ($selectedSports->count() !== 1) {
            return false;
        }

        $fitnessSport = $sports->firstWhere('slug', 'fitness');
        if ($fitnessSport === null) {
            return false;
        }

        return $selectedSports->contains((int) $fitnessSport->id);
    }

    private function applyFitnessOfficialWhitelist(Builder $query): void
    {
        $terms = [
            'basic fit',
            'basic-fit',
            'basicfit',
            'trainmore',
            'fit for free',
            'fit-for-free',
            'fitforfree',
            'sportcity',
            'mylife',
            'my life',
            'anytime fitness',
            'anytimefitness',
            'big gym',
            'biggym',
            'invictus',
            'club pellikaan',
            'clubpellikaan',
            'snap fitness',
            'snapfitness',
            'david lloyd',
            'davidlloyd',
            'sportplaza',
        ];

        $query->where(function (Builder $nameQuery) use ($terms) {
            foreach ($terms as $term) {
                $like = '%'.mb_strtolower($term).'%';
                $nameQuery->orWhereRaw('LOWER(name) LIKE ?', [$like]);
            }
        });
    }
}
