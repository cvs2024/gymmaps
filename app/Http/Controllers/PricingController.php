<?php

namespace App\Http\Controllers;

use App\Mail\AdminFormSubmissionMail;
use App\Mail\PricingRequestConfirmationMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PricingController extends Controller
{
    public function index()
    {
        return view('pages.pricing');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'gym_name' => ['required', 'string', 'max:255'],
            'contact_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'website' => ['nullable', 'url', 'max:500'],
            'notes' => ['nullable', 'string', 'max:3000'],
        ]);

        $adminAddress = (string) config('mail.admin.address');
        if ($adminAddress === '') {
            return back()
                ->withInput()
                ->withErrors([
                    'mail' => 'Aanvraag versturen lukt nu niet: admin e-mailadres ontbreekt.',
                ]);
        }

        if ($this->notifyAdmin($validated, $adminAddress) !== null) {
            return back()
                ->withInput()
                ->withErrors([
                    'mail' => 'Aanvraag is niet verzonden door een mailfout. Probeer het later opnieuw.',
                ]);
        }

        $submitterMailError = $this->notifySubmitter($validated);
        if ($submitterMailError !== null) {
            return redirect()
                ->route('pages.pricing')
                ->with('status', 'Bedankt! We hebben je premium aanvraag ontvangen. De bevestigingsmail kon nu niet worden verstuurd.');
        }

        return redirect()
            ->route('pages.pricing')
            ->with('status', 'Bedankt! We nemen zo snel mogelijk contact met je op over jouw premium vermelding op GymMaps.');
    }

    private function notifyAdmin(array $payload, string $adminAddress): ?string
    {
        try {
            Mail::to($adminAddress)->send(new AdminFormSubmissionMail(
                'Nieuwe Premium aanvraag (Tarieven)',
                [
                    'Naam sportschool' => $payload['gym_name'] ?? null,
                    'Naam contactpersoon' => $payload['contact_name'] ?? null,
                    'E-mailadres' => $payload['email'] ?? null,
                    'Telefoonnummer' => $payload['phone'] ?? null,
                    'Plaats' => $payload['city'] ?? null,
                    'Website' => $payload['website'] ?? null,
                    'Opmerkingen' => $payload['notes'] ?? null,
                ],
                $payload['email'] ?? null,
                $payload['contact_name'] ?? null
            ));

            return null;
        } catch (\Throwable $e) {
            Log::warning('Kon premium aanvraag (admin) niet versturen.', [
                'error' => $e->getMessage(),
                'to' => $adminAddress,
            ]);

            return $e->getMessage();
        }
    }

    private function notifySubmitter(array $payload): ?string
    {
        $email = trim((string) ($payload['email'] ?? ''));
        if ($email === '') {
            return null;
        }

        try {
            Mail::to($email)->send(new PricingRequestConfirmationMail(
                (string) ($payload['contact_name'] ?? '')
            ));

            return null;
        } catch (\Throwable $e) {
            Log::warning('Kon premium bevestigingsmail niet versturen.', [
                'error' => $e->getMessage(),
                'email' => $email,
            ]);

            return $e->getMessage();
        }
    }
}
