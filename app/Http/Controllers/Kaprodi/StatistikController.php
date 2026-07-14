<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use App\Models\KategoriPengaduan;
use App\Models\Pengaduan;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class StatistikController extends Controller
{
    /**
     * Resolve rentang tanggal ($startDate, $endDate) dan label ($teksPeriode)
     * berdasarkan parameter `periode` dari request.
     */
    private function resolvePeriode(Request $request): array
    {
        $periode = $request->input('periode', 'mingguan');
        if (! in_array($periode, ['mingguan', 'bulanan', 'tahunan', 'custom'])) {
            $periode = 'mingguan';
        }

        switch ($periode) {
            case 'mingguan':
                $startDate   = now()->subDays(6)->startOfDay();
                $endDate     = now()->endOfDay();
                $teksPeriode = '7 Hari Terakhir (' . $startDate->locale('id')->isoFormat('D MMM') . ' – ' . $endDate->locale('id')->isoFormat('D MMM YYYY') . ')';
                break;

            case 'bulanan':
                $bulan = (int) $request->input('bulan', now()->month);
                $tahunBulan = (int) $request->input('tahun_bulan', now()->year);
                if ($bulan < 1 || $bulan > 12) $bulan = now()->month;
                if ($tahunBulan < 2020 || $tahunBulan > now()->year + 1) $tahunBulan = now()->year;

                $startDate   = Carbon::create($tahunBulan, $bulan, 1)->startOfDay();
                $endDate     = $startDate->copy()->endOfMonth();
                $teksPeriode = 'Bulan ' . $startDate->locale('id')->isoFormat('MMMM YYYY');
                break;

            case 'tahunan':
                $tahun = (int) $request->input('tahun', now()->year);
                if ($tahun < 2020 || $tahun > now()->year + 1) $tahun = now()->year;

                $startDate   = Carbon::create($tahun, 1, 1)->startOfDay();
                $endDate     = Carbon::create($tahun, 12, 31)->endOfDay();
                $teksPeriode = 'Tahun ' . $tahun;
                break;

            case 'custom':
                if ($request->filled('tanggal_dari') && $request->filled('tanggal_sampai')) {
                    $d1 = Carbon::parse($request->tanggal_dari)->startOfDay();
                    $d2 = Carbon::parse($request->tanggal_sampai)->endOfDay();
                    if ($d1->lte($d2)) {
                        $startDate = $d1;
                        $endDate   = $d2;
                    } else {
                        $startDate = $d2->startOfDay();
                        $endDate   = $d1->endOfDay();
                    }
                } else {
                    $startDate = now()->subDays(6)->startOfDay();
                    $endDate   = now()->endOfDay();
                }
                $teksPeriode = $startDate->locale('id')->isoFormat('D MMMM YYYY') . ' s.d. ' . $endDate->locale('id')->isoFormat('D MMMM YYYY');
                break;

            default:
                $startDate   = now()->subDays(6)->startOfDay();
                $endDate     = now()->endOfDay();
                $teksPeriode = '7 Hari Terakhir';
                break;
        }

        return compact('periode', 'startDate', 'endDate', 'teksPeriode');
    }

    /**
     * Bangun data tren (labels + values) secara dinamis berdasarkan rentang tanggal.
     */
    private function buildTrendData(Carbon $startDate, Carbon $endDate): array
    {
        $trendLabels = [];
        $trendData   = [];

        $diffDays = $startDate->diffInDays($endDate);

        if ($diffDays <= 31) {
            $cursor = $startDate->copy()->startOfDay();
            $limit  = $endDate->copy()->startOfDay();
            while ($cursor->lte($limit)) {
                $trendLabels[] = $cursor->locale('id')->isoFormat('D MMM');
                $trendData[]   = Pengaduan::whereDate('created_at', $cursor->toDateString())->count();
                $cursor->addDay();
            }
        } else {
            $cursor    = $startDate->copy()->startOfMonth();
            $lastMonth = $endDate->copy()->startOfMonth();
            while ($cursor->lte($lastMonth)) {
                $trendLabels[] = $cursor->locale('id')->isoFormat('MMM YYYY');
                $monthStart = $cursor->copy()->startOfMonth()->lt($startDate)
                    ? $startDate->copy()
                    : $cursor->copy()->startOfMonth();
                $monthEnd   = $cursor->copy()->endOfMonth()->gt($endDate)
                    ? $endDate->copy()
                    : $cursor->copy()->endOfMonth();
                $trendData[] = Pengaduan::whereBetween('created_at', [$monthStart, $monthEnd])->count();
                $cursor->addMonth();
            }
        }

        return compact('trendLabels', 'trendData');
    }

    /**
     * Tampilkan halaman statistik & rekap pengaduan untuk Kaprodi.
     */
    public function index(Request $request): View
    {
        ['periode' => $periode, 'startDate' => $startDate, 'endDate' => $endDate, 'teksPeriode' => $teksPeriode]
            = $this->resolvePeriode($request);

        $perKategori = KategoriPengaduan::query()
            ->withCount(['pengaduan' => fn ($q) => $q->whereBetween('created_at', [$startDate, $endDate])])
            ->orderBy('nama_kategori')
            ->get();

        $statusLabels = Pengaduan::statusLabels();
        $perStatus    = [];
        foreach ($statusLabels as $key => $label) {
            $perStatus[$key] = Pengaduan::byStatus($key)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();
        }

        ['trendLabels' => $trendLabels, 'trendData' => $trendData]
            = $this->buildTrendData($startDate, $endDate);

        $pengaduanSelesai = Pengaduan::where('status', Pengaduan::STATUS_SELESAI)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get(['created_at', 'updated_at']);

        $rataRataJam = $pengaduanSelesai->isEmpty()
            ? 0
            : round($pengaduanSelesai->avg(fn ($p) => $p->created_at->diffInHours($p->updated_at)), 1);

        $kategoriTerbanyak = $perKategori->sortByDesc('pengaduan_count')->first();

        $warnaStatus = [
            Pengaduan::STATUS_MENUNGGU            => '#9CA3AF',
            Pengaduan::STATUS_DIPROSES            => '#3B82F6',
            Pengaduan::STATUS_BUTUH_INFO          => '#EAB308',
            Pengaduan::STATUS_MENUNGGU_KONFIRMASI => '#06B6D4',
            Pengaduan::STATUS_SELESAI             => '#22C55E',
            Pengaduan::STATUS_DITOLAK             => '#EF4444',
        ];

        $statusValid = array_keys($statusLabels);
        $detailQuery = Pengaduan::with(['user', 'kategori'])
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($request->filled('status') && in_array($request->status, $statusValid)) {
            $detailQuery->byStatus($request->status);
        }
        if ($request->filled('kategori_id') && is_numeric($request->kategori_id)) {
            $detailQuery->byKategori((int) $request->kategori_id);
        }
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

        return view('kaprodi.statistik.index', [
            'periode'            => $periode,
            'teksPeriode'        => $teksPeriode,
            'startDate'          => $startDate,
            'endDate'            => $endDate,
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
            'pengaduanDetail'    => $pengaduanDetail,
            'statusLabels'       => $statusLabels,
            'statusColors'       => Pengaduan::statusColors(),
            'kategoriList'       => $kategoriList,
        ]);
    }

    /**
     * Ekspor laporan statistik ke PDF menggunakan DomPDF.
     */
    public function exportPdf(Request $request): Response
    {
        ['periode' => $periode, 'startDate' => $startDate, 'endDate' => $endDate, 'teksPeriode' => $teksPeriode]
            = $this->resolvePeriode($request);

        $perKategori = KategoriPengaduan::query()
            ->withCount(['pengaduan' => fn ($q) => $q->whereBetween('created_at', [$startDate, $endDate])])
            ->orderBy('nama_kategori')
            ->get();

        $statusLabels = Pengaduan::statusLabels();
        $statusColors = [
            Pengaduan::STATUS_MENUNGGU            => '#9CA3AF',
            Pengaduan::STATUS_DIPROSES            => '#3B82F6',
            Pengaduan::STATUS_BUTUH_INFO          => '#EAB308',
            Pengaduan::STATUS_MENUNGGU_KONFIRMASI => '#06B6D4',
            Pengaduan::STATUS_SELESAI             => '#22C55E',
            Pengaduan::STATUS_DITOLAK             => '#EF4444',
        ];
        $perStatus = [];
        foreach ($statusLabels as $key => $label) {
            $perStatus[$key] = Pengaduan::byStatus($key)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->count();
        }

        ['trendLabels' => $trendLabels, 'trendData' => $trendData]
            = $this->buildTrendData($startDate, $endDate);

        $pengaduanSelesai = Pengaduan::where('status', Pengaduan::STATUS_SELESAI)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->get(['created_at', 'updated_at']);

        $rataRataJam = $pengaduanSelesai->isEmpty()
            ? 0
            : round($pengaduanSelesai->avg(fn ($p) => $p->created_at->diffInHours($p->updated_at)), 1);

        $kategoriTerbanyak = $perKategori->sortByDesc('pengaduan_count')->first();

        $pengaduanList = Pengaduan::with(['user', 'kategori'])
            ->whereBetween('created_at', [$startDate, $endDate])
            ->orderBy('created_at')
            ->get();

        $pdf = Pdf::loadView('kaprodi.statistik.export-pdf', [
            'teksPeriode'       => $teksPeriode,
            'startDate'         => $startDate,
            'endDate'           => $endDate,
            'totalPengaduan'    => array_sum($perStatus),
            'rataRataJam'       => $rataRataJam,
            'kategoriTerbanyak' => $kategoriTerbanyak,
            'perKategori'       => $perKategori,
            'perStatus'         => $perStatus,
            'statusLabels'      => $statusLabels,
            'statusColors'      => $statusColors,
            'trendLabels'       => $trendLabels,
            'trendData'         => $trendData,
            'pengaduanList'     => $pengaduanList,
        ])->setOption([
            'isLocalEnabled' => true,
            'chroot'         => public_path(),
        ])->setPaper('a4', 'portrait');

        $filename = 'laporan-statistik-pengaduan-' . $startDate->format('Ymd') . '-' . $endDate->format('Ymd') . '.pdf';

        return $pdf->download($filename);
    }
}
