<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateStatusRequest;
use App\Models\KategoriPengaduan;
use App\Models\Pengaduan;
use App\Services\PengaduanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PengaduanController extends Controller
{
    public function __construct(
        protected PengaduanService $pengaduanService
    ) {}

    /**
     * Daftar seluruh pengaduan dari semua mahasiswa.
     * Filter: status, kategori, tanggal (range), search (nama/NIM/subjek).
     * Pagination: 15 per halaman.
     */
    public function index(Request $request): View
    {
        // Validasi input filter sebelum digunakan di query
        $statusValid = array_keys(Pengaduan::statusLabels());

        $query = Pengaduan::with(['user', 'kategori']);

        // Filter berdasarkan status
        if ($request->filled('status') && in_array($request->status, $statusValid)) {
            $query->byStatus($request->status);
        }

        // Filter berdasarkan kategori
        if ($request->filled('kategori_id') && is_numeric($request->kategori_id)) {
            $query->byKategori((int) $request->kategori_id);
        }

        // Filter berdasarkan range tanggal pengajuan (created_at)
        if ($request->filled('tanggal_dari')) {
            $dari = \Carbon\Carbon::parse($request->tanggal_dari)->startOfDay();
            $query->where('created_at', '>=', $dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $sampai = \Carbon\Carbon::parse($request->tanggal_sampai)->endOfDay();
            $query->where('created_at', '<=', $sampai);
        }

        // Filter berdasarkan pencarian: nama mahasiswa, NIM, atau subjek
        // Pencarian nama/NIM dikecualikan untuk pengaduan anonim — supaya admin
        // tidak bisa "mencari" nama mahasiswa tertentu untuk menyingkap pelapor anonim.
        if ($request->filled('search')) {
            $search = strip_tags(trim($request->search));
            $query->where(function ($q) use ($search) {
                $q->where('subjek', 'like', '%' . $search . '%')
                  ->orWhere(function ($idq) use ($search) {
                      $idq->where('is_anonymous', false)
                          ->whereHas('user', function ($uq) use ($search) {
                              $uq->where('name', 'like', '%' . $search . '%')
                                 ->orWhere('nim', 'like', '%' . $search . '%');
                          });
                  });
            });
        }

        $pengaduan    = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        $kategoriList = KategoriPengaduan::active()->orderBy('nama_kategori')->get();
        $statusLabels = Pengaduan::statusLabels();
        $statusColors = Pengaduan::statusColors();

        return view('admin.pengaduan.index', compact(
            'pengaduan',
            'kategoriList',
            'statusLabels',
            'statusColors'
        ));
    }

    /**
     * Tampilkan detail pengaduan beserta riwayat status.
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

        // Opsi status yang bisa dipilih admin di form update — STATUS_SELESAI dikecualikan
        // karena admin hanya bisa membawa pengaduan ke "menunggu konfirmasi", bukan langsung selesai.
        $statusOptions = $statusLabels;
        unset($statusOptions[Pengaduan::STATUS_SELESAI]);

        return view('admin.pengaduan.show', compact('pengaduan', 'statusLabels', 'statusColors', 'statusOptions'));
    }

    /**
     * Update status pengaduan — admin hanya bisa ubah status & catatan_admin.
     * Isi pengaduan tidak boleh diubah (sesuai AGENT.md).
     */
    public function updateStatus(UpdateStatusRequest $request, Pengaduan $pengaduan): RedirectResponse
    {
        $this->pengaduanService->updateStatus(
            pengaduan: $pengaduan,
            statusBaru: $request->validated('status'),
            catatanAdmin: $request->validated('catatan_admin'),
            adminId: $request->user()->id,
            buktiAdmin: $request->file('bukti_admin'),
        );

        return redirect()
            ->route('admin.pengaduan.show', $pengaduan)
            ->with('success', 'Status pengaduan berhasil diperbarui dan notifikasi telah dikirim ke mahasiswa.');
    }

    /**
     * Ekspor laporan pengaduan ke CSV (native PHP, tanpa package tambahan).
     * Filter: status, kategori_id, tanggal_dari, tanggal_sampai.
     */
    public function export(Request $request): StreamedResponse
    {
        $statusValid = array_keys(Pengaduan::statusLabels());

        $query = Pengaduan::with(['user', 'kategori']);

        if ($request->filled('status') && in_array($request->status, $statusValid)) {
            $query->byStatus($request->status);
        }

        if ($request->filled('kategori_id') && is_numeric($request->kategori_id)) {
            $query->byKategori((int) $request->kategori_id);
        }

        if ($request->filled('tanggal_dari')) {
            $dari = \Carbon\Carbon::parse($request->tanggal_dari)->startOfDay();
            $query->where('created_at', '>=', $dari);
        }

        if ($request->filled('tanggal_sampai')) {
            $sampai = \Carbon\Carbon::parse($request->tanggal_sampai)->endOfDay();
            $query->where('created_at', '<=', $sampai);
        }

        $pengaduan    = $query->orderBy('created_at')->get();
        $statusLabels = Pengaduan::statusLabels();
        $filename     = 'laporan-pengaduan-' . now()->format('Y-m-d') . '.csv';

        $callback = function () use ($pengaduan, $statusLabels) {
            $handle = fopen('php://output', 'w');

            // BOM UTF-8 agar karakter terbaca benar saat dibuka di Excel
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'No', 'Nama Pelapor', 'NIM', 'Kelas', 'Kategori', 'Subjek',
                'Tanggal Kejadian', 'Status', 'Catatan Admin', 'Tanggal Dibuat', 'Tanggal Selesai',
            ]);

            foreach ($pengaduan as $index => $p) {
                fputcsv($handle, [
                    $index + 1,
                    $p->is_anonymous ? 'Anonim' : $p->user->name,
                    $p->is_anonymous ? '-' : $p->user->nim,
                    $p->is_anonymous ? '-' : $p->user->class,
                    $p->kategori->nama_kategori,
                    $p->subjek,
                    $p->tanggal_kejadian->format('d/m/Y'),
                    $statusLabels[$p->status] ?? $p->status,
                    $p->catatan_admin,
                    $p->created_at->format('d/m/Y H:i'),
                    $p->status === Pengaduan::STATUS_SELESAI ? $p->updated_at->format('d/m/Y H:i') : '-',
                ]);
            }

            fclose($handle);
        };

        return response()->stream($callback, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }
}
