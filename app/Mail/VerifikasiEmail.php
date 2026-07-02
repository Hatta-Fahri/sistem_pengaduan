<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class VerifikasiEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Buat instance Mailable baru.
     */
    public function __construct(
        public readonly string $userName,
        public readonly string $verificationUrl,
    ) {}

    /**
     * Subject dan metadata email.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[SILPM] Verifikasi Alamat Email Anda',
        );
    }

    /**
     * Konten email — view blade kustom.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.verifikasi-email',
        );
    }

    /**
     * Tidak ada attachment.
     */
    public function attachments(): array
    {
        return [];
    }
}
