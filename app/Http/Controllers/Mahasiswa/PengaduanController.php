<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePengaduanRequest;
use App\Http\Requests\TolakKonfirmasiRequest;
use App\Models\KategoriPengaduan;
use App\Models\Pengaduan;
use App\Services\PengaduanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PengaduanController extends Controller
{
    public function __construct(
        protected PengaduanService $pengaduanService
    ) {}

    /**
     * Daftar seluruh pengaduan milik mahasiswa yang login.
     * Filter: status, kategori_id, search (subjek).
     * Pagination: 10 per halaman.
     */
    public function index(Request $request): View
    {
        // Validasi input filter (gunakan Eloquent, bukan raw SQL)
        $statusValid = array_keys(Pengaduan::statusLabels());

        $query = Pengaduan::milikSaya()->with('kategori');

        // Filter berdasarkan status yang valid
        if ($request->filled('status') && in_array($request->status, $statusValid)) {
            $query->byStatus($request->status);
        }

        // Filter berdasarkan kategori (pastikan integer)
        if ($request->filled('kategori_id') && is_numeric($request->kategori_id)) {
            $query->byKategori((int) $request->kategori_id);
        }

        // Filter berdasarkan pencarian subjek
        if ($request->filled('search')) {
            $search = strip_tags(trim($request->search));
            $query->where('subjek', 'like', '%' . $search . '%');
        }

        $pengaduan    = $query->orderByDesc('created_at')->paginate(10)->withQueryString();
        $kategoriList = KategoriPengaduan::active()->orderBy('nama_kategori')->get();
        $statusLabels = Pengaduan::statusLabels();

        return view('mahasiswa.pengaduan.index', compact(
            'pengaduan',
            'kategoriList',
            'statusLabels'
        ));
    }

    /**
     * Tampilkan form pengaduan baru.
     */
    public function create(): View
    {
        $kategoriList = KategoriPengaduan::active()->orderBy('nama_kategori')->get();
        $user         = auth()->user();

        return view('mahasiswa.pengaduan.create', compact('kategoriList', 'user'));
    }

    /**
     * Simpan pengaduan baru ke database via PengaduanService.
     */
    public function store(StorePengaduanRequest $request): RedirectResponse
    {
        $pengaduan = $this->pengaduanService->createPengaduan(
            data: $request->validated(),
            userId: $request->user()->id,
        );

        return redirect()
            ->route('mahasiswa.pengaduan.show', $pengaduan)
            ->with('success', 'Pengaduan Anda berhasil diajukan. Kami akan segera memprosesnya.');
    }

    /**
     * Tampilkan detail pengaduan — hanya milik mahasiswa yang login (RBAC di layer query).
     */
    public function show(Pengaduan $pengaduan): View
    {
        // Isolasi RBAC: pastikan mahasiswa hanya bisa lihat pengaduannya sendiri
        if ($pengaduan->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke pengaduan ini.');
        }

        // Load relasi — history urut ASC (terlama ke terbaru)
        $pengaduan->load([
            'kategori',
            'statusHistory' => fn ($q) => $q->orderBy('created_at', 'asc'),
            'statusHistory.changedBy',
        ]);

        $statusLabels = Pengaduan::statusLabels();
        $statusColors = Pengaduan::statusColors();

        return view('mahasiswa.pengaduan.show', compact('pengaduan', 'statusLabels', 'statusColors'));
    }

    /**
     * Mahasiswa mengonfirmasi pengaduan miliknya sudah benar-benar selesai.
     * Hanya valid dari status menunggu_konfirmasi_mahasiswa.
     */
    public function konfirmasiSelesai(Pengaduan $pengaduan): RedirectResponse
    {
        if ($pengaduan->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke pengaduan ini.');
        }

        if ($pengaduan->status !== Pengaduan::STATUS_MENUNGGU_KONFIRMASI) {
            abort(403, 'Pengaduan ini belum dapat dikonfirmasi.');
        }

        $this->pengaduanService->konfirmasiSelesai($pengaduan, auth()->id());

        return redirect()
            ->route('mahasiswa.pengaduan.show', $pengaduan)
            ->with('success', 'Terima kasih telah mengonfirmasi. Pengaduan ini sekarang ditandai selesai.');
    }

    /**
     * Mahasiswa menyatakan pengaduan miliknya belum benar-benar selesai — dibuka
     * kembali agar admin menindaklanjuti. Hanya valid dari status menunggu_konfirmasi_mahasiswa.
     */
    public function tolakKonfirmasi(TolakKonfirmasiRequest $request, Pengaduan $pengaduan): RedirectResponse
    {
        if ($pengaduan->user_id !== auth()->id()) {
            abort(403, 'Anda tidak memiliki akses ke pengaduan ini.');
        }

        if ($pengaduan->status !== Pengaduan::STATUS_MENUNGGU_KONFIRMASI) {
            abort(403, 'Pengaduan ini belum dapat ditolak konfirmasinya.');
        }

        $this->pengaduanService->tolakKonfirmasi(
            pengaduan: $pengaduan,
            alasan: $request->validated('alasan'),
            mahasiswaId: auth()->id(),
        );

        return redirect()
            ->route('mahasiswa.pengaduan.show', $pengaduan)
            ->with('success', 'Pengaduan dibuka kembali dan admin akan menindaklanjuti.');
    }
}
