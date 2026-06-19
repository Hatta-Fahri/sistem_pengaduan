<?php

namespace App\Mail;

use App\Models\Pengaduan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PengaduanDiterima extends Mailable implements ShouldQueue
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
            subject: '[SILPM] Pengaduan #' . $this->pengaduan->id . ' Berhasil Diterima',
        );
    }

    /**
     * Konten email — view blade.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.pengaduan-diterima',
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
