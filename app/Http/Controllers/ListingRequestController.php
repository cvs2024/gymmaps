<?php

namespace App\Http\Controllers;

use App\Models\ListingRequest;
use Illuminate\Http\Request;

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
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo_path'] = $request->file('photo')->store('listing-requests', 'public');
        }

        unset($validated['photo']);

        ListingRequest::query()->create($validated);

        return redirect()
            ->route('home')
            ->with('status', 'Bedankt! Je aanmelding is ontvangen en wordt snel gecontroleerd.');
    }
}
