<?php

namespace App\Mail;

use App\Models\Pengaduan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class KonfirmasiDitolakAdmin extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Buat instance Mailable baru.
     */
    public function __construct(
        public readonly Pengaduan $pengaduan,
        public readonly string $alasan
    ) {}

    /**
     * Subject dan metadata email.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[SILPM] Mahasiswa Menyatakan Belum Selesai — Pengaduan #' . $this->pengaduan->id,
        );
    }

    /**
     * Konten email — view blade.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.konfirmasi-ditolak-admin',
        );
    }

    /**
     * Attachment (tidak ada).
     */
    public function attachments(): array
    {
        return [];
    }
}
