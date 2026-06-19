<?php

namespace App\Mail;

use App\Models\Pengaduan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PengaduanBaruAdmin extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Buat instance Mailable baru.
     */
    public function __construct(
        public readonly Pengaduan $pengaduan
    ) {}

    /**
     * Subject dan metadata email.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[SILPM] Pengaduan Baru Masuk — #' . $this->pengaduan->id . ': ' . \Str::limit($this->pengaduan->subjek, 60),
        );
    }

    /**
     * Konten email — view blade.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.pengaduan-baru-admin',
        );
    }

    /**
     * Attachment (tidak ada di Fase 2).
     */
    public function attachments(): array
    {
        return [];
    }
}
