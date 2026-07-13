<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriPengaduan;
use App\Models\Pengaduan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Carbon\Carbon;

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

        // ===== Query detail pengaduan (search + filter + paginate) =====
        // Terpisah dari data chart — tidak dipengaruhi filter tahun di atas.
        $statusValid = array_keys($statusLabels);
        $detailQuery = Pengaduan::with(['user', 'kategori']);

        // Filter status
        if ($request->filled('status') && in_array($request->status, $statusValid)) {
            $detailQuery->byStatus($request->status);
        }

        // Filter kategori
        if ($request->filled('kategori_id') && is_numeric($request->kategori_id)) {
            $detailQuery->byKategori((int) $request->kategori_id);
        }

        // Filter tanggal dari
        if ($request->filled('tanggal_dari')) {
            $detailQuery->where('created_at', '>=', Carbon::parse($request->tanggal_dari)->startOfDay());
        }

        // Filter tanggal sampai
        if ($request->filled('tanggal_sampai')) {
            $detailQuery->where('created_at', '<=', Carbon::parse($request->tanggal_sampai)->endOfDay());
        }

        // Search: nama/NIM (non-anonim), subjek, isi_pengaduan
        if ($request->filled('search')) {
            $search = strip_tags(trim($request->search));
            $detailQuery->where(function ($q) use ($search) {
                $q->where('subjek', 'like', '%' . $search . '%')
                  ->orWhere('isi_pengaduan', 'like', '%' . $search . '%')
                  ->orWhere(function ($idq) use ($search) {
                      $idq->where('is_anonymous', false)
                          ->whereHas('user', function ($uq) use ($search) {
                              $uq->where('name', 'like', '%' . $search . '%')
                                 ->orWhere('nim', 'like', '%' . $search . '%');
                          });
                  });
            });
        }

        $pengaduanDetail = $detailQuery
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        $kategoriList = KategoriPengaduan::active()->orderBy('nama_kategori')->get();

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
            // Data tabel detail
            'pengaduanDetail'    => $pengaduanDetail,
            'statusLabels'       => $statusLabels,
            'statusColors'       => Pengaduan::statusColors(),
            'kategoriList'       => $kategoriList,
        ]);
    }

    /**
     * Ekspor laporan statistik ke PDF menggunakan DomPDF.
     * Filter: tahun (default tahun berjalan).
     */
    public function exportPdf(Request $request): Response
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
        $statusColors = [
            Pengaduan::STATUS_MENUNGGU            => '#9CA3AF',
            Pengaduan::STATUS_DIPROSES            => '#3B82F6',
            Pengaduan::STATUS_BUTUH_INFO          => '#EAB308',
            Pengaduan::STATUS_MENUNGGU_KONFIRMASI => '#06B6D4',
            Pengaduan::STATUS_SELESAI             => '#22C55E',
            Pengaduan::STATUS_DITOLAK             => '#EF4444',
        ];
        $perStatus    = [];
        foreach ($statusLabels as $key => $label) {
            $perStatus[$key] = Pengaduan::byStatus($key)->whereYear('created_at', $tahun)->count();
        }

        // ===== Tren pengaduan per bulan, 12 bulan terakhir (rolling) =====
        $trendLabels = [];
        $trendData   = [];
        for ($i = 11; $i >= 0; $i--) {
            $bulan         = now()->subMonths($i);
            $trendLabels[] = $bulan->isoFormat('MMM YYYY');
            $trendData[]   = Pengaduan::whereYear('created_at', $bulan->year)
                ->whereMonth('created_at', $bulan->month)
                ->count();
        }

        // ===== Rata-rata waktu penyelesaian =====
        $pengaduanSelesai = Pengaduan::where('status', Pengaduan::STATUS_SELESAI)
            ->whereYear('created_at', $tahun)
            ->get(['created_at', 'updated_at']);

        $rataRataJam = $pengaduanSelesai->isEmpty()
            ? 0
            : round($pengaduanSelesai->avg(fn ($p) => $p->created_at->diffInHours($p->updated_at)), 1);

        // ===== Kategori dengan pengaduan terbanyak =====
        $kategoriTerbanyak = $perKategori->sortByDesc('pengaduan_count')->first();

        // ===== Seluruh pengaduan tahun terpilih untuk tabel detail =====
        $pengaduanList = Pengaduan::with(['user', 'kategori'])
            ->whereYear('created_at', $tahun)
            ->orderBy('created_at')
            ->get();

        $pdf = Pdf::loadView('admin.statistik.export-pdf', [
            'tahun'            => $tahun,
            'totalPengaduan'   => array_sum($perStatus),
            'rataRataJam'      => $rataRataJam,
            'kategoriTerbanyak'=> $kategoriTerbanyak,
            'perKategori'      => $perKategori,
            'perStatus'        => $perStatus,
            'statusLabels'     => $statusLabels,
            'statusColors'     => $statusColors,
            'trendLabels'      => $trendLabels,
            'trendData'        => $trendData,
            'pengaduanList'    => $pengaduanList,
        ])->setOption([
            'isLocalEnabled' => true,
            'chroot'         => public_path(),
        ])->setPaper('a4', 'portrait');

        $filename = 'laporan-statistik-pengaduan-' . $tahun . '.pdf';

        return $pdf->download($filename);
    }
}
