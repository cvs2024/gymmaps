<?php

namespace App\Http\Controllers;

use App\Mail\AdminFormSubmissionMail;
use App\Mail\ListingRequestConfirmationMail;
use App\Models\ListingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ListingRequestController extends Controller
{
    public function create()
    {
        return view('listing_requests.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'contact_name' => ['required', 'string', 'max:255'],
            'business_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'website' => ['nullable', 'url', 'max:255'],
            'address' => ['required', 'string', 'max:255'],
            'postcode' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'sports_overview' => ['required', 'string', 'max:500'],
            'message' => ['nullable', 'string', 'max:2000'],
            'photo' => ['nullable', 'image', 'max:5120'],
            'logo_url' => ['nullable', 'url', 'max:5000'],
        ], [
            'email.required' => 'E-mailadres is verplicht zodat we je aanmelding kunnen bevestigen.',
            'email.email' => 'Vul een geldig e-mailadres in.',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo_path'] = $request->file('photo')->store('listing-requests', 'public');
        }

        unset($validated['photo']);

        ListingRequest::query()->create($validated);
        $adminMailError = $this->notifyAdmin($validated);
        $submitterMailError = $this->notifySubmitter($validated);

        if ($adminMailError || $submitterMailError) {
            return redirect()
                ->route('home')
                ->with('status', 'Je aanmelding is opgeslagen, maar er was een probleem met het versturen van e-mail. Probeer het later opnieuw of neem contact op.');
        }

        return redirect()
            ->route('home')
            ->with('status', 'Bedankt! Je aanmelding is ontvangen en wordt snel gecontroleerd.');
    }

    private function notifyAdmin(array $payload): ?string
    {
        $adminAddress = (string) config('mail.admin.address');
        if ($adminAddress === '') {
            return null;
        }

        try {
            Mail::to($adminAddress)->send(new AdminFormSubmissionMail(
                'Nieuwe sportlocatie aanmelding',
                [
                    'Contactpersoon' => $payload['contact_name'] ?? null,
                    'E-mail' => $payload['email'] ?? null,
                    'Telefoon' => $payload['phone'] ?? null,
                    'Naam locatie' => $payload['business_name'] ?? null,
                    'Website' => $payload['website'] ?? null,
                    'Adres' => $payload['address'] ?? null,
                    'Postcode' => $payload['postcode'] ?? null,
                    'Plaats' => $payload['city'] ?? null,
                    'Sportactiviteiten' => $payload['sports_overview'] ?? null,
                    'Extra toelichting' => $payload['message'] ?? null,
                    'Logo URL' => $payload['logo_url'] ?? null,
                ],
                $payload['email'] ?? null,
                $payload['contact_name'] ?? null
            ));
            return null;
        } catch (\Throwable $e) {
            Log::warning('Kon admin-mail voor listing request niet versturen.', [
                'error' => $e->getMessage(),
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
            Mail::to($email)->send(new ListingRequestConfirmationMail(
                (string) ($payload['contact_name'] ?? '')
            ));
            return null;
        } catch (\Throwable $e) {
            Log::warning('Kon bevestigingsmail voor listing request niet versturen.', [
                'error' => $e->getMessage(),
                'email' => $email,
            ]);
            return $e->getMessage();
        }
    }
}
