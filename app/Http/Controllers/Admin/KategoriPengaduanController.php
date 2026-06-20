<?php

namespace App\Http\Controllers\Admin;

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

        return view('admin.kategori.index', compact('kategoriList'));
    }

    /**
     * Tampilkan form tambah kategori baru.
     */
    public function create(): View
    {
        return view('admin.kategori.create');
    }

    /**
     * Simpan kategori baru.
     */
    public function store(KategoriPengaduanRequest $request): RedirectResponse
    {
        KategoriPengaduan::create($request->validated());

        return redirect()
            ->route('admin.kategori.index')
            ->with('success', 'Kategori pengaduan berhasil ditambahkan.');
    }

    /**
     * Tampilkan form edit kategori.
     */
    public function edit(KategoriPengaduan $kategori): View
    {
        return view('admin.kategori.edit', compact('kategori'));
    }

    /**
     * Perbarui data kategori.
     */
    public function update(KategoriPengaduanRequest $request, KategoriPengaduan $kategori): RedirectResponse
    {
        $kategori->update($request->validated());

        return redirect()
            ->route('admin.kategori.index')
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
            ->route('admin.kategori.index')
            ->with('success', $pesan);
    }
}
