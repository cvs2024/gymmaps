<?php

namespace App\Http\Controllers;

use App\Models\PersonalTrainerRequest;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class PersonalTrainerRequestController extends Controller
{
    public function index()
    {
        if (!Schema::hasTable('personal_trainer_requests')) {
            return view('pages.personal-trainer', [
                'requests' => new LengthAwarePaginator([], 0, 12, 1),
            ]);
        }

        return view('pages.personal-trainer', [
            'requests' => PersonalTrainerRequest::query()
                ->where('is_active', true)
                ->latest()
                ->paginate(12),
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

        return redirect()
            ->route('pages.personal-trainer')
            ->with('status', 'Je oproep voor een personal trainer is geplaatst.');
    }
}
