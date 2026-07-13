<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard kaprodi dengan statistik seluruh pengaduan.
     */
    public function index(Request $request): View
    {
        // Statistik seluruh pengaduan (kaprodi melihat semua, read-only)
        $stats = [
            'total'               => Pengaduan::count(),
            'menunggu'            => Pengaduan::byStatus(Pengaduan::STATUS_MENUNGGU)->count(),
            'diproses'            => Pengaduan::byStatus(Pengaduan::STATUS_DIPROSES)->count(),
            'butuh_info'          => Pengaduan::byStatus(Pengaduan::STATUS_BUTUH_INFO)->count(),
            'menunggu_konfirmasi' => Pengaduan::byStatus(Pengaduan::STATUS_MENUNGGU_KONFIRMASI)->count(),
            'selesai'             => Pengaduan::byStatus(Pengaduan::STATUS_SELESAI)->count(),
            'ditolak'             => Pengaduan::byStatus(Pengaduan::STATUS_DITOLAK)->count(),
            'overdue'             => Pengaduan::overdue()->count(),
        ];

        // 10 pengaduan terbaru masuk
        $pengaduanTerbaru = Pengaduan::with(['user', 'kategori'])
            ->orderByDesc('created_at')
            ->limit(10)
            ->get();

        // Pengaduan yang sudah terlantar (overdue) — paling lama menunggu duluan
        $pengaduanOverdue = Pengaduan::with(['user', 'kategori'])
            ->overdue()
            ->orderBy('updated_at', 'asc')
            ->limit(5)
            ->get();

        return view('kaprodi.dashboard', compact('stats', 'pengaduanTerbaru', 'pengaduanOverdue'));
    }
}
