<?php

namespace App\Http\Controllers;

use App\Mail\ContactQuestionMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index()
    {
        return view('pages.contact');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:3000'],
        ]);

        $toAddress = (string) config('mail.admin.address');
        if ($toAddress === '') {
            return back()
                ->withInput()
                ->withErrors([
                    'mail' => 'Contactformulier kon niet versturen: ADMIN_EMAIL ontbreekt.',
                ]);
        }

        Mail::to($toAddress)->send(new ContactQuestionMail($validated));

        return redirect()
            ->route('pages.contact')
            ->with('status', 'Bedankt! Je vraag is verstuurd. We reageren zo snel mogelijk.');
    }
}
