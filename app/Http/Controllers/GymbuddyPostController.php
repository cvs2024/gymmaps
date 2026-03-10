<?php

namespace App\Http\Controllers;

use App\Mail\AdminFormSubmissionMail;
use App\Models\GymbuddyPost;
use App\Models\Location;
use App\Services\GoogleGeocodingService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class GymbuddyPostController extends Controller
{
    private array $postcodeCoordinateCache = [];
    private array $cityCoordinateCache = [];

    public function index(Request $request, GoogleGeocodingService $geocodingService)
    {
        $query = trim((string) $request->string('q'));
        $sport = trim((string) $request->string('sport'));
        $radius = (int) $request->integer('radius', 10);
        $radius = in_array($radius, [5, 10, 20, 50, 100], true) ? $radius : 10;

        $center = null;
        if ($query !== '') {
            $geo = $geocodingService->geocode($query . ', Nederland');
            if ($geo) {
                $center = (object) [
                    'latitude' => (float) $geo['latitude'],
                    'longitude' => (float) $geo['longitude'],
                    'label' => $query,
                ];
            }

            if (!$center) {
                $matchedLocation = Location::query()
                    ->whereNotNull('latitude')
                    ->whereNotNull('longitude')
                    ->where(function ($subQuery) use ($query) {
                        $subQuery->where('postcode', 'like', "%{$query}%")
                            ->orWhere('city', 'like', "%{$query}%")
                            ->orWhere('address', 'like', "%{$query}%")
                            ->orWhere('name', 'like', "%{$query}%");
                    })
                    ->orderByRaw("source = 'kvk' desc")
                    ->first();

                if ($matchedLocation) {
                    $center = (object) [
                        'latitude' => (float) $matchedLocation->latitude,
                        'longitude' => (float) $matchedLocation->longitude,
                        'label' => $matchedLocation->city ?: $query,
                    ];
                }
            }
        }

        $results = GymbuddyPost::query()
            ->where('is_active', true)
            ->latest()
            ->get()
            ->map(function (GymbuddyPost $post) {
                $coordinates = $this->resolvePostCoordinates($post);
                $post->latitude = $coordinates['latitude'];
                $post->longitude = $coordinates['longitude'];
                $post->profile_photo_url = $this->resolveProfilePhotoUrl($post);
                $post->distance_km = null;

                return $post;
            });

        if ($sport !== '') {
            $sportNeedle = mb_strtolower($sport);
            $results = $results->filter(function (GymbuddyPost $post) use ($sportNeedle) {
                return str_contains(mb_strtolower($post->sport), $sportNeedle);
            });
        }

        if ($query !== '') {
            if ($center) {
                $results = $results
                    ->map(function (GymbuddyPost $post) use ($center) {
                        if ($post->latitude === null || $post->longitude === null) {
                            $post->distance_km = null;

                            return $post;
                        }

                        $distance = $this->haversineDistanceKm(
                            (float) $center->latitude,
                            (float) $center->longitude,
                            (float) $post->latitude,
                            (float) $post->longitude
                        );
                        $post->distance_km = round($distance, 1);

                        return $post;
                    })
                    ->filter(function (GymbuddyPost $post) use ($radius) {
                        return $post->distance_km !== null && $post->distance_km <= $radius;
                    })
                    ->sortBy('distance_km')
                    ->values();
            } else {
                $needle = mb_strtolower($query);
                $results = $results->filter(function (GymbuddyPost $post) use ($needle) {
                    $haystack = mb_strtolower(implode(' ', array_filter([
                        $post->city,
                        $post->postcode,
                        $post->address,
                        $post->sport,
                    ])));

                    return str_contains($haystack, $needle);
                })->values();
            }
        }

        $perPage = 9;
        $page = max(1, (int) $request->integer('page', 1));
        $posts = new LengthAwarePaginator(
            $results->forPage($page, $perPage)->values(),
            $results->count(),
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        return view('gymbuddy.index', [
            'posts' => $posts,
            'query' => $query,
            'sport' => $sport,
            'radius' => $radius,
            'hasSearchCenter' => $center !== null,
            'searchCenterLabel' => $center->label ?? null,
        ]);
    }

    public function store(Request $request)
    {
        $uploadedPhoto = $request->file('profile_photo');
        if ($uploadedPhoto && !$uploadedPhoto->isValid()) {
            return back()
                ->withErrors([
                    'profile_photo' => 'Upload van de foto is mislukt. Gebruik een JPG, PNG of WEBP tot 10 MB en probeer opnieuw.',
                ])
                ->withInput();
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'sport' => ['required', 'string', 'max:255'],
            'days_per_week' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string', 'max:255'],
            'postcode' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'gender_preference' => ['nullable', 'string', 'in:geen_voorkeur,vrouw,man,maakt_niet_uit'],
            'about_you' => ['required', 'string', 'max:3000'],
            'search_message' => ['required', 'string', 'max:3000'],
            'profile_photo' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
        ], [
            'profile_photo.file' => 'De profielfoto is ongeldig. Kies een geldig bestand.',
            'profile_photo.mimes' => 'Gebruik een JPG, JPEG, PNG of WEBP bestand voor de profielfoto.',
            'profile_photo.max' => 'De profielfoto mag maximaal 10 MB groot zijn.',
        ]);

        $photoPath = $request->file('profile_photo')?->store('gymbuddy-photos', 'public');
        unset($validated['profile_photo']);

        $payload = [
            ...$validated,
            'profile_photo_path' => $photoPath,
        ];

        GymbuddyPost::query()->create($payload);
        $this->notifyAdmin($payload);

        return redirect()
            ->to(route('gymbuddy.index') . '#actieve-oproepen')
            ->with('status', 'Je Gymbuddy oproep is geplaatst.');
    }

    private function resolvePostCoordinates(GymbuddyPost $post): array
    {
        $postcode = strtoupper(str_replace(' ', '', trim((string) $post->postcode)));
        if ($postcode !== '') {
            if (array_key_exists($postcode, $this->postcodeCoordinateCache)) {
                return $this->postcodeCoordinateCache[$postcode];
            }

            $match = Location::query()
                ->select(['latitude', 'longitude'])
                ->whereNotNull('latitude')
                ->whereNotNull('longitude')
                ->whereRaw("REPLACE(UPPER(postcode), ' ', '') = ?", [$postcode])
                ->orderByRaw("source = 'kvk' desc")
                ->first();

            if ($match) {
                return $this->postcodeCoordinateCache[$postcode] = [
                    'latitude' => (float) $match->latitude,
                    'longitude' => (float) $match->longitude,
                ];
            }

            $this->postcodeCoordinateCache[$postcode] = [
                'latitude' => null,
                'longitude' => null,
            ];
        }

        $city = mb_strtolower(trim((string) $post->city));
        if ($city === '') {
            return ['latitude' => null, 'longitude' => null];
        }

        if (array_key_exists($city, $this->cityCoordinateCache)) {
            return $this->cityCoordinateCache[$city];
        }

        $match = Location::query()
            ->select(['latitude', 'longitude'])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->whereRaw('LOWER(city) = ?', [$city])
            ->orderByRaw("source = 'kvk' desc")
            ->first();

        if ($match) {
            return $this->cityCoordinateCache[$city] = [
                'latitude' => (float) $match->latitude,
                'longitude' => (float) $match->longitude,
            ];
        }

        return $this->cityCoordinateCache[$city] = [
            'latitude' => null,
            'longitude' => null,
        ];
    }

    private function resolveProfilePhotoUrl(GymbuddyPost $post): string
    {
        if (is_string($post->profile_photo_path) && trim($post->profile_photo_path) !== '') {
            return asset('storage/' . ltrim($post->profile_photo_path, '/'));
        }

        return $this->defaultAvatarDataUri((string) $post->name);
    }

    private function defaultAvatarDataUri(string $name): string
    {
        $safeName = trim($name) !== '' ? trim($name) : 'Gymbuddy';
        $safeName = htmlspecialchars($safeName, ENT_QUOTES, 'UTF-8');
        $initial = mb_strtoupper(mb_substr($safeName, 0, 1));

        $svg = <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="240" height="240" viewBox="0 0 240 240">
  <defs>
    <linearGradient id="g" x1="0" y1="0" x2="1" y2="1">
      <stop offset="0%" stop-color="#cfe6f6"/>
      <stop offset="100%" stop-color="#a8cbe3"/>
    </linearGradient>
  </defs>
  <rect width="240" height="240" fill="url(#g)"/>
  <circle cx="120" cy="120" r="70" fill="#0f5e88" fill-opacity="0.18"/>
  <text x="120" y="136" text-anchor="middle" font-family="Segoe UI, Arial, sans-serif" font-size="72" font-weight="700" fill="#0f4f7c">{$initial}</text>
  <title>{$safeName}</title>
</svg>
SVG;

        return 'data:image/svg+xml;charset=UTF-8,' . rawurlencode($svg);
    }

    private function haversineDistanceKm(float $latFrom, float $lngFrom, float $latTo, float $lngTo): float
    {
        $earthRadius = 6371;

        $latDelta = deg2rad($latTo - $latFrom);
        $lngDelta = deg2rad($lngTo - $lngFrom);

        $a = sin($latDelta / 2) ** 2
            + cos(deg2rad($latFrom)) * cos(deg2rad($latTo))
            * sin($lngDelta / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    private function notifyAdmin(array $payload): void
    {
        $adminAddress = (string) config('mail.admin.address');
        if ($adminAddress === '') {
            return;
        }

        try {
            Mail::to($adminAddress)->send(new AdminFormSubmissionMail(
                'Nieuwe gymbuddy oproep',
                [
                    'Naam' => $payload['name'] ?? null,
                    'E-mail' => $payload['email'] ?? null,
                    'Sport' => $payload['sport'] ?? null,
                    'Dagen per week' => $payload['days_per_week'] ?? null,
                    'Adres' => $payload['address'] ?? null,
                    'Postcode' => $payload['postcode'] ?? null,
                    'Plaats' => $payload['city'] ?? null,
                    'Voorkeur geslacht' => $payload['gender_preference'] ?? null,
                    'Over mij' => $payload['about_you'] ?? null,
                    'Zoekopdracht' => $payload['search_message'] ?? null,
                    'Foto' => !empty($payload['profile_photo_path']) ? asset('storage/' . ltrim((string) $payload['profile_photo_path'], '/')) : null,
                ],
                $payload['email'] ?? null,
                $payload['name'] ?? null
            ));
        } catch (\Throwable $e) {
            Log::warning('Kon admin-mail voor gymbuddy niet versturen.', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
