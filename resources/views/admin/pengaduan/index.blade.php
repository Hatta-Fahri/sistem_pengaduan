@extends('layouts.admin')
@section('title', 'Manajemen Pengaduan')
@section('content')

@php
    $badgeClass = [
        'menunggu_verifikasi'           => 'bg-gray-100 text-gray-700',
        'sedang_diproses'               => 'bg-blue-100 text-blue-700',
        'membutuhkan_informasi_tambahan'=> 'bg-yellow-100 text-yellow-700',
        'selesai_ditangani'             => 'bg-green-100 text-green-700',
        'ditolak'                       => 'bg-red-100 text-red-700',
    ];
@endphp

<div class="space-y-5">

    {{-- ===== Form Filter Lengkap ===== --}}
    <form method="GET" action="{{ route('admin.pengaduan.index') }}"
          class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">

            {{-- Search --}}
            <div class="sm:col-span-2 lg:col-span-2">
                <label class="block text-xs font-medium text-gray-500 mb-1">Cari</label>
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Nama mahasiswa, NIM, atau subjek pengaduan..."
                           class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
                </div>
            </div>

            {{-- Filter Status --}}
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                <select name="status"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Status</option>
                    @foreach ($statusLabels as $key => $label)
                        <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Kategori --}}
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Kategori</label>
                <select name="kategori_id"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua Kategori</option>
                    @foreach ($kategoriList as $kat)
                        <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>
                            {{ $kat->nama_kategori }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Filter Tanggal Dari --}}
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Tanggal Dari</label>
                <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
            </div>

            {{-- Filter Tanggal Sampai --}}
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Tanggal Sampai</label>
                <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}"
                       class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" />
            </div>

        </div>

        <div class="flex items-center gap-3 mt-4 pt-4 border-t border-gray-100">
            <button type="submit"
                    class="px-5 py-2 bg-blue-700 hover:bg-blue-800 text-white text-sm font-semibold rounded-lg transition">
                Terapkan Filter
            </button>
            <a href="{{ route('admin.pengaduan.export', request()->only(['status', 'kategori_id', 'tanggal_dari', 'tanggal_sampai'])) }}"
               class="flex items-center gap-2 px-4 py-2 border border-green-600 text-green-700 hover:bg-green-50 text-sm font-semibold rounded-lg transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3"/>
                </svg>
                Ekspor CSV
            </a>
            @if (request()->hasAny(['status', 'kategori_id', 'search', 'tanggal_dari', 'tanggal_sampai']))
                <a href="{{ route('admin.pengaduan.index') }}"
                   class="px-4 py-2 border border-gray-300 text-gray-600 hover:bg-gray-50 text-sm font-medium rounded-lg transition">
                    Reset Filter
                </a>
                <span class="text-sm text-gray-400 ml-1">
                    {{ $pengaduan->total() }} pengaduan ditemukan
                </span>
            @else
                <span class="text-sm text-gray-400">Total: {{ $pengaduan->total() }} pengaduan</span>
            @endif
        </div>
    </form>

    {{-- ===== Tabel Pengaduan ===== --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">

        @if ($pengaduan->isEmpty())
            <div class="px-6 py-14 text-center">
                <svg class="w-14 h-14 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-sm font-medium text-gray-500">Tidak ada pengaduan ditemukan.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide w-8">No</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Nama Pelapor</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">NIM</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Kategori</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Subjek</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Tanggal</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($pengaduan as $index => $p)
                        <tr class="hover:bg-gray-50 transition-colors {{ $p->status === 'menunggu_verifikasi' ? 'bg-yellow-50 hover:bg-yellow-100' : '' }}">
                            <td class="px-4 py-3.5 text-gray-400 text-xs">
                                {{ ($pengaduan->currentPage() - 1) * $pengaduan->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-4 py-3.5">
                                <p class="font-medium text-gray-800">{{ $p->user->name }}</p>
                            </td>
                            <td class="px-4 py-3.5 text-gray-500 text-xs whitespace-nowrap">
                                {{ $p->user->nim }}
                            </td>
                            <td class="px-4 py-3.5 text-gray-500 text-xs whitespace-nowrap max-w-24">
                                <span class="line-clamp-1">{{ $p->kategori->nama_kategori }}</span>
                            </td>
                            <td class="px-4 py-3.5 max-w-xs">
                                <p class="text-gray-700 line-clamp-1">{{ $p->subjek }}</p>
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                             {{ $badgeClass[$p->status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ $statusLabels[$p->status] ?? $p->status }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 text-xs text-gray-400 whitespace-nowrap">
                                {{ $p->created_at->format('d/m/Y') }}<br>{{ $p->created_at->format('H:i') }}
                            </td>
                            <td class="px-4 py-3.5 text-right">
                                <a href="{{ route('admin.pengaduan.show', $p) }}"
                                   class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg text-xs font-semibold transition whitespace-nowrap">
                                    Kelola →
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($pengaduan->hasPages())
                <div class="px-5 py-4 border-t border-gray-100">
                    {{ $pengaduan->links() }}
                </div>
            @endif
        @endif
    </div>

</div>
@endsection
