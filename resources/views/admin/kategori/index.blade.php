@extends('layouts.admin')
@section('title', 'Kelola Kategori Pengaduan')
@section('content')

<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Kelola Kategori Pengaduan</h1>
            <p class="text-sm text-gray-500 mt-1 font-medium">Tambah, ubah, atau nonaktifkan kategori yang tersedia di form pengaduan mahasiswa.</p>
        </div>
        <a href="{{ route('admin.kategori.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-polmed-blue hover:bg-blue-800 text-white text-sm font-bold rounded-xl shadow-md shadow-blue-900/20 transition-all">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Kategori
        </a>
    </div>

    <!-- Flash Messages -->
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.500ms
             class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex items-start gap-4 shadow-sm relative">
            <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <div class="flex-1">
                <h3 class="text-sm font-bold text-emerald-800">Berhasil!</h3>
                <p class="text-emerald-700 text-sm mt-0.5">{{ session('success') }}</p>
            </div>
            <button @click="show = false" class="text-emerald-500 hover:bg-emerald-100 rounded-lg p-1.5 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        </div>
    @endif

    <!-- Tabel Kategori -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50/80 border-b border-gray-200 text-gray-500 text-xs uppercase tracking-wider font-bold">
                    <tr>
                        <th scope="col" class="px-6 py-4 w-12 text-center">No</th>
                        <th scope="col" class="px-6 py-4">Nama Kategori</th>
                        <th scope="col" class="px-6 py-4">Deskripsi</th>
                        <th scope="col" class="px-6 py-4 text-center">Jumlah Pengaduan</th>
                        <th scope="col" class="px-6 py-4 text-center">Status</th>
                        <th scope="col" class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($kategoriList as $index => $kategori)
                    <tr class="hover:bg-gray-50/80 transition-colors group {{ ! $kategori->is_active ? 'opacity-60' : '' }}">
                        <td class="px-6 py-4 text-center text-gray-400 text-xs font-bold">
                            {{ ($kategoriList->currentPage() - 1) * $kategoriList->perPage() + $loop->iteration }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900 group-hover:text-polmed-blue transition-colors">{{ $kategori->nama_kategori }}</div>
                        </td>
                        <td class="px-6 py-4 max-w-sm">
                            <p class="text-gray-500 font-medium line-clamp-2">{{ $kategori->deskripsi ?: '-' }}</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-50 text-polmed-blue font-bold border border-blue-100">
                                {{ $kategori->pengaduan_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if ($kategori->is_active)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200">Aktif</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-gray-100 text-gray-500 ring-1 ring-gray-200">Nonaktif</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center whitespace-nowrap">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.kategori.edit', $kategori) }}"
                                   class="inline-flex items-center justify-center px-3 py-2 bg-white border border-gray-200 hover:border-polmed-blue hover:text-polmed-blue text-gray-700 rounded-lg text-xs font-bold transition-all shadow-sm">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.kategori.toggle-active', $kategori) }}">
                                    @csrf
                                    @method('PATCH')
                                    @if ($kategori->is_active)
                                        <button type="submit"
                                                class="inline-flex items-center justify-center px-3 py-2 bg-white border border-amber-200 hover:bg-amber-50 text-amber-700 rounded-lg text-xs font-bold transition-all shadow-sm">
                                            Nonaktifkan
                                        </button>
                                    @else
                                        <button type="submit"
                                                class="inline-flex items-center justify-center px-3 py-2 bg-white border border-emerald-200 hover:bg-emerald-50 text-emerald-700 rounded-lg text-xs font-bold transition-all shadow-sm">
                                            Aktifkan
                                        </button>
                                    @endif
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-24 text-center">
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
