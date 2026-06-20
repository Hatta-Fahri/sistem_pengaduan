<?php

namespace App\Services;

use App\Mail\BalasanInformasiAdmin;
use App\Mail\KonfirmasiDitolakAdmin;
use App\Mail\PengaduanBaruAdmin;
use App\Mail\PengaduanDiterima;
use App\Mail\StatusDiperbarui;
use App\Models\EmailLog;
use App\Models\Pengaduan;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class NotifikasiService
{
    /**
     * Kirim notifikasi ke mahasiswa bahwa pengaduan berhasil diterima.
     * Dispatched via Queue agar tidak blocking response.
     */
    public function kirimPengaduanDiterima(Pengaduan $pengaduan): void
    {
        $subject = '[SILPM] Pengaduan #' . $pengaduan->id . ' Berhasil Diterima';

        try {
            // Pastikan relasi sudah di-load
            $pengaduan->loadMissing(['user', 'kategori']);

            // Dispatch ke queue (non-blocking)
            Mail::to($pengaduan->user->email)
                ->queue(new PengaduanDiterima($pengaduan));

            $this->catatEmailLog(
                recipientEmail: $pengaduan->user->email,
                subject: $subject,
                type: 'pengaduan_diterima',
                pengaduanId: $pengaduan->id,
                status: 'sent',
            );
        } catch (\Throwable $e) {
            Log::error('[SILPM Email] Gagal dispatch PengaduanDiterima: ' . $e->getMessage(), [
                'pengaduan_id' => $pengaduan->id,
            ]);
            $this->catatEmailLog(
                recipientEmail: $pengaduan->user->email ?? '',
                subject: $subject,
                type: 'pengaduan_diterima',
                pengaduanId: $pengaduan->id,
                status: 'failed',
            );
        }
    }

    /**
     * Kirim notifikasi ke semua admin bahwa ada pengaduan baru masuk.
     * Dispatched via Queue.
     */
    public function kirimPengaduanBaruAdmin(Pengaduan $pengaduan): void
    {
        $subject = '[SILPM] Pengaduan Baru Masuk — #' . $pengaduan->id;

        try {
            $pengaduan->loadMissing(['user', 'kategori']);

            // Kirim ke semua akun dengan role admin
            $admins = User::where('role', 'admin')->get();

            foreach ($admins as $admin) {
                Mail::to($admin->email)
                    ->queue(new PengaduanBaruAdmin($pengaduan));

                $this->catatEmailLog(
                    recipientEmail: $admin->email,
                    subject: $subject,
                    type: 'pengaduan_baru_admin',
                    pengaduanId: $pengaduan->id,
                    status: 'sent',
                );
            }
        } catch (\Throwable $e) {
            Log::error('[SILPM Email] Gagal dispatch PengaduanBaruAdmin: ' . $e->getMessage(), [
                'pengaduan_id' => $pengaduan->id,
            ]);
            $this->catatEmailLog(
                recipientEmail: 'admin@silpm.local',
                subject: $subject,
                type: 'pengaduan_baru_admin',
                pengaduanId: $pengaduan->id,
                status: 'failed',
            );
        }
    }

    /**
     * Kirim notifikasi ke mahasiswa bahwa status pengaduannya diperbarui.
     * Dispatched via Queue.
     *
     * @param  Pengaduan  $pengaduan  Instance fresh setelah update
     * @param  string  $statusLama  Status sebelum diubah
     */
    public function kirimStatusDiperbarui(Pengaduan $pengaduan, string $statusLama): void
    {
        $subject = '[SILPM] Status Pengaduan #' . $pengaduan->id . ' Telah Diperbarui';

        try {
            $pengaduan->loadMissing(['user', 'kategori']);

            Mail::to($pengaduan->user->email)
                ->queue(new StatusDiperbarui($pengaduan, $statusLama));

            $this->catatEmailLog(
                recipientEmail: $pengaduan->user->email,
                subject: $subject,
                type: 'status_diperbarui',
                pengaduanId: $pengaduan->id,
                status: 'sent',
            );
        } catch (\Throwable $e) {
            Log::error('[SILPM Email] Gagal dispatch StatusDiperbarui: ' . $e->getMessage(), [
                'pengaduan_id' => $pengaduan->id,
                'status_lama'  => $statusLama,
            ]);
            $this->catatEmailLog(
                recipientEmail: $pengaduan->user->email ?? '',
                subject: $subject,
                type: 'status_diperbarui',
                pengaduanId: $pengaduan->id,
                status: 'failed',
            );
        }
    }

    /**
     * Kirim notifikasi ke semua admin bahwa mahasiswa menolak konfirmasi penyelesaian
     * (menyatakan pengaduan belum selesai), beserta alasannya.
     * Dispatched via Queue.
     */
    public function kirimKonfirmasiDitolakAdmin(Pengaduan $pengaduan, string $alasan): void
    {
        $subject = '[SILPM] Mahasiswa Menyatakan Belum Selesai — Pengaduan #' . $pengaduan->id;

        try {
            $pengaduan->loadMissing(['user', 'kategori']);

            $admins = User::where('role', 'admin')->get();

            foreach ($admins as $admin) {
                Mail::to($admin->email)
                    ->queue(new KonfirmasiDitolakAdmin($pengaduan, $alasan));

                $this->catatEmailLog(
                    recipientEmail: $admin->email,
                    subject: $subject,
                    type: 'konfirmasi_ditolak_admin',
                    pengaduanId: $pengaduan->id,
                    status: 'sent',
                );
            }
        } catch (\Throwable $e) {
            Log::error('[SILPM Email] Gagal dispatch KonfirmasiDitolakAdmin: ' . $e->getMessage(), [
                'pengaduan_id' => $pengaduan->id,
            ]);
        }
    }

    /**
     * Kirim notifikasi ke semua admin bahwa mahasiswa membalas permintaan
     * informasi tambahan. Dispatched via Queue.
     */
    public function kirimBalasanInformasiAdmin(Pengaduan $pengaduan, string $balasan): void
    {
        $subject = '[SILPM] Mahasiswa Membalas Permintaan Informasi — Pengaduan #' . $pengaduan->id;

        try {
            $pengaduan->loadMissing(['user', 'kategori']);

            $admins = User::where('role', 'admin')->get();

            foreach ($admins as $admin) {
                Mail::to($admin->email)
                    ->queue(new BalasanInformasiAdmin($pengaduan, $balasan));

                $this->catatEmailLog(
                    recipientEmail: $admin->email,
                    subject: $subject,
                    type: 'balasan_informasi_admin',
                    pengaduanId: $pengaduan->id,
                    status: 'sent',
                );
            }
        } catch (\Throwable $e) {
            Log::error('[SILPM Email] Gagal dispatch BalasanInformasiAdmin: ' . $e->getMessage(), [
                'pengaduan_id' => $pengaduan->id,
            ]);
        }
    }

    /**
     * Catat log pengiriman email ke tabel email_logs.
     * Kegagalan pencatatan tidak menghentikan flow utama.
     */
    protected function catatEmailLog(
        string $recipientEmail,
        string $subject,
        string $type,
        int $pengaduanId,
        string $status
    ): void {
        try {
            EmailLog::create([
                'recipient_email' => $recipientEmail,
                'subject'         => $subject,
                'type'            => $type,
                'pengaduan_id'    => $pengaduanId,
                'status'          => $status,
                'sent_at'         => now(),
            ]);

            Log::info("[SILPM Email] {$status} | {$type} → {$recipientEmail}");
        } catch (\Throwable $e) {
            Log::error('[SILPM Email] Gagal mencatat email_log: ' . $e->getMessage());
        }
    }
}
