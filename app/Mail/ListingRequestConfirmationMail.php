<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ListingRequestConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $contactName
    ) {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bedankt voor je aanmelding bij GymMaps.nl',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.listing-request-confirmation',
            with: [
                'contactName' => $this->contactName,
            ],
        );
    }
}

