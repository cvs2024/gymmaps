<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminFormSubmissionMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $subjectLine,
        public array $fields,
        public ?string $replyToAddress = null,
        public ?string $replyToName = null
    ) {
    }

    public function envelope(): Envelope
    {
        $replyTo = [];
        if ($this->replyToAddress) {
            $replyTo[] = [
                'address' => $this->replyToAddress,
                'name' => $this->replyToName,
            ];
        }

        return new Envelope(
            subject: $this->subjectLine,
            replyTo: $replyTo
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin-form-submission',
            with: [
                'subjectLine' => $this->subjectLine,
                'fields' => $this->fields,
            ],
        );
    }
}
