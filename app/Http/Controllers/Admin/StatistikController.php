<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriPengaduan;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StatistikController extends Controller
{
    /**
     * Tampilkan halaman statistik & rekap pengaduan.
     *
     * Filter tahun hanya berlaku untuk rekap per kategori, per status,
     * rata-rata waktu penyelesaian, dan kategori terbanyak. Tren bulanan
     * selalu menampilkan 12 bulan terakhir secara berjalan (rolling),
     * terlepas dari tahun yang dipilih.
     */
    public function index(Request $request): View
    {
        $tahunList = [now()->year, now()->year - 1, now()->year - 2];

        $tahun = (int) $request->input('tahun', now()->year);
        if (! in_array($tahun, $tahunList, true)) {
            $tahun = now()->year;
        }

        // ===== Total pengaduan per kategori =====
        $perKategori = KategoriPengaduan::query()
            ->withCount(['pengaduan' => fn ($q) => $q->whereYear('created_at', $tahun)])
            ->orderBy('nama_kategori')
            ->get();

        // ===== Total pengaduan per status =====
        $statusLabels = Pengaduan::statusLabels();
        $perStatus    = [];
        foreach ($statusLabels as $key => $label) {
            $perStatus[$key] = Pengaduan::byStatus($key)->whereYear('created_at', $tahun)->count();
        }

        // ===== Tren pengaduan per bulan, 12 bulan terakhir (rolling) =====
        $trendLabels = [];
        $trendData   = [];
        for ($i = 11; $i >= 0; $i--) {
            $bulan           = now()->subMonths($i);
            $trendLabels[]   = $bulan->isoFormat('MMM YYYY');
            $trendData[]     = Pengaduan::whereYear('created_at', $bulan->year)
                ->whereMonth('created_at', $bulan->month)
                ->count();
        }

        // ===== Rata-rata waktu penyelesaian (created_at -> updated_at, status selesai) =====
        $pengaduanSelesai = Pengaduan::where('status', Pengaduan::STATUS_SELESAI)
            ->whereYear('created_at', $tahun)
            ->get(['created_at', 'updated_at']);

        $rataRataJam = $pengaduanSelesai->isEmpty()
            ? 0
            : round($pengaduanSelesai->avg(fn ($p) => $p->created_at->diffInHours($p->updated_at)), 1);

        // ===== Kategori dengan pengaduan terbanyak =====
        $kategoriTerbanyak = $perKategori->sortByDesc('pengaduan_count')->first();

        // Warna badge status — disamakan dengan badge yang sudah dipakai di seluruh halaman pengaduan
        $warnaStatus = [
            Pengaduan::STATUS_MENUNGGU           => '#9CA3AF', // gray-400
            Pengaduan::STATUS_DIPROSES           => '#3B82F6', // blue-500
            Pengaduan::STATUS_BUTUH_INFO         => '#EAB308', // yellow-500
            Pengaduan::STATUS_MENUNGGU_KONFIRMASI=> '#06B6D4', // cyan-500
            Pengaduan::STATUS_SELESAI             => '#22C55E', // green-500
            Pengaduan::STATUS_DITOLAK             => '#EF4444', // red-500
        ];

        return view('admin.statistik.index', [
            'tahunList'          => $tahunList,
            'tahunTerpilih'      => $tahun,
            'totalPengaduan'     => array_sum($perStatus),
            'rataRataJam'        => $rataRataJam,
            'kategoriTerbanyak'  => $kategoriTerbanyak,
            'kategoriLabelsJson' => json_encode($perKategori->pluck('nama_kategori')),
            'kategoriDataJson'   => json_encode($perKategori->pluck('pengaduan_count')),
            'statusLabelsJson'   => json_encode(array_values($statusLabels)),
            'statusDataJson'     => json_encode(array_values($perStatus)),
            'statusColorsJson'   => json_encode(array_values($warnaStatus)),
            'trendLabelsJson'    => json_encode($trendLabels),
            'trendDataJson'      => json_encode($trendData),
        ]);
    }
}
