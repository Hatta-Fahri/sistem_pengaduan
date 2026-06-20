@extends('layouts.mahasiswa')
@section('title', 'Detail Pengaduan')
@section('content')

@php
    $badgeClass = [
        'menunggu_verifikasi'           => 'bg-gray-100 text-gray-700 ring-1 ring-gray-200',
        'sedang_diproses'               => 'bg-blue-50 text-blue-700 ring-1 ring-blue-200',
        'membutuhkan_informasi_tambahan'=> 'bg-amber-50 text-amber-700 ring-1 ring-amber-200',
        'selesai_ditangani'             => 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200',
        'ditolak'                       => 'bg-red-50 text-red-700 ring-1 ring-red-200',
    ];

    $timelineIcons = [
        'dibuat'                        => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>',
        'menunggu_verifikasi'           => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>',
        'sedang_diproses'               => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>',
        'membutuhkan_informasi_tambahan'=> '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
        'selesai_ditangani'             => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>',
        'ditolak'                       => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>',
    ];

    $timelineColors = [
        'dibuat'                        => 'bg-blue-100 text-blue-600',
        'menunggu_verifikasi'           => 'bg-gray-100 text-gray-500',
        'sedang_diproses'               => 'bg-blue-100 text-blue-600',
        'membutuhkan_informasi_tambahan'=> 'bg-amber-100 text-amber-600',
        'selesai_ditangani'             => 'bg-emerald-100 text-emerald-600',
        'ditolak'                       => 'bg-red-100 text-red-600',
    ];
@endphp

<div class="max-w-5xl mx-auto space-y-6">

    {{-- ===== Breadcrumb ===== --}}
    <nav class="flex text-sm text-gray-500 gap-2 items-center font-medium mb-4">
        <a href="{{ route('mahasiswa.dashboard') }}" class="hover:text-polmed-blue transition-colors flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Dashboard
        </a>
        <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <a href="{{ route('mahasiswa.pengaduan.index') }}" class="hover:text-polmed-blue transition-colors">Pengaduan Saya</a>
        <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <span class="text-polmed-blue font-bold">Detail Pengaduan</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8 items-start">
        
        {{-- ===== Kolom Utama (Kiri): Detail Laporan ===== --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden relative">
                <!-- Status Bar Top -->
                <div class="absolute top-0 left-0 right-0 h-1.5 {{ str_contains($badgeClass[$pengaduan->status], 'emerald') ? 'bg-emerald-500' : (str_contains($badgeClass[$pengaduan->status], 'red') ? 'bg-red-500' : (str_contains($badgeClass[$pengaduan->status], 'amber') ? 'bg-amber-500' : 'bg-polmed-blue')) }}"></div>
                
                <div class="p-6 sm:p-8">
                    <!-- Badges -->
                    <div class="flex flex-wrap gap-2 mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $badgeClass[$pengaduan->status] ?? 'bg-gray-100 text-gray-700' }}">
                            @if($pengaduan->status === 'menunggu_verifikasi')
                                <span class="w-2 h-2 rounded-full bg-gray-500 mr-2 animate-pulse"></span>
                            @endif
                            {{ \App\Models\Pengaduan::statusLabels()[$pengaduan->status] ?? $pengaduan->status }}
                        </span>
                        <span class="inline-flex items-center px-3 py-1 rounded-md text-xs font-bold bg-gray-100 text-gray-600 border border-gray-200 tracking-wide uppercase">
                            {{ $pengaduan->kategori->nama_kategori }}
                        </span>
                    </div>

                    <!-- Judul -->
                    <h2 class="text-2xl font-extrabold text-gray-900 tracking-tight leading-snug mb-6">
                        {{ $pengaduan->subjek }}
                    </h2>

                    <hr class="border-gray-100 mb-6">

                    <!-- Info Laporan -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-8">
                        <div>
                            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Tanggal Dikirim</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $pengaduan->created_at->format('d M Y, H:i') }} WIB</p>
                        </div>
                        <div>
                            <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Waktu Kejadian</p>
                            <p class="text-sm font-semibold text-gray-900">{{ \Carbon\Carbon::parse($pengaduan->tanggal_kejadian)->format('d M Y, H:i') }} WIB</p>
                        </div>
                    </div>

                    <!-- Isi Laporan -->
                    <div>
                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-3">Detail Pengaduan</p>
                        <div class="bg-gray-50 rounded-xl p-5 border border-gray-100">
                            <p class="text-gray-800 text-[15px] font-medium leading-relaxed whitespace-pre-wrap">{{ $pengaduan->isi_pengaduan }}</p>
                        </div>
                    </div>

                    <!-- Bukti Pendukung -->
                    @if ($pengaduan->bukti)
                    <div class="mt-6">
                        <p class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-3">Bukti Pendukung</p>
                        @if (str_ends_with($pengaduan->bukti, '.pdf'))
                            <a href="{{ $pengaduan->bukti_url }}" target="_blank"
                               class="inline-flex items-center gap-2 px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold text-polmed-blue hover:bg-gray-100 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Lihat Dokumen PDF
                            </a>
                        @else
                            <a href="{{ $pengaduan->bukti_url }}" target="_blank" class="block w-fit">
                                <img src="{{ $pengaduan->bukti_url }}" alt="Bukti pendukung"
                                     class="max-h-64 rounded-xl border border-gray-200 hover:opacity-90 transition-opacity" />
                            </a>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- ===== Kolom Samping (Kanan): Timeline Status ===== --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8 relative">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 bg-polmed-light rounded-xl flex items-center justify-center text-polmed-blue">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 tracking-tight">Timeline Riwayat</h3>
                </div>

                <div class="flow-root">
                    <ul role="list" class="-mb-8">
                        <!-- Step: Dibuat -->
                        <li>
                            <div class="relative pb-8">
                                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full {{ $timelineColors['dibuat'] }} flex items-center justify-center ring-4 ring-white shadow-sm">
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $timelineIcons['dibuat'] !!}</svg>
                                        </span>
                                    </div>
                                    <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                        <div>
                                            <p class="text-sm font-bold text-gray-900">Pengaduan Dibuat</p>
                                        </div>
                                        <div class="whitespace-nowrap text-right text-xs font-semibold text-gray-500">
                                            {{ $pengaduan->created_at->format('d M H:i') }}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>

                        <!-- Riwayat Status Dinamis -->
                        @foreach ($pengaduan->statusHistory as $idx => $riwayat)
                            <li>
                                <div class="relative pb-8">
                                    @if (!$loop->last)
                                        <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                    @endif
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full {{ $timelineColors[$riwayat->status_baru] ?? 'bg-gray-100 text-gray-500' }} flex items-center justify-center ring-4 ring-white shadow-sm">
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $timelineIcons[$riwayat->status_baru] ?? $timelineIcons['menunggu_verifikasi'] !!}</svg>
                                            </span>
                                        </div>
                                        <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                            <div class="flex-1">
                                                <p class="text-sm font-bold text-gray-900">
                                                    Status: {{ \App\Models\Pengaduan::statusLabels()[$riwayat->status_baru] ?? $riwayat->status_baru }}
                                                </p>
                                                @if ($riwayat->catatan)
                                                    <div class="mt-2 text-xs font-medium text-gray-600 bg-gray-50 p-3 rounded-lg border border-gray-100 relative">
                                                        <div class="absolute -top-1.5 left-3 w-3 h-3 bg-gray-50 border-t border-l border-gray-100 transform rotate-45"></div>
                                                        <span class="block font-bold text-gray-800 mb-0.5">Catatan Admin:</span>
                                                        {{ $riwayat->catatan }}
                                                    </div>
                                                @endif
                                            </div>
                                            <div class="whitespace-nowrap text-right text-xs font-semibold text-gray-500">
                                                {{ $riwayat->created_at->format('d M H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>

                {{-- Status Selesai / Ditolak Banner --}}
                @if (in_array($pengaduan->status, ['selesai_ditangani', 'ditolak']))
                    <div class="mt-8 pt-6 border-t border-gray-100">
                        <div class="flex items-center gap-3 p-3 rounded-xl {{ $pengaduan->status === 'selesai_ditangani' ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-700' }}">
                            <svg class="w-6 h-6 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                {!! $pengaduan->status === 'selesai_ditangani' ? '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>' : '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>' !!}
                            </svg>
                            <p class="text-sm font-bold leading-tight">Pengaduan ini telah {{ $pengaduan->status === 'selesai_ditangani' ? 'Selesai' : 'Ditolak' }} dan ditutup.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
