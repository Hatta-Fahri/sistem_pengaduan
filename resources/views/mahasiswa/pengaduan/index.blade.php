@extends('layouts.mahasiswa')
@section('title', 'Riwayat Pengaduan Saya')
@section('content')

@php
    $badgeClass = [
        'menunggu_verifikasi'           => 'bg-gray-100 text-gray-700 ring-1 ring-gray-200',
        'sedang_diproses'               => 'bg-blue-50 text-blue-700 ring-1 ring-blue-200',
        'membutuhkan_informasi_tambahan'=> 'bg-amber-50 text-amber-700 ring-1 ring-amber-200',
        'selesai_ditangani'             => 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200',
        'ditolak'                       => 'bg-red-50 text-red-700 ring-1 ring-red-200',
    ];
@endphp

<div class="space-y-6">

    {{-- ===== Header & Aksi Cepat ===== --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Daftar Pengaduan Saya</h1>
            <p class="text-sm text-gray-500 mt-1 font-medium">Pantau status seluruh pengaduan yang pernah Anda buat.</p>
        </div>
        <div class="flex-shrink-0 w-full sm:w-auto">
            <a href="{{ route('mahasiswa.pengaduan.create') }}" class="w-full sm:w-auto inline-flex justify-center items-center gap-2 bg-[#2b4cba] hover:bg-[#2441a1] text-white font-bold px-6 py-3 rounded-xl shadow-lg shadow-blue-900/25 transition-all duration-200 hover:-translate-y-0.5 focus:ring-4 focus:ring-blue-500/30">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                Buat Pengaduan Baru
            </a>
        </div>
    </div>

    {{-- ===== Form Filter Lengkap ===== --}}
    <div x-data="{ filterOpen: false }" class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        
        <!-- Toggle Button for Mobile -->
        <button @click="filterOpen = !filterOpen" class="w-full flex items-center justify-between px-6 py-4 bg-gray-50/50 hover:bg-gray-50 text-left transition md:hidden">
            <span class="font-bold text-gray-700 flex items-center gap-2">
                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                Filter Pencarian
            </span>
            <svg class="w-5 h-5 text-gray-500 transition-transform duration-200" :class="filterOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
        </button>

        <form method="GET" action="{{ route('mahasiswa.pengaduan.index') }}"
              class="p-6 md:block transition-all duration-300"
              :class="filterOpen ? 'block' : 'hidden'">
            
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-5">
                {{-- Search --}}
                <div class="sm:col-span-2">
                    <label class="block text-xs font-bold text-gray-600 mb-2 uppercase tracking-wide">Pencarian Subjek</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari berdasarkan subjek pengaduan..."
                               class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium focus:ring-4 focus:ring-blue-500/20 focus:border-polmed-blue focus:bg-white transition-all placeholder-gray-400" />
                    </div>
                </div>

                {{-- Filter Status --}}
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-2 uppercase tracking-wide">Status</label>
                    <div class="relative">
                        <select name="status" class="appearance-none w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 focus:ring-4 focus:ring-blue-500/20 focus:border-polmed-blue focus:bg-white transition-all">
                            <option value="">Semua Status</option>
                            @foreach ($statusLabels as $key => $label)
                                <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>

                {{-- Filter Kategori --}}
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-2 uppercase tracking-wide">Kategori</label>
                    <div class="relative">
                        <select name="kategori_id" class="appearance-none w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 focus:ring-4 focus:ring-blue-500/20 focus:border-polmed-blue focus:bg-white transition-all">
                            <option value="">Semua Kategori</option>
                            @foreach ($kategoriList as $kat)
                                <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>
                                    {{ $kat->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mt-6 pt-6 border-t border-gray-100">
                <div class="text-sm font-medium text-gray-500 w-full sm:w-auto text-center sm:text-left">
                    Menampilkan <span class="text-gray-900 font-bold">{{ $pengaduan->total() }}</span> hasil
                </div>
                
                <div class="flex flex-col-reverse sm:flex-row items-center gap-3 w-full sm:w-auto">
                    @if (request()->hasAny(['status', 'kategori_id', 'search']))
                        <a href="{{ route('mahasiswa.pengaduan.index') }}"
                           class="w-full sm:w-auto text-center px-5 py-2.5 bg-white border border-gray-200 text-gray-500 hover:text-gray-800 hover:border-gray-300 text-sm font-semibold rounded-xl transition-all">
                            Reset Filter
                        </a>
                    @endif
                    <button type="submit"
                            class="w-full sm:w-auto px-6 py-2.5 bg-[#2b4cba] hover:bg-[#2441a1] text-white text-sm font-bold rounded-xl shadow-md shadow-blue-900/20 transition-all focus:ring-4 focus:ring-blue-500/30 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        Terapkan Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- ===== Tabel Pengaduan ===== --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

        @if ($pengaduan->isEmpty())
            <div class="px-6 py-24 text-center flex flex-col items-center justify-center">
                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-5">
                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800">Tidak Ada Data</h3>
                <p class="text-sm text-gray-500 mt-2 max-w-md">Kami tidak dapat menemukan pengaduan yang sesuai dengan pencarian Anda, atau Anda belum membuat pengaduan apapun.</p>
                        <a href="{{ route('mahasiswa.pengaduan.create') }}" class="mt-6 inline-flex items-center gap-2 px-5 py-2.5 bg-[#2b4cba] text-white font-bold rounded-xl shadow-md shadow-blue-900/20 hover:bg-[#2441a1] hover:-translate-y-0.5 transition-all focus:ring-4 focus:ring-blue-500/30">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    Buat Pengaduan Sekarang
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50/80 border-b border-gray-200 text-gray-500 text-xs uppercase tracking-wider font-bold">
                        <tr>
                            <th scope="col" class="px-6 py-4 w-12 text-center">No</th>
                            <th scope="col" class="px-6 py-4">Waktu Laporan</th>
                            <th scope="col" class="px-6 py-4">Subjek Pengaduan</th>
                            <th scope="col" class="px-6 py-4">Status & Kategori</th>
                            <th scope="col" class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach ($pengaduan as $index => $p)
                        <tr class="hover:bg-gray-50/80 transition-colors group relative">
                            <td class="px-6 py-4 text-center text-gray-400 text-xs font-bold">
                                {{ ($pengaduan->currentPage() - 1) * $pengaduan->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <span class="text-gray-900 font-bold">{{ $p->created_at->format('d M Y') }}</span>
                                    <span class="text-xs font-medium text-gray-500 mt-0.5">{{ $p->created_at->format('H:i') }} WIB</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 max-w-sm">
                                <p class="text-gray-900 font-bold line-clamp-2 text-[15px] group-hover:text-polmed-blue transition-colors">{{ $p->subjek }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex flex-col items-start gap-2">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $badgeClass[$p->status] ?? 'bg-gray-100 text-gray-700 ring-1 ring-gray-200' }}">
                                        @if($p->status === 'menunggu_verifikasi')
                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-500 mr-1.5 animate-pulse"></span>
                                        @endif
                                        {{ $statusLabels[$p->status] ?? $p->status }}
                                    </span>
                                    <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-600 uppercase tracking-wider border border-gray-200">
                                        {{ $p->kategori->nama_kategori }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center whitespace-nowrap">
                                <a href="{{ route('mahasiswa.pengaduan.show', $p) }}"
                                   class="inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-[#2b4cba]/8 border border-[#2b4cba]/20 hover:bg-[#2b4cba] hover:text-white text-[#2b4cba] rounded-lg text-sm font-bold transition-all duration-200 focus:ring-4 focus:ring-blue-500/20">
                                    Lihat Detail
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($pengaduan->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $pengaduan->links() }}
                </div>
            @endif
        @endif
    </div>

</div>
@endsection
