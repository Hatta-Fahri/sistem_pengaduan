@extends('layouts.admin')
@section('title', 'Manajemen Pengaduan')
@section('content')

@php
    $badgeClass = [
        'menunggu_verifikasi'           => 'bg-gray-100 text-gray-700 ring-1 ring-gray-200',
        'sedang_diproses'               => 'bg-blue-50 text-blue-700 ring-1 ring-blue-200',
        'membutuhkan_informasi_tambahan'=> 'bg-amber-50 text-amber-700 ring-1 ring-amber-200',
        'menunggu_konfirmasi_mahasiswa' => 'bg-cyan-50 text-cyan-700 ring-1 ring-cyan-200',
        'selesai_ditangani'             => 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200',
        'ditolak'                       => 'bg-red-50 text-red-700 ring-1 ring-red-200',
    ];
@endphp

<div class="space-y-6">

    {{-- ===== Header ===== --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Data Pengaduan Mahasiswa</h1>
            <p class="text-sm text-gray-500 mt-1">Kelola, verifikasi, dan pantau status seluruh pengaduan yang masuk.</p>
        </div>
    </div>

    {{-- ===== Form Filter Lengkap ===== --}}
    <div x-data="{ filterOpen: false }" class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        
        <!-- Toggle Button for Mobile -->
        <button @click="filterOpen = !filterOpen" class="w-full flex items-center justify-between px-6 py-4 bg-gray-50/50 hover:bg-gray-50 text-left transition lg:hidden">
            <span class="font-semibold text-gray-700 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                Filter Pencarian
            </span>
            <svg class="w-5 h-5 text-gray-500 transition-transform duration-200" :class="filterOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
        </button>

        <form method="GET" action="{{ route('admin.pengaduan.index') }}"
              class="p-6 lg:block transition-all duration-300"
              :class="filterOpen ? 'block' : 'hidden'">
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                {{-- Search --}}
                <div class="md:col-span-2 lg:col-span-4">
                    <label class="block text-xs font-bold text-gray-600 mb-2 uppercase tracking-wide">Pencarian Utama</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Ketik nama mahasiswa, NIM, atau subjek pengaduan..."
                               class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-4 focus:ring-blue-500/20 focus:border-polmed-blue focus:bg-white transition-all placeholder-gray-400 font-medium" />
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

                {{-- Filter Tanggal Dari --}}
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-2 uppercase tracking-wide">Mulai Tanggal</label>
                    <input type="date" name="tanggal_dari" value="{{ request('tanggal_dari') }}"
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 focus:ring-4 focus:ring-blue-500/20 focus:border-polmed-blue focus:bg-white transition-all" />
                </div>

                {{-- Filter Tanggal Sampai --}}
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-2 uppercase tracking-wide">Sampai Tanggal</label>
                    <input type="date" name="tanggal_sampai" value="{{ request('tanggal_sampai') }}"
                           class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 focus:ring-4 focus:ring-blue-500/20 focus:border-polmed-blue focus:bg-white transition-all" />
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mt-6 pt-6 border-t border-gray-100">
                <div class="text-sm font-medium text-gray-500 w-full sm:w-auto text-center sm:text-left">
                    Menampilkan <span class="text-gray-900 font-bold">{{ $pengaduan->total() }}</span> hasil pengaduan
                </div>
                
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    @if (request()->hasAny(['status', 'kategori_id', 'search', 'tanggal_dari', 'tanggal_sampai']))
                        <a href="{{ route('admin.pengaduan.index') }}"
                           class="flex-1 sm:flex-none text-center px-5 py-2.5 bg-white border-2 border-gray-200 text-gray-600 hover:bg-gray-50 hover:text-gray-900 text-sm font-bold rounded-xl transition-all">
                            Reset Filter
                        </a>
                    @endif
                    <button type="submit"
                            class="flex-1 sm:flex-none px-6 py-2.5 bg-polmed-blue hover:bg-blue-800 text-white text-sm font-bold rounded-xl shadow-md shadow-blue-900/20 transition-all focus:ring-4 focus:ring-blue-500/30">
                        Terapkan Filter
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- ===== Tabel Pengaduan ===== --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

        @if ($pengaduan->isEmpty())
            <div class="px-6 py-20 text-center flex flex-col items-center justify-center">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800">Tidak Ada Data</h3>
                <p class="text-sm text-gray-500 mt-1 max-w-sm">Kami tidak dapat menemukan pengaduan yang cocok dengan filter yang Anda berikan.</p>
            </div>
        @else
            <div class="overflow-x-auto w-full">
                <table class="w-full text-sm text-left" style="min-width: 700px;">
                    <thead class="bg-gray-50/80 border-b border-gray-200 text-gray-500 text-xs uppercase tracking-wider font-semibold">
                        <tr>
                            <th scope="col" class="px-4 py-4 w-10 text-center">No</th>
                            <th scope="col" class="px-4 py-4 w-[340px]">Info Pelapor</th>
                            <th scope="col" class="px-4 py-4 w-15" >Detail Pengaduan</th>
                            <th scope="col" class="px-4 py-4 w-44">Status & Waktu</th>
                            <th scope="col" class="px-4 py-4 w-40 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach ($pengaduan as $index => $p)
                        <tr class="hover:bg-gray-50/80 transition-colors {{ $p->status === 'menunggu_verifikasi' ? 'bg-amber-50/30 border-l-4 border-amber-400' : 'border-l-4 border-transparent' }}">
                            <td class="px-4 py-4 text-center text-gray-400 text-xs font-medium">
                                {{ ($pengaduan->currentPage() - 1) * $pengaduan->perPage() + $loop->iteration }}
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    @if ($p->is_anonymous)
                                        <div class="w-9 h-9 rounded-full bg-purple-50 text-purple-400 flex items-center justify-center ring-1 ring-purple-100">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.243 4.243L9.88 9.88"/></svg>
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-500 italic">Mahasiswa Anonim</p>
                                            <p class="text-xs text-gray-400 font-medium mt-0.5">Identitas disembunyikan</p>
                                        </div>
                                    @else
                                        <div class="w-9 h-9 rounded-full bg-[#2b4cba]/10 border border-[#2b4cba]/20 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-[#2b4cba]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-900">{{ $p->user->name }}</p>
                                            <p class="text-xs text-gray-500 font-medium mt-0.5">{{ $p->user->nim }}</p>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-4 max-w-[180px]">
                                <p class="text-gray-900 font-semibold line-clamp-1 mb-1">{{ $p->subjek }}</p>
                                <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-600 uppercase tracking-wider">
                                    {{ $p->kategori->nama_kategori }}
                                </span>
                            </td>
                            <td class="px-4 py-4">
                                <div class="flex flex-col items-start gap-1.5">
                                    <span class="inline-flex items-center justify-center text-center leading-tight px-2.5 py-1 rounded-lg text-xs font-bold {{ $badgeClass[$p->status] ?? 'bg-gray-100 text-gray-700 ring-1 ring-gray-200' }}">
                                        @if($p->status === 'menunggu_verifikasi')
                                            <span class="w-1.5 h-1.5 rounded-full bg-gray-500 mr-1.5 animate-pulse flex-shrink-0"></span>
                                        @endif
                                        {{ $statusLabels[$p->status] ?? $p->status }}
                                    </span>
                                    @if ($p->is_overdue)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold bg-red-50 text-red-600 ring-1 ring-red-200 uppercase tracking-wide">
                                            Terlambat
                                        </span>
                                    @endif
                                    <span class="text-xs font-medium text-gray-400 flex items-center gap-1 mt-1 whitespace-nowrap">
                                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ $p->created_at->format('d M Y, H:i') }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-center whitespace-nowrap">
                                <a href="{{ route('admin.pengaduan.show', $p) }}"
                                   class="inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-200 hover:border-polmed-blue hover:text-polmed-blue text-gray-700 rounded-lg text-sm font-bold transition-all shadow-sm focus:ring-4 focus:ring-blue-500/20">
                                    Detail
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
