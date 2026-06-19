<?php

namespace App\Mail;

use App\Models\Pengaduan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StatusDiperbarui extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Buat instance Mailable baru.
     *
     * @param  Pengaduan  $pengaduan  Pengaduan yang statusnya diperbarui (sudah fresh)
     * @param  string  $statusLama  Nilai status sebelum diubah
     */
    public function __construct(
        public readonly Pengaduan $pengaduan,
        public readonly string $statusLama
    ) {}

    /**
     * Subject dan metadata email.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[SILPM] Status Pengaduan #' . $this->pengaduan->id . ' Telah Diperbarui',
        );
    }

    /**
     * Konten email — view blade.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.status-diperbarui',
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
