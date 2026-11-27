<?php

namespace App\Mail;

use App\Models\User;
use App\Services\EmailVerificationService;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class VerificationCodeMail extends Mailable
{
    public int $minutes = EmailVerificationService::TTL_MINUTES;

    public function __construct(
        public User $user,
        public string $code,
        public string $purpose,
        public string $cancelUrl,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: match ($this->purpose) {
                'new_device' => 'Novo acesso à sua conta no Portal Etec',
                'first_access' => 'Confirme o e-mail da sua conta de aluno no Portal Etec',
                default => 'Confirme seu e-mail no Portal Etec',
            },
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.verification-code');
    }
}
