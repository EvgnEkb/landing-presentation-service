<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;

class OwnerMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public array $data)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            to: [config('mail.owner_email')],
            subject: 'Новое сообщение с сайта',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.owner',
            with: ['data' => $this->data],
        );
    }
}
