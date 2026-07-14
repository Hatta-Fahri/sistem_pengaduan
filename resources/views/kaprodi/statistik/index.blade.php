@extends('layouts.kaprodi')
@section('title', 'Statistik Pengaduan')
@section('content')

@php
    $badgeClass = [
        'menunggu_verifikasi'            => 'bg-gray-100 text-gray-700 ring-1 ring-gray-200',
        'sedang_diproses'                => 'bg-blue-50 text-blue-700 ring-1 ring-blue-200',
        'membutuhkan_informasi_tambahan' => 'bg-amber-50 text-amber-700 ring-1 ring-amber-200',
        'menunggu_konfirmasi_mahasiswa'  => 'bg-cyan-50 text-cyan-700 ring-1 ring-cyan-200',
        'selesai_ditangani'              => 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200',
        'ditolak'                        => 'bg-red-50 text-red-700 ring-1 ring-red-200',
    ];

    $trendSubtitle = match(true) {
        $startDate->diffInDays($endDate) <= 31 => 'Grafik menampilkan data pengaduan per hari.',
        default => 'Grafik menampilkan data pengaduan per bulan.',
    };
@endphp

<div class="space-y-6">

    {{-- ===== Header ===== --}}
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 space-y-5">

        {{-- Row 1: Judul + Tombol Ekspor --}}
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Statistik & Laporan</h1>
                <p class="text-sm text-gray-500 mt-1 font-medium">Pantau rekapitulasi data pengaduan untuk kebutuhan analisis dan laporan.</p>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto">
                {{-- Export Buttons Group --}}
                <div class="flex-1 sm:flex-none flex rounded-xl overflow-hidden shadow-md" role="group" aria-label="Pilihan format ekspor laporan">
                    {{-- Ekspor CSV --}}
                    <a id="btn-export-csv"
                       href="{{ route('kaprodi.pengaduan.export', ['tanggal_dari' => $startDate->format('Y-m-d'), 'tanggal_sampai' => $endDate->format('Y-m-d')]) }}"
                       title="Unduh laporan dalam format CSV"
                       class="inline-flex flex-1 sm:flex-none justify-center items-center gap-2 bg-polmed-blue text-white hover:bg-blue-800 font-bold px-5 py-3 text-sm transition-all duration-200 hover:-translate-y-0.5 focus:ring-4 focus:ring-blue-500/30 border-r border-blue-700/50">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        CSV
                    </a>
                    {{-- Ekspor PDF --}}
                    <a id="btn-export-pdf"
                       href="{{ route('kaprodi.statistik.export-pdf', array_filter(['periode' => $periode, 'bulan' => request('bulan'), 'tahun_bulan' => request('tahun_bulan'), 'tahun' => request('tahun'), 'tanggal_dari' => $startDate->format('Y-m-d'), 'tanggal_sampai' => $endDate->format('Y-m-d')])) }}"
                       title="Unduh laporan dalam format PDF"
                       class="inline-flex flex-1 sm:flex-none justify-center items-center gap-2 bg-red-600 text-white hover:bg-red-700 font-bold px-5 py-3 text-sm transition-all duration-200 hover:-translate-y-0.5 focus:ring-4 focus:ring-red-500/30">
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 13h1.5a1.5 1.5 0 010 3H10v-3zm0 0V10"/>
                        </svg>
                        PDF
                    </a>
                </div>
            </div>
        </div>

        {{-- Row 2: Filter Periode --}}
        <form method="GET" action="{{ route('kaprodi.statistik') }}"
              x-data="{
                  selectedPeriode: '{{ $periode }}',
                  submitIfNotCustom() {
                      if (this.selectedPeriode !== 'custom') {
                          this.$nextTick(() => this.$el.closest('form').submit());
                      }
                  }
              }"
              class="flex flex-wrap items-end gap-3 pt-4 border-t border-gray-100">

            {{-- Pertahankan parameter pencarian aktif --}}
            @foreach (['search', 'status', 'kategori_id'] as $param)
                @if (request()->filled($param))
                    <input type="hidden" name="{{ $param }}" value="{{ request($param) }}">
                @endif
            @endforeach

            {{-- Dropdown Periode --}}
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1.5 uppercase tracking-wide">Periode</label>
                <select name="periode" x-model="selectedPeriode"
                        @change="submitIfNotCustom()"
                        class="px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold text-gray-700 focus:ring-4 focus:ring-blue-500/20 focus:border-polmed-blue cursor-pointer min-w-[140px]">
                    <option value="mingguan">7 Hari Terakhir</option>
                    <option value="bulanan">Bulanan</option>
                    <option value="tahunan">Tahunan</option>
                    <option value="custom">Custom</option>
                </select>
            </div>

            {{-- Input Bulan (muncul hanya saat bulanan dipilih) --}}
            <div x-show="selectedPeriode === 'bulanan'" x-transition.opacity class="flex items-end gap-2">
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1.5 uppercase tracking-wide">Bulan</label>
                    <select name="bulan"
                            class="px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 focus:ring-4 focus:ring-blue-500/20 focus:border-polmed-blue min-w-[130px]">
                        @for ($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ (int) request('bulan', now()->month) === $m ? 'selected' : '' }}>
                                {{ \Carbon\Carbon::create(null, $m, 1)->locale('id')->isoFormat('MMMM') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1.5 uppercase tracking-wide">Tahun</label>
                    <select name="tahun_bulan"
                            class="px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 focus:ring-4 focus:ring-blue-500/20 focus:border-polmed-blue">
                        @for ($y = now()->year; $y >= 2020; $y--)
                            <option value="{{ $y }}" {{ (int) request('tahun_bulan', now()->year) === $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <button type="submit"
                        class="px-5 py-2.5 bg-polmed-blue hover:bg-blue-800 text-white text-sm font-bold rounded-xl shadow-md transition-all focus:ring-4 focus:ring-blue-500/30">
                    Terapkan
                </button>
            </div>

            {{-- Input Tahun (muncul hanya saat tahunan dipilih) --}}
            <div x-show="selectedPeriode === 'tahunan'" x-transition.opacity class="flex items-end gap-2">
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1.5 uppercase tracking-wide">Tahun</label>
                    <select name="tahun"
                            class="px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 focus:ring-4 focus:ring-blue-500/20 focus:border-polmed-blue">
                        @for ($y = now()->year; $y >= 2020; $y--)
                            <option value="{{ $y }}" {{ (int) request('tahun', now()->year) === $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
                <button type="submit"
                        class="px-5 py-2.5 bg-polmed-blue hover:bg-blue-800 text-white text-sm font-bold rounded-xl shadow-md transition-all focus:ring-4 focus:ring-blue-500/30">
                    Terapkan
                </button>
            </div>

            {{-- Input tanggal custom (muncul hanya saat custom dipilih) --}}
            <div x-show="selectedPeriode === 'custom'" x-transition.opacity class="flex flex-wrap items-end gap-2">
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1.5 uppercase tracking-wide">Dari</label>
                    <input type="date" name="tanggal_dari"
                           value="{{ $startDate->format('Y-m-d') }}"
                           class="px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 focus:ring-4 focus:ring-blue-500/20 focus:border-polmed-blue">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-1.5 uppercase tracking-wide">Sampai</label>
                    <input type="date" name="tanggal_sampai"
                           value="{{ $endDate->format('Y-m-d') }}"
                           class="px-3 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 focus:ring-4 focus:ring-blue-500/20 focus:border-polmed-blue">
                </div>
                <button type="submit"
                        class="px-5 py-2.5 bg-polmed-blue hover:bg-blue-800 text-white text-sm font-bold rounded-xl shadow-md transition-all focus:ring-4 focus:ring-blue-500/30">
                    Terapkan
                </button>
            </div>

            {{-- Badge periode aktif --}}
            <span class="inline-flex items-center px-3 py-2 bg-blue-50 text-polmed-blue rounded-lg text-xs font-bold gap-1.5 self-end">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                {{ $teksPeriode }}
            </span>
        </form>
    </div>

    {{-- ===== Ringkasan Cepat ===== --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-polmed-blue to-blue-900 rounded-2xl p-6 shadow-md text-white relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-blue-100 font-bold text-sm uppercase tracking-wider mb-2">Total Pengaduan — {{ $teksPeriode }}</p>
                <div class="flex items-end gap-3">
                    <h3 class="text-5xl font-extrabold">{{ $totalPengaduan }}</h3>
                    <p class="text-blue-200 text-sm font-medium mb-1">laporan masuk</p>
                </div>
            </div>
            <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:scale-125 transition-transform duration-500"></div>
        </div>

        <div class="bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-2xl p-6 shadow-md text-white relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-emerald-100 font-bold text-sm uppercase tracking-wider mb-2">Rata-rata Waktu Penyelesaian</p>
                <div class="flex items-end gap-3">
                    <h3 class="text-5xl font-extrabold">{{ $rataRataJam }}</h3>
                    <p class="text-emerald-200 text-sm font-medium mb-1">jam</p>
                </div>
            </div>
            <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:scale-125 transition-transform duration-500"></div>
        </div>

        <div class="bg-gradient-to-br from-polmed-yellow to-yellow-500 rounded-2xl p-6 shadow-md text-white relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-yellow-100 font-bold text-sm uppercase tracking-wider mb-2">Kategori Terbanyak</p>
                <div class="flex items-end gap-3">
                    <h3 class="text-2xl font-extrabold leading-tight">{{ $kategoriTerbanyak->nama_kategori ?? 'Belum ada data' }}</h3>
                </div>
                @if ($kategoriTerbanyak)
                    <p class="text-yellow-100 text-sm font-medium mt-1">{{ $kategoriTerbanyak->pengaduan_count }} laporan</p>
                @endif
            </div>
            <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:scale-125 transition-transform duration-500"></div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Doughnut: Distribusi Status --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Distribusi Status Pengaduan — {{ $teksPeriode }}</h3>
            <div class="relative" style="height: 280px;">
                <canvas id="chartStatus"></canvas>
            </div>
        </div>

        {{-- Bar: Rekap per Kategori --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Rekap Berdasarkan Kategori — {{ $teksPeriode }}</h3>
            <div class="relative" style="height: 280px;">
                <canvas id="chartKategori"></canvas>
            </div>
        </div>
    </div>

    {{-- Line: Tren Pengaduan --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-1">Tren Pengaduan — {{ $teksPeriode }}</h3>
        <p class="text-xs text-gray-400 font-medium mb-6">{{ $trendSubtitle }}</p>
        <div class="relative" style="height: 280px;">
            <canvas id="chartTren"></canvas>
        </div>
    </div>

    {{-- ===================================================== --}}
    {{-- ===== SECTION: Pencarian & Tabel Detail Pengaduan ===== --}}
    {{-- ===================================================== --}}

    <div class="flex items-center gap-3">
        <div class="h-6 w-1 bg-polmed-blue rounded-full flex-shrink-0"></div>
        <div>
            <h2 class="text-xl font-extrabold text-gray-900 tracking-tight">Cari &amp; Telusuri Data Pengaduan</h2>
            <p class="text-sm text-gray-500 mt-0.5">Pencarian di bawah ini memfilter data dalam periode yang dipilih di atas.</p>
        </div>
    </div>

    {{-- Form Filter & Search --}}
    <div x-data="{ filterOpen: false }" class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

        {{-- Toggle untuk tampilan mobile --}}
        <button @click="filterOpen = !filterOpen"
                class="w-full flex items-center justify-between px-6 py-4 bg-gray-50/50 hover:bg-gray-50 text-left transition lg:hidden">
            <span class="font-semibold text-gray-700 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                Filter &amp; Pencarian
            </span>
            <svg class="w-5 h-5 text-gray-500 transition-transform duration-200" :class="filterOpen ? 'rotate-180' : ''"
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <form method="GET" action="{{ route('kaprodi.statistik') }}"
              class="p-6 lg:block"
              :class="filterOpen ? 'block' : 'hidden'">

            {{-- Pertahankan filter periode aktif saat submit form pencarian --}}
            <input type="hidden" name="periode" value="{{ $periode }}">
            @if ($periode === 'bulanan')
                <input type="hidden" name="bulan" value="{{ request('bulan', now()->month) }}">
                <input type="hidden" name="tahun_bulan" value="{{ request('tahun_bulan', now()->year) }}">
            @elseif ($periode === 'tahunan')
                <input type="hidden" name="tahun" value="{{ request('tahun', now()->year) }}">
            @elseif ($periode === 'custom')
                <input type="hidden" name="tanggal_dari" value="{{ $startDate->format('Y-m-d') }}">
                <input type="hidden" name="tanggal_sampai" value="{{ $endDate->format('Y-m-d') }}">
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Search Bar --}}
                <div class="md:col-span-2">
                    <label for="search-statistik" class="block text-xs font-bold text-gray-600 mb-2 uppercase tracking-wide">
                        Pencarian
                    </label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" name="search" id="search-statistik"
                               value="{{ request('search') }}"
                               placeholder="Ketik nama mahasiswa, NIM, subjek, atau isi pengaduan..."
                               class="w-full pl-11 pr-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm focus:ring-4 focus:ring-blue-500/20 focus:border-polmed-blue focus:bg-white transition-all placeholder-gray-400 font-medium"/>
                    </div>
                </div>

                {{-- Filter Status --}}
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-2 uppercase tracking-wide">Status</label>
                    <div class="relative">
                        <select name="status"
                                class="appearance-none w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 focus:ring-4 focus:ring-blue-500/20 focus:border-polmed-blue focus:bg-white transition-all">
                            <option value="">Semua Status</option>
                            @foreach ($statusLabels as $key => $label)
                                <option value="{{ $key }}" {{ request('status') === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Filter Kategori --}}
                <div>
                    <label class="block text-xs font-bold text-gray-600 mb-2 uppercase tracking-wide">Kategori</label>
                    <div class="relative">
                        <select name="kategori_id"
                                class="appearance-none w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium text-gray-700 focus:ring-4 focus:ring-blue-500/20 focus:border-polmed-blue focus:bg-white transition-all">
                            <option value="">Semua Kategori</option>
                            @foreach ($kategoriList as $kat)
                                <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>
                                    {{ $kat->nama_kategori }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mt-6 pt-6 border-t border-gray-100">
                <div class="text-sm font-medium text-gray-500 w-full sm:w-auto text-center sm:text-left">
                    Ditemukan <span class="text-gray-900 font-bold">{{ $pengaduanDetail->total() }}</span> hasil pengaduan
                </div>
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    @if (request()->hasAny(['search', 'status', 'kategori_id']))
                        <a href="{{ route('kaprodi.statistik', array_filter(['periode' => $periode, 'bulan' => $periode === 'bulanan' ? request('bulan') : null, 'tahun_bulan' => $periode === 'bulanan' ? request('tahun_bulan') : null, 'tahun' => $periode === 'tahunan' ? request('tahun') : null, 'tanggal_dari' => $periode === 'custom' ? $startDate->format('Y-m-d') : null, 'tanggal_sampai' => $periode === 'custom' ? $endDate->format('Y-m-d') : null])) }}"
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

    {{-- ===== Tabel Detail Pengaduan ===== --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">

        @if ($pengaduanDetail->isEmpty())
            <div class="px-6 py-20 text-center flex flex-col items-center justify-center">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800">Tidak Ada Data</h3>
                <p class="text-sm text-gray-500 mt-1 max-w-sm">
                    @if (request()->hasAny(['search', 'status', 'kategori_id']))
                        Tidak ditemukan pengaduan yang cocok dengan filter yang Anda berikan.
                    @else
                        Belum ada data pengaduan dalam periode ini.
                    @endif
                </p>
            </div>
        @else
            <div class="overflow-x-auto w-full">
                <table class="w-full text-sm text-left" style="min-width: 740px;">
                    <thead class="bg-gray-50/80 border-b border-gray-200 text-gray-500 text-xs uppercase tracking-wider font-semibold">
                        <tr>
                            <th scope="col" class="px-4 py-4 w-10 text-center">No</th>
                            <th scope="col" class="px-4 py-4">Info Pelapor</th>
                            <th scope="col" class="px-4 py-4">Detail Pengaduan</th>
                            <th scope="col" class="px-4 py-4">Status &amp; Waktu</th>
                            <th scope="col" class="px-4 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach ($pengaduanDetail as $p)
                        <tr class="hover:bg-gray-50/80 transition-colors {{ $p->status === 'menunggu_verifikasi' ? 'bg-amber-50/30 border-l-4 border-amber-400' : 'border-l-4 border-transparent' }}">

                            {{-- Nomor --}}
                            <td class="px-4 py-4 text-center text-gray-400 text-xs font-medium">
                                {{ ($pengaduanDetail->currentPage() - 1) * $pengaduanDetail->perPage() + $loop->iteration }}
                            </td>

                            {{-- Info Pelapor --}}
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    @if ($p->is_anonymous)
                                        <div class="w-9 h-9 rounded-full bg-purple-50 text-purple-400 flex items-center justify-center ring-1 ring-purple-100 flex-shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                      d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.243 4.243L9.88 9.88"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-500 italic">Mahasiswa Anonim</p>
                                            <p class="text-xs text-gray-400 font-medium mt-0.5">Identitas disembunyikan</p>
                                        </div>
                                    @else
                                        <div class="w-9 h-9 rounded-full bg-[#2b4cba]/10 border border-[#2b4cba]/20 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-[#2b4cba]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                      d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <p class="font-bold text-gray-900">{{ $p->user->name }}</p>
                                            <p class="text-xs text-gray-500 font-medium mt-0.5">{{ $p->user->nim }}</p>
                                            @if ($p->user->class)
                                                <p class="text-xs text-gray-400 font-medium">{{ $p->user->class }}</p>
                                            @endif
                                        </div>
                                    @endif
                                </div>
                            </td>

                            {{-- Detail Pengaduan --}}
                            <td class="px-4 py-4 max-w-[240px]">
                                <p class="text-gray-900 font-semibold line-clamp-1 mb-1">{{ $p->subjek }}</p>
                                <span class="inline-flex px-2 py-0.5 rounded text-[10px] font-bold bg-gray-100 text-gray-600 uppercase tracking-wider mb-1">
                                    {{ $p->kategori->nama_kategori }}
                                </span>
                                <p class="text-xs text-gray-400 line-clamp-2 mt-1 leading-relaxed">
                                    {{ Str::limit($p->isi_pengaduan, 90) }}
                                </p>
                            </td>

                            {{-- Status & Waktu --}}
                            <td class="px-4 py-4">
                                <div class="flex flex-col items-start gap-1.5">
                                    <span class="inline-flex items-center justify-center text-center leading-tight px-2.5 py-1 rounded-lg text-xs font-bold {{ $badgeClass[$p->status] ?? 'bg-gray-100 text-gray-700 ring-1 ring-gray-200' }}">
                                        @if ($p->status === 'menunggu_verifikasi')
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
                                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        {{ $p->created_at->format('d M Y, H:i') }}
                                    </span>
                                </div>
                            </td>

                            {{-- Aksi --}}
                            <td class="px-4 py-4 text-center whitespace-nowrap">
                                <a href="{{ route('kaprodi.pengaduan.show', $p) }}"
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
            @if ($pengaduanDetail->hasPages())
                <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                    {{ $pengaduanDetail->links() }}
                </div>
            @endif
        @endif
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
<script>
    const statusLabels = @json(json_decode($statusLabelsJson));
    const statusData   = @json(json_decode($statusDataJson));
    const statusColors = @json(json_decode($statusColorsJson));

    new Chart(document.getElementById('chartStatus'), {
        type: 'doughnut',
        data: {
            labels: statusLabels,
            datasets: [{ data: statusData, backgroundColor: statusColors, borderWidth: 0 }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { position: 'bottom', labels: { font: { size: 11 }, boxWidth: 12 } } },
        },
    });

    const kategoriLabels = @json(json_decode($kategoriLabelsJson));
    const kategoriData   = @json(json_decode($kategoriDataJson));

    new Chart(document.getElementById('chartKategori'), {
        type: 'bar',
        data: {
            labels: kategoriLabels,
            datasets: [{ label: 'Jumlah Pengaduan', data: kategoriData, backgroundColor: '#1E3A8A', borderRadius: 6 }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
        },
    });

    const trendLabels = @json(json_decode($trendLabelsJson));
    const trendData   = @json(json_decode($trendDataJson));

    new Chart(document.getElementById('chartTren'), {
        type: 'line',
        data: {
            labels: trendLabels,
            datasets: [{
                label: 'Pengaduan Masuk',
                data: trendData,
                borderColor: '#F59E0B',
                backgroundColor: 'rgba(245, 158, 11, 0.15)',
                fill: true,
                tension: 0.35,
                pointRadius: 4,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
        },
    });
</script>
@endpush

@endsection
