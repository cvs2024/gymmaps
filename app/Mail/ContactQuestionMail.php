<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactQuestionMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public array $payload)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nieuwe vraag via contactformulier: '.$this->payload['subject'],
            replyTo: [
                [
                    'address' => $this->payload['email'],
                    'name' => $this->payload['name'],
                ],
            ],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-question',
            with: [
                'payload' => $this->payload,
            ],
        );
    }
}
