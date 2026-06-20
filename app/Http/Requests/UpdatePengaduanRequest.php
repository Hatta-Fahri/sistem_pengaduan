<?php

namespace App\Http\Requests;

use App\Models\Pengaduan;

class UpdatePengaduanRequest extends StorePengaduanRequest
{
    /**
     * Hanya mahasiswa pemilik pengaduan yang boleh mengedit, dan hanya selama
     * statusnya masih menunggu_verifikasi (belum disentuh admin sama sekali).
     * Aturan validasi (rules/messages) memakai punya StorePengaduanRequest.
     */
    public function authorize(): bool
    {
        if (! auth()->check() || ! auth()->user()->isMahasiswa()) {
            return false;
        }

        $pengaduan = $this->route('pengaduan');

        return $pengaduan
            && $pengaduan->user_id === auth()->id()
            && $pengaduan->status === Pengaduan::STATUS_MENUNGGU;
    }
}
