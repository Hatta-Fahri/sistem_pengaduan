<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard mahasiswa dengan ringkasan pengaduan.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        // Statistik pengaduan milik mahasiswa yang login
        $stats = [
            'total'    => Pengaduan::milikSaya()->count(),
            'menunggu' => Pengaduan::milikSaya()->byStatus(Pengaduan::STATUS_MENUNGGU)->count(),
            'diproses' => Pengaduan::milikSaya()->byStatus(Pengaduan::STATUS_DIPROSES)->count(),
            'selesai'  => Pengaduan::milikSaya()->byStatus(Pengaduan::STATUS_SELESAI)->count(),
            'ditolak'  => Pengaduan::milikSaya()->byStatus(Pengaduan::STATUS_DITOLAK)->count(),
        ];

        // 5 pengaduan terbaru
        $pengaduanTerbaru = Pengaduan::milikSaya()
            ->with('kategori')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('mahasiswa.dashboard', compact('user', 'stats', 'pengaduanTerbaru'));
    }
}
