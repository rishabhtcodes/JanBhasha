<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactConfirmationMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public readonly string $senderName,
        public readonly string $senderEmail,
        public readonly string $subject,
        public readonly string $reason,
    ) {}

    /**
     * Get the message envelope.
     * This goes to the user who submitted the form.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'We Received Your Message — JanBhasha Support',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.contact-confirmation',
        );
    }
}
