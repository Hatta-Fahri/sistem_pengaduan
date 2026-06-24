@extends('layouts.admin')
@section('title', 'Kelola Kategori Pengaduan')
@section('content')

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Kelola Kategori Pengaduan</h1>
            <p class="text-sm text-gray-500 mt-1 font-medium">Tambah, ubah, atau nonaktifkan kategori yang tersedia di form pengaduan mahasiswa.</p>
        </div>
        <a href="{{ route('admin.kategori.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#2b4cba] hover:bg-[#2441a1] text-white text-sm font-bold rounded-xl shadow-md shadow-blue-900/20 transition-all hover:-translate-y-0.5 focus:ring-4 focus:ring-blue-500/30">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Tambah Kategori
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50/80 border-b border-gray-200 text-gray-500 text-xs uppercase tracking-wider font-bold">
                    <tr>
                        <th scope="col" class="px-6 py-4 w-12 text-center">No</th>
                        <th scope="col" class="px-6 py-4">Nama Kategori</th>
                        <th scope="col" class="px-6 py-4">Deskripsi</th>
                        <th scope="col" class="px-6 py-4 text-center">Jumlah Pengaduan</th>
                        <th scope="col" class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($kategoriList as $index => $kategori)
                    <tr class="hover:bg-gray-50/80 transition-colors group">
                        <td class="px-6 py-4 text-center text-gray-400 text-xs font-bold">
                            {{ ($kategoriList->currentPage() - 1) * $kategoriList->perPage() + $loop->iteration }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900 group-hover:text-[#2b4cba] transition-colors">{{ $kategori->nama_kategori }}</div>
                        </td>
                        <td class="px-6 py-4 max-w-sm">
                            <p class="text-gray-500 font-medium line-clamp-2">{{ $kategori->deskripsi ?: '-' }}</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-50 text-[#2b4cba] font-bold border border-blue-100">
                                {{ $kategori->pengaduan_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center whitespace-nowrap">
                            <div class="flex items-center justify-center gap-2">
                                {{-- Tombol Edit --}}
                                <a href="{{ route('admin.kategori.edit', $kategori) }}"
                                   class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-[#2b4cba]/8 border border-[#2b4cba]/20 hover:bg-[#2b4cba] hover:text-white text-[#2b4cba] rounded-lg text-xs font-bold transition-all duration-200">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    Edit
                                </a>
                                {{-- Tombol Hapus --}}
                                <form method="POST" action="{{ route('admin.kategori.destroy', $kategori) }}" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-red-50 border border-red-200 hover:bg-red-600 hover:text-white text-red-600 rounded-lg text-xs font-bold transition-all duration-200">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-24 text-center">
                            <h3 class="text-lg font-bold text-gray-900">Belum ada kategori pengaduan</h3>
                            <p class="text-sm text-gray-500 mt-1">Tambahkan kategori baru agar mahasiswa bisa memilihnya saat membuat pengaduan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($kategoriList->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $kategoriList->links() }}
            </div>
        @endif
    </div>
</div>

@endsection