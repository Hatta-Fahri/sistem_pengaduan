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
        // Simpan file bukti (jika ada) ke disk public sebelum transaksi DB dimulai
        $buktiPath = isset($data['bukti']) && $data['bukti'] instanceof \Illuminate\Http\UploadedFile
            ? $data['bukti']->store('bukti-pengaduan', 'public')
            : null;

        return DB::transaction(function () use ($data, $userId, $buktiPath) {
            // Simpan pengaduan ke database
            $pengaduan = Pengaduan::create([
                'user_id'          => $userId,
                'is_anonymous'     => $data['is_anonymous'] ?? false,
                'kategori_id'      => $data['kategori_id'],
                'tanggal_kejadian' => $data['tanggal_kejadian'],
                'subjek'           => $data['subjek'],
                'isi_pengaduan'    => $data['isi_pengaduan'],
                'bukti'            => $buktiPath,
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

    /**
     * Konfirmasi dari mahasiswa bahwa pengaduan sudah benar-benar selesai.
     * Hanya valid dari status menunggu_konfirmasi_mahasiswa (dijaga di controller).
     */
    public function konfirmasiSelesai(Pengaduan $pengaduan, int $mahasiswaId): Pengaduan
    {
        return DB::transaction(function () use ($pengaduan, $mahasiswaId) {
            $statusLama = $pengaduan->status;

            $pengaduan->update(['status' => Pengaduan::STATUS_SELESAI]);

            StatusHistory::create([
                'pengaduan_id' => $pengaduan->id,
                'status_lama'  => $statusLama,
                'status_baru'  => Pengaduan::STATUS_SELESAI,
                'catatan'      => 'Mahasiswa mengonfirmasi pengaduan telah selesai ditangani.',
                'changed_by'   => $mahasiswaId,
            ]);

            return $pengaduan->fresh();
        });
    }

    /**
     * Mahasiswa menyatakan pengaduan belum benar-benar selesai — dibuka kembali
     * ke status sedang_diproses agar admin menindaklanjuti lagi.
     * Hanya valid dari status menunggu_konfirmasi_mahasiswa (dijaga di controller).
     */
    public function tolakKonfirmasi(Pengaduan $pengaduan, string $alasan, int $mahasiswaId): Pengaduan
    {
        return DB::transaction(function () use ($pengaduan, $alasan, $mahasiswaId) {
            $statusLama = $pengaduan->status;

            $pengaduan->update(['status' => Pengaduan::STATUS_DIPROSES]);

            StatusHistory::create([
                'pengaduan_id' => $pengaduan->id,
                'status_lama'  => $statusLama,
                'status_baru'  => Pengaduan::STATUS_DIPROSES,
                'catatan'      => $alasan,
                'changed_by'   => $mahasiswaId,
            ]);

            $this->notifikasiService->kirimKonfirmasiDitolakAdmin($pengaduan->fresh(), $alasan);

            return $pengaduan->fresh();
        });
    }

    /**
     * Tutup otomatis pengaduan yang sudah SLA_HARI hari di status menunggu_konfirmasi_mahasiswa
     * tanpa respons dari mahasiswa (anggap diterima). Dipanggil dari scheduled command.
     *
     * @return int  Jumlah pengaduan yang ditutup.
     */
    public function autoCloseStale(): int
    {
        $stale = Pengaduan::byStatus(Pengaduan::STATUS_MENUNGGU_KONFIRMASI)
            ->where('updated_at', '<=', now()->subDays(Pengaduan::SLA_HARI))
            ->get();

        foreach ($stale as $pengaduan) {
            DB::transaction(function () use ($pengaduan) {
                $pengaduan->update(['status' => Pengaduan::STATUS_SELESAI]);

                StatusHistory::create([
                    'pengaduan_id' => $pengaduan->id,
                    'status_lama'  => Pengaduan::STATUS_MENUNGGU_KONFIRMASI,
                    'status_baru'  => Pengaduan::STATUS_SELESAI,
                    'catatan'      => 'Ditutup otomatis oleh sistem karena tidak ada respons dari mahasiswa dalam ' . Pengaduan::SLA_HARI . ' hari.',
                    'changed_by'   => null,
                ]);

                $this->notifikasiService->kirimStatusDiperbarui($pengaduan->fresh(), Pengaduan::STATUS_MENUNGGU_KONFIRMASI);
            });
        }

        return $stale->count();
    }
}
