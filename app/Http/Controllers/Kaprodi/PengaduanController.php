<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use Illuminate\View\View;

class PengaduanController extends Controller
{
    /**
     * Tampilkan detail pengaduan untuk Kaprodi — READ ONLY.
     * Kaprodi hanya bisa melihat, tidak bisa mengubah status apapun.
     */
    public function show(Pengaduan $pengaduan): View
    {
        // Load relasi — history urut ASC (terlama ke terbaru)
        $pengaduan->load([
            'user',
            'kategori',
            'statusHistory' => fn ($q) => $q->orderBy('created_at', 'asc'),
            'statusHistory.changedBy',
        ]);

        $statusLabels = Pengaduan::statusLabels();
        $statusColors = Pengaduan::statusColors();

        return view('kaprodi.pengaduan.show', compact('pengaduan', 'statusLabels', 'statusColors'));
    }
}
