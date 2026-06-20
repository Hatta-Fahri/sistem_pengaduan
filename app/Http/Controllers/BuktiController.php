<?php

namespace App\Http\Controllers;

use App\Models\Pengaduan;
use App\Models\StatusHistory;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BuktiController extends Controller
{
    /**
     * Sajikan file bukti pendukung milik sebuah pengaduan.
     * File disimpan di disk privat — hanya pemilik pengaduan atau admin yang boleh mengakses.
     */
    public function pengaduan(Pengaduan $pengaduan): StreamedResponse
    {
        $this->authorizeAkses($pengaduan->user_id);

        abort_unless($pengaduan->bukti, 404);

        return Storage::disk('local')->response($pengaduan->bukti);
    }

    /**
     * Sajikan file bukti yang dilampirkan admin pada satu entri riwayat status.
     */
    public function statusHistory(StatusHistory $statusHistory): StreamedResponse
    {
        $this->authorizeAkses($statusHistory->pengaduan->user_id);

        abort_unless($statusHistory->bukti, 404);

        return Storage::disk('local')->response($statusHistory->bukti);
    }

    /**
     * Hanya admin atau mahasiswa pemilik pengaduan yang boleh mengakses berkas bukti.
     */
    protected function authorizeAkses(int $pemilikId): void
    {
        $user = auth()->user();

        if (! $user->isAdmin() && $user->id !== $pemilikId) {
            abort(403, 'Anda tidak memiliki akses ke berkas ini.');
        }
    }
}
