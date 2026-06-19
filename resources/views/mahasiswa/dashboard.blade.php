@extends('layouts.mahasiswa')
@section('title', 'Dashboard Saya')
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

<div class="space-y-6">

    {{-- ===== Banner Sambutan ===== --}}
    <div class="bg-gradient-to-r from-blue-600 to-blue-800 rounded-2xl p-6 text-white shadow-sm">
        <div class="flex items-start justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold mb-1">Selamat datang, {{ $user->name }}! 👋</h2>
                <p class="text-blue-100 text-sm">NIM: {{ $user->nim }} &middot; Kelas: {{ $user->class }}</p>
                <p class="text-blue-200 text-xs mt-2 max-w-md">
                    Sampaikan keluhan atau masukan Anda. Kami akan merespon secepatnya.
                </p>
            </div>
            <a href="{{ route('mahasiswa.pengaduan.create') }}"
               class="flex-shrink-0 bg-white text-blue-700 hover:bg-blue-50 font-semibold text-sm px-5 py-2.5 rounded-xl transition shadow">
                + Buat Pengaduan
            </a>
        </div>
    </div>

    {{-- ===== Kartu Statistik ===== --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        @php
            $statCards = [
                ['label' => 'Total Pengaduan',   'value' => $stats['total'],    'bg' => 'bg-white',       'text' => 'text-gray-800', 'border' => 'border-gray-200'],
                ['label' => 'Sedang Diproses',   'value' => $stats['diproses'], 'bg' => 'bg-blue-50',     'text' => 'text-blue-700', 'border' => 'border-blue-200'],
                ['label' => 'Selesai Ditangani', 'value' => $stats['selesai'],  'bg' => 'bg-green-50',    'text' => 'text-green-700','border' => 'border-green-200'],
                ['label' => 'Ditolak',           'value' => $stats['ditolak'],  'bg' => 'bg-red-50',      'text' => 'text-red-700',  'border' => 'border-red-200'],
            ];
        @endphp
        @foreach ($statCards as $card)
        <div class="rounded-xl border {{ $card['border'] }} {{ $card['bg'] }} p-5 text-center shadow-sm">
            <p class="text-3xl font-bold {{ $card['text'] }}">{{ $card['value'] }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $card['label'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- ===== Tabel 5 Pengaduan Terbaru ===== --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h3 class="font-semibold text-gray-800">5 Pengaduan Terbaru</h3>
            <a href="{{ route('mahasiswa.pengaduan.index') }}"
               class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                Lihat semua →
            </a>
        </div>

        @if ($pengaduanTerbaru->isEmpty())
            <div class="px-6 py-14 text-center">
                <svg class="w-14 h-14 mx-auto mb-3 text-gray-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                          d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <p class="text-sm text-gray-400 font-medium">Belum ada pengaduan yang diajukan.</p>
                <a href="{{ route('mahasiswa.pengaduan.create') }}"
                   class="mt-3 inline-block text-sm text-blue-600 hover:underline">
                    Buat pengaduan pertama Anda
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Subjek</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Kategori</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Tanggal</th>
                            <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach ($pengaduanTerbaru as $p)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3.5">
                                <p class="font-medium text-gray-800 line-clamp-1 max-w-xs">{{ $p->subjek }}</p>
                            </td>
                            <td class="px-5 py-3.5 text-gray-500 text-xs whitespace-nowrap">
                                {{ $p->kategori->nama_kategori }}
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                             {{ $badgeClass[$p->status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ \App\Models\Pengaduan::statusLabels()[$p->status] ?? $p->status }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-xs text-gray-400 whitespace-nowrap">
                                {{ $p->created_at->format('d M Y') }}
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <a href="{{ route('mahasiswa.pengaduan.show', $p) }}"
                                   class="text-blue-600 hover:text-blue-800 text-xs font-medium">
                                    Detail →
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
@endsection
