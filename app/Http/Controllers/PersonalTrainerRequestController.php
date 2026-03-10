<?php

namespace App\Http\Controllers;

use App\Mail\AdminFormSubmissionMail;
use App\Models\Location;
use App\Models\PersonalTrainerRequest;
use App\Services\GoogleGeocodingService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;

class PersonalTrainerRequestController extends Controller
{
    private array $cityCoordinateCache = [];

    public function index(Request $request, GoogleGeocodingService $geocodingService)
    {
        if (!Schema::hasTable('personal_trainer_requests')) {
            return view('pages.personal-trainer', [
                'requests' => new LengthAwarePaginator([], 0, 12, 1),
                'query' => '',
                'sport' => '',
                'radius' => 10,
                'hasSearchCenter' => false,
                'searchCenterLabel' => null,
            ]);
        }

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
            } else {
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

        $results = PersonalTrainerRequest::query()
            ->where('is_active', true)
            ->latest()
            ->get()
            ->map(function (PersonalTrainerRequest $requestItem) {
                $coordinates = $this->resolveRequestCoordinates($requestItem);
                $requestItem->latitude = $coordinates['latitude'];
                $requestItem->longitude = $coordinates['longitude'];
                $requestItem->distance_km = null;

                return $requestItem;
            });

        if ($sport !== '') {
            $sportNeedle = mb_strtolower($sport);
            $results = $results->filter(function (PersonalTrainerRequest $requestItem) use ($sportNeedle) {
                $haystack = mb_strtolower(implode(' ', array_filter([
                    $requestItem->sport_focus,
                    $requestItem->goal,
                    $requestItem->message,
                ])));

                return str_contains($haystack, $sportNeedle);
            });
        }

        if ($query !== '') {
            if ($center) {
                $results = $results
                    ->map(function (PersonalTrainerRequest $requestItem) use ($center) {
                        if ($requestItem->latitude === null || $requestItem->longitude === null) {
                            $requestItem->distance_km = null;

                            return $requestItem;
                        }

                        $distance = $this->haversineDistanceKm(
                            (float) $center->latitude,
                            (float) $center->longitude,
                            (float) $requestItem->latitude,
                            (float) $requestItem->longitude
                        );
                        $requestItem->distance_km = round($distance, 1);

                        return $requestItem;
                    })
                    ->filter(function (PersonalTrainerRequest $requestItem) use ($radius) {
                        return $requestItem->distance_km !== null && $requestItem->distance_km <= $radius;
                    })
                    ->sortBy('distance_km')
                    ->values();
            } else {
                $needle = mb_strtolower($query);
                $results = $results->filter(function (PersonalTrainerRequest $requestItem) use ($needle) {
                    $haystack = mb_strtolower(implode(' ', array_filter([
                        $requestItem->city,
                        $requestItem->training_location,
                        $requestItem->sport_focus,
                        $requestItem->goal,
                        $requestItem->message,
                    ])));

                    return str_contains($haystack, $needle);
                })->values();
            }
        }

        $perPage = 9;
        $page = max(1, (int) $request->integer('page', 1));
        $paginated = new LengthAwarePaginator(
            $results->forPage($page, $perPage)->values(),
            $results->count(),
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'query' => $request->query(),
            ]
        );

        return view('pages.personal-trainer', [
            'requests' => $paginated,
            'query' => $query,
            'sport' => $sport,
            'radius' => $radius,
            'hasSearchCenter' => $center !== null,
            'searchCenterLabel' => $center->label ?? null,
        ]);
    }

    public function store(Request $request)
    {
        if (!Schema::hasTable('personal_trainer_requests')) {
            return back()->withErrors([
                'db' => 'De personal trainer tabel ontbreekt nog. Draai eerst de migraties op live.',
            ]);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255', 'required_without:phone'],
            'phone' => ['nullable', 'string', 'max:255', 'required_without:email'],
            'training_location' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'days_per_week' => ['required', 'string', 'max:255'],
            'sport_focus' => ['required', 'string', 'max:255'],
            'max_rate' => ['nullable', 'numeric', 'min:0', 'max:99999.99'],
            'goal' => ['nullable', 'string', 'max:1200'],
            'message' => ['nullable', 'string', 'max:3000'],
        ], [
            'email.required_without' => 'Vul minimaal een e-mailadres of telefoonnummer in.',
            'phone.required_without' => 'Vul minimaal een telefoonnummer of e-mailadres in.',
        ]);

        PersonalTrainerRequest::query()->create($validated);
        $this->notifyAdmin($validated);

        return redirect()
            ->to(route('pages.personal-trainer') . '#actieve-oproepen')
            ->with('status', 'Je oproep voor een personal trainer is geplaatst.');
    }

    private function resolveRequestCoordinates(PersonalTrainerRequest $requestItem): array
    {
        $city = mb_strtolower(trim((string) $requestItem->city));
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
                'Nieuwe personal trainer oproep',
                [
                    'Naam' => $payload['name'] ?? null,
                    'E-mail' => $payload['email'] ?? null,
                    'Telefoon' => $payload['phone'] ?? null,
                    'Gewenste locatie' => $payload['training_location'] ?? null,
                    'Plaats / regio' => $payload['city'] ?? null,
                    'Dagen per week' => $payload['days_per_week'] ?? null,
                    'Sport / doel' => $payload['sport_focus'] ?? null,
                    'Max tarief' => $payload['max_rate'] ?? null,
                    'Trainingsdoel' => $payload['goal'] ?? null,
                    'Extra info' => $payload['message'] ?? null,
                ],
                $payload['email'] ?? null,
                $payload['name'] ?? null
            ));
        } catch (\Throwable $e) {
            Log::warning('Kon admin-mail voor personal trainer oproep niet versturen.', [
                'error' => $e->getMessage(),
            ]);
        }
    }
}
