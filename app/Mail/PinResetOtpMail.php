<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PinResetOtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $otp
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kode OTP Reset PIN Transaksi - Edubank SMKN 1 Bantul',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.pin-reset-otp',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
