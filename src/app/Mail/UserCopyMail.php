<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;

class UserCopyMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public array $data)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            to: [$this->data['email']],
            subject: 'Копия вашего обращения',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.user_copy',
            with: ['data' => $this->data],
        );
    }
}
