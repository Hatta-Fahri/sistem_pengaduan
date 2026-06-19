<?php

namespace App\Services;

use App\Models\EmailLog;
use App\Models\Pengaduan;
use App\Models\StatusHistory;
use Illuminate\Support\Facades\DB;

class PengaduanService
{
    public function __construct(
        protected NotifikasiService $notifikasiService
    ) {}

    /**
     * Buat pengaduan baru dan catat status awal ke status_history.
     *
     * @param  array<string, mixed>  $data  Data tervalidasi dari StorePengaduanRequest
     * @param  int  $userId  ID mahasiswa pelapor
     * @return Pengaduan
     */
    public function createPengaduan(array $data, int $userId): Pengaduan
    {
        return DB::transaction(function () use ($data, $userId) {
            // Simpan pengaduan ke database
            $pengaduan = Pengaduan::create([
                'user_id'          => $userId,
                'kategori_id'      => $data['kategori_id'],
                'tanggal_kejadian' => $data['tanggal_kejadian'],
                'subjek'           => $data['subjek'],
                'isi_pengaduan'    => $data['isi_pengaduan'],
                'status'           => Pengaduan::STATUS_MENUNGGU,
            ]);

            // Catat ke status_history (status awal, status_lama = null)
            StatusHistory::create([
                'pengaduan_id' => $pengaduan->id,
                'status_lama'  => null,
                'status_baru'  => Pengaduan::STATUS_MENUNGGU,
                'catatan'      => 'Pengaduan berhasil diajukan oleh mahasiswa.',
                'changed_by'   => $userId,
            ]);

            // Kirim notifikasi email (non-blocking, via queue)
            $this->notifikasiService->kirimPengaduanDiterima($pengaduan);
            $this->notifikasiService->kirimPengaduanBaruAdmin($pengaduan);

            return $pengaduan;
        });
    }

    /**
     * Update status pengaduan dan catat ke status_history.
     *
     * @param  Pengaduan  $pengaduan  Instance pengaduan yang akan diupdate
     * @param  string  $statusBaru  Nilai status baru
     * @param  string|null  $catatanAdmin  Catatan dari admin (opsional)
     * @param  int  $adminId  ID admin yang melakukan perubahan
     * @return Pengaduan
     */
    public function updateStatus(Pengaduan $pengaduan, string $statusBaru, ?string $catatanAdmin, int $adminId): Pengaduan
    {
        return DB::transaction(function () use ($pengaduan, $statusBaru, $catatanAdmin, $adminId) {
            $statusLama = $pengaduan->status;

            // Update status dan catatan admin di tabel pengaduan
            $pengaduan->update([
                'status'        => $statusBaru,
                'catatan_admin' => $catatanAdmin,
            ]);

            // Catat perubahan status ke status_history
            StatusHistory::create([
                'pengaduan_id' => $pengaduan->id,
                'status_lama'  => $statusLama,
                'status_baru'  => $statusBaru,
                'catatan'      => $catatanAdmin,
                'changed_by'   => $adminId,
            ]);

            // Kirim notifikasi email kepada mahasiswa (sertakan statusLama untuk template email)
            $this->notifikasiService->kirimStatusDiperbarui($pengaduan->fresh(), $statusLama);

            return $pengaduan->fresh();
        });
    }
}
