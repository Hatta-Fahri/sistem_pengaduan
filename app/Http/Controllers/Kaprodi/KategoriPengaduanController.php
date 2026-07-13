<?php

namespace App\Http\Controllers\Kaprodi;

use App\Http\Controllers\Controller;
use App\Http\Requests\KategoriPengaduanRequest;
use App\Models\KategoriPengaduan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KategoriPengaduanController extends Controller
{
    /**
     * Daftar seluruh kategori pengaduan (aktif & nonaktif).
     */
    public function index(Request $request): View
    {
        $kategoriList = KategoriPengaduan::withCount('pengaduan')
            ->orderBy('nama_kategori')
            ->paginate(15)
            ->withQueryString();

        return view('kaprodi.kategori.index', compact('kategoriList'));
    }

    /**
     * Tampilkan form tambah kategori baru.
     */
    public function create(): View
    {
        return view('kaprodi.kategori.create');
    }

    /**
     * Simpan kategori baru.
     */
    public function store(KategoriPengaduanRequest $request): RedirectResponse
    {
        KategoriPengaduan::create($request->validated());

        return redirect()
            ->route('kaprodi.kategori.index')
            ->with('success', 'Kategori pengaduan berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit kategori.
     */
    public function edit(KategoriPengaduan $kategori): View
    {
        return view('kaprodi.kategori.edit', compact('kategori'));
    }

    /**
     * Perbarui data kategori.
     */
    public function update(KategoriPengaduanRequest $request, KategoriPengaduan $kategori): RedirectResponse
    {
        $kategori->update($request->validated());

        return redirect()
            ->route('kaprodi.kategori.index')
            ->with('success', 'Kategori pengaduan berhasil diperbarui.');
    }

    /**
     * Aktifkan/nonaktifkan kategori — tidak dihapus permanen karena kategori
     * yang sudah dipakai pengaduan tidak boleh hilang relasinya (onDelete restrict).
     */
    public function toggleActive(KategoriPengaduan $kategori): RedirectResponse
    {
        $kategori->update(['is_active' => ! $kategori->is_active]);

        $pesan = $kategori->is_active
            ? 'Kategori "' . $kategori->nama_kategori . '" diaktifkan kembali.'
            : 'Kategori "' . $kategori->nama_kategori . '" dinonaktifkan. Kategori tidak akan muncul di pilihan form pengaduan baru.';

        return redirect()
            ->route('kaprodi.kategori.index')
            ->with('success', $pesan);
    }

    /**
     * Hapus kategori secara permanen.
     * Gagal jika kategori masih memiliki pengaduan terkait.
     */
    public function destroy(KategoriPengaduan $kategori): RedirectResponse
    {
        if ($kategori->pengaduan()->count() > 0) {
            return redirect()
                ->route('kaprodi.kategori.index')
                ->with('error', 'Kategori "' . $kategori->nama_kategori . '" tidak dapat dihapus karena masih memiliki ' . $kategori->pengaduan()->count() . ' pengaduan terkait.');
        }

        $nama = $kategori->nama_kategori;
        $kategori->delete();

        return redirect()
            ->route('kaprodi.kategori.index')
            ->with('success', 'Kategori "' . $nama . '" berhasil dihapus.');
    }
}
