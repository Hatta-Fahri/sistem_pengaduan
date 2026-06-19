<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard admin dengan statistik seluruh pengaduan.
     */
    public function index(Request $request): View
    {
        // Statistik seluruh pengaduan (admin melihat semua)
        $stats = [
            'total'         => Pengaduan::count(),
            'menunggu'      => Pengaduan::byStatus(Pengaduan::STATUS_MENUNGGU)->count(),
            'diproses'      => Pengaduan::byStatus(Pengaduan::STATUS_DIPROSES)->count(),
            'butuh_info'    => Pengaduan::byStatus(Pengaduan::STATUS_BUTUH_INFO)->count(),
            'selesai'       => Pengaduan::byStatus(Pengaduan::STATUS_SELESAI)->count(),
            'ditolak'       => Pengaduan::byStatus(Pengaduan::STATUS_DITOLAK)->count(),
        ];

        // 10 pengaduan terbaru masuk
        $pengaduanTerbaru = Pengaduan::with(['user', 'kategori'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('stats', 'pengaduanTerbaru'));
    }
}
