@extends('layouts.mahasiswa')
@section('title', 'Detail Pengaduan')
@section('content')

@php
    $badgeClass = [
        'menunggu_verifikasi'           => 'bg-gray-100 text-gray-700',
        'sedang_diproses'               => 'bg-blue-100 text-blue-700',
        'membutuhkan_informasi_tambahan'=> 'bg-yellow-100 text-yellow-700',
        'selesai_ditangani'             => 'bg-green-100 text-green-700',
        'ditolak'                       => 'bg-red-100 text-red-700',
    ];
    $dotClass = [
        'menunggu_verifikasi'           => 'bg-gray-400',
        'sedang_diproses'               => 'bg-blue-500',
        'membutuhkan_informasi_tambahan'=> 'bg-yellow-500',
        'selesai_ditangani'             => 'bg-green-500',
        'ditolak'                       => 'bg-red-500',
    ];
    $currentBadge = $badgeClass[$pengaduan->status] ?? 'bg-gray-100 text-gray-700';
    $label = $statusLabels[$pengaduan->status] ?? $pengaduan->status;
@endphp

<div class="max-w-3xl mx-auto space-y-6">

    {{-- ===== Breadcrumb ===== --}}
    <nav class="flex text-sm text-gray-400 gap-1 items-center">
        <a href="{{ route('mahasiswa.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
        <span>/</span>
        <a href="{{ route('mahasiswa.pengaduan.index') }}" class="hover:text-blue-600">Pengaduan Saya</a>
        <span>/</span>
        <span class="text-gray-600 truncate max-w-xs">{{ \Str::limit($pengaduan->subjek, 40) }}</span>
    </nav>

    {{-- ===== Kartu Utama Pengaduan ===== --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">

        {{-- Header: Subjek + Badge Status --}}
        <div class="flex items-start justify-between gap-4 mb-5">
            <div class="flex-1 min-w-0">
                <h1 class="text-lg font-bold text-gray-900 leading-snug">{{ $pengaduan->subjek }}</h1>
                <p class="text-sm text-gray-400 mt-1">
                    Diajukan {{ $pengaduan->created_at->diffForHumans() }} &middot; #{{ $pengaduan->id }}
                </p>
            </div>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold flex-shrink-0
                         {{ $currentBadge }}">
                {{ $label }}
            </span>
        </div>

        {{-- Meta Info Grid --}}
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 py-4 border-t border-b border-gray-100 text-sm">
            <div>
                <p class="text-gray-400 text-xs mb-0.5">Kategori</p>
                <p class="font-medium text-gray-700">{{ $pengaduan->kategori->nama_kategori }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs mb-0.5">Tanggal Kejadian</p>
                <p class="font-medium text-gray-700">{{ $pengaduan->tanggal_kejadian->format('d M Y, H:i') }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs mb-0.5">Tanggal Pengajuan</p>
                <p class="font-medium text-gray-700">{{ $pengaduan->created_at->format('d M Y, H:i') }}</p>
            </div>
        </div>

        {{-- Isi Pengaduan --}}
        <div class="mt-5">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Isi Pengaduan</p>
            <div class="bg-gray-50 rounded-xl p-4 text-sm text-gray-700 leading-relaxed whitespace-pre-wrap border border-gray-100">{{ $pengaduan->isi_pengaduan }}</div>
        </div>

        {{-- Catatan Admin (jika ada) --}}
        @if ($pengaduan->catatan_admin)
        <div class="mt-5 bg-blue-50 rounded-xl p-4 border border-blue-100">
            <p class="text-xs font-semibold text-blue-600 uppercase tracking-wide mb-2">💬 Catatan dari Admin</p>
            <p class="text-sm text-blue-900 leading-relaxed whitespace-pre-wrap">{{ $pengaduan->catatan_admin }}</p>
        </div>
        @endif
    </div>

    {{-- ===== Timeline Riwayat Status (ASC: terlama ke terbaru) ===== --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <h2 class="font-semibold text-gray-800 mb-6">Riwayat Status</h2>

        @if ($pengaduan->statusHistory->isEmpty())
            <p class="text-sm text-gray-400 text-center py-4">Belum ada riwayat status.</p>
        @else
            <div class="space-y-0">
                @foreach ($pengaduan->statusHistory as $history)
                @php
                    $hBadge = $badgeClass[$history->status_baru] ?? 'bg-gray-100 text-gray-700';
                    $hDot   = $dotClass[$history->status_baru] ?? 'bg-gray-400';
                    $hLabel = $statusLabels[$history->status_baru] ?? $history->status_baru;
                    $hLamaLabel = $history->status_lama
                        ? ($statusLabels[$history->status_lama] ?? $history->status_lama)
                        : null;
                @endphp
                <div class="flex gap-4">
                    {{-- Timeline indicator --}}
                    <div class="flex flex-col items-center w-4 flex-shrink-0">
                        <div class="w-3.5 h-3.5 rounded-full {{ $hDot }} ring-2 ring-white flex-shrink-0 mt-0.5"></div>
                        @if (!$loop->last)
                            <div class="w-0.5 flex-1 bg-gray-200 my-1"></div>
                        @endif
                    </div>

                    {{-- Timeline content --}}
                    <div class="pb-6 flex-1 min-w-0">
                        <div class="flex items-start justify-between gap-2 flex-wrap">
                            <div class="flex items-center gap-2 flex-wrap">
                                @if ($hLamaLabel)
                                    <span class="text-xs text-gray-400">{{ $hLamaLabel }}</span>
                                    <span class="text-gray-300 text-xs">→</span>
                                @endif
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $hBadge }}">
                                    {{ $hLabel }}
                                </span>
                            </div>
                            <span class="text-xs text-gray-400 whitespace-nowrap">
                                {{ $history->created_at->format('d M Y, H:i') }}
                            </span>
                        </div>

                        @if ($history->catatan)
                            <p class="text-sm text-gray-600 mt-1.5 leading-relaxed">{{ $history->catatan }}</p>
                        @endif
                        <p class="text-xs text-gray-400 mt-1">
                            Oleh: {{ $history->changedBy->name }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Kembali --}}
    <div>
        <a href="{{ route('mahasiswa.pengaduan.index') }}"
           class="inline-flex items-center gap-2 text-sm text-gray-500 hover:text-blue-600 transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Daftar Pengaduan
        </a>
    </div>

</div>
@endsection
