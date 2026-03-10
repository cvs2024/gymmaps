<?php

namespace App\Http\Controllers;

use App\Mail\AdminFormSubmissionMail;
use App\Models\GymbuddyPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class GymbuddyPostController extends Controller
{
    public function index()
    {
        return view('gymbuddy.index', [
            'posts' => GymbuddyPost::query()
                ->where('is_active', true)
                ->latest()
                ->paginate(12),
        ]);
    }

    public function store(Request $request)
    {
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
        ]);

        GymbuddyPost::query()->create($validated);
        $this->notifyAdmin($validated);

        return redirect()
            ->route('gymbuddy.index')
            ->with('status', 'Je Gymbuddy oproep is geplaatst.');
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
