<?php

namespace App\Http\Controllers;

use App\Models\GymbuddyPost;
use Illuminate\Http\Request;

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

        return redirect()
            ->route('gymbuddy.index')
            ->with('status', 'Je Gymbuddy oproep is geplaatst.');
    }
}
