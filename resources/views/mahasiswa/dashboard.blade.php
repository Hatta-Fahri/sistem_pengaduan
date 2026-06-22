@extends('layouts.mahasiswa')
@section('title', 'Dashboard Mahasiswa')
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

<div class="space-y-8">

    {{-- ===== Banner Welcome ===== --}}
    <div class="bg-[#2b4cba] rounded-2xl p-5 sm:p-6 shadow-lg shadow-blue-900/10 text-white relative overflow-hidden">
        <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold mb-1 tracking-tight">Halo, {{ auth()->user()->name }}! 👋</h2>
                <p class="text-white/70 text-xs max-w-xl leading-relaxed">
                    Sistem Informasi Layanan Pengaduan Mahasiswa (SILPM) memudahkan Anda untuk menyampaikan keluhan, saran, atau masalah terkait akademik dan fasilitas kampus secara cepat dan transparan.
                </p>
            </div>
            <div class="flex-shrink-0">
                <a href="{{ route('mahasiswa.pengaduan.create') }}"
                   class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-bold backdrop-blur-sm border border-white/20 bg-white/10 text-white/90 hover:bg-white/20 hover:border-white/40 hover:text-white transition-all duration-200 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Buat Pengaduan Baru
                </a>
            </div>
        </div>
        <!-- Decorative Shapes -->
        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white opacity-10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-40 -mb-10 w-32 h-32 bg-polmed-yellow opacity-10 rounded-full blur-2xl"></div>
    </div>

    {{-- ===== Kartu Statistik ===== --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        @php
            $cards = [
                ['label' => 'Total Pengaduan',    'value' => $stats['total'],      'bg' => 'bg-white',      'border' => 'border-gray-200',   'text' => 'text-gray-900',  'icon_bg' => 'bg-blue-50 text-blue-600', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>'],
                ['label' => 'Sedang Diproses',    'value' => $stats['diproses'],   'bg' => 'bg-white',      'border' => 'border-blue-100',   'text' => 'text-blue-700',  'icon_bg' => 'bg-blue-50 text-blue-600', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>'],
                ['label' => 'Selesai Ditangani',  'value' => $stats['selesai'],    'bg' => 'bg-white',      'border' => 'border-emerald-100','text' => 'text-emerald-700','icon_bg' => 'bg-emerald-50 text-emerald-600', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
                ['label' => 'Ditolak / Batal',    'value' => $stats['ditolak'],    'bg' => 'bg-white',      'border' => 'border-red-100',    'text' => 'text-red-700',    'icon_bg' => 'bg-red-50 text-red-600', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
            ];
        @endphp
        @foreach ($cards as $card)
        <div class="relative group rounded-xl border {{ $card['border'] }} {{ $card['bg'] }} p-4 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300">
            <div class="flex flex-row items-center justify-between gap-3">
                <div>
                    <p class="text-2xl font-extrabold {{ $card['text'] }} tracking-tight leading-none">{{ $card['value'] }}</p>
                    <p class="text-[10px] font-bold text-gray-400 mt-1.5 uppercase tracking-wide">{{ $card['label'] }}</p>
                </div>
                <div class="w-8 h-8 rounded-lg {{ $card['icon_bg'] }} flex items-center justify-center flex-shrink-0 group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $card['icon'] !!}</svg>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ===== Tabel 5 Pengaduan Terbaru ===== --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden flex flex-col">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between px-6 lg:px-8 py-5 border-b border-gray-100 gap-4">
            <div>
                <h2 class="text-lg font-bold text-gray-900">Pengaduan Terakhir Anda</h2>
                <p class="text-sm text-gray-500 mt-1">Status 5 pengaduan terbaru yang Anda kirimkan.</p>
            </div>
            <a href="{{ route('mahasiswa.pengaduan.index') }}"
               class="inline-flex items-center gap-2 text-sm text-polmed-blue hover:text-blue-800 font-semibold bg-blue-50 hover:bg-blue-100 px-4 py-2 rounded-xl transition-colors whitespace-nowrap">
                Lihat Seluruh Riwayat
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </a>
        </div>

        @if ($pengaduanTerbaru->isEmpty())
            <div class="px-6 py-24 text-center flex flex-col items-center justify-center">
                <div class="w-24 h-24 bg-gray-50 rounded-full flex items-center justify-center mb-5">
                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-gray-800">Belum Ada Pengaduan</h3>
                <p class="text-sm text-gray-500 mt-2 max-w-md mx-auto">Anda belum pernah membuat pengaduan apapun. Jika ada kendala, jangan ragu untuk melaporkannya melalui sistem ini.</p>
                <a href="{{ route('mahasiswa.pengaduan.create') }}" class="mt-6 inline-flex items-center gap-2 text-polmed-blue font-bold hover:underline">
                    Buat Pengaduan Sekarang
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50/80 border-b border-gray-100 text-gray-500 text-xs uppercase tracking-wider font-bold">
                        <tr>
                            <th scope="col" class="px-6 lg:px-8 py-4">Informasi Tanggal</th>
                            <th scope="col" class="px-6 lg:px-8 py-4">Subjek Pengaduan</th>
                            <th scope="col" class="px-6 lg:px-8 py-4">Kategori</th>
                            <th scope="col" class="px-6 lg:px-8 py-4">Status Saat Ini</th>
                            <th scope="col" class="px-6 lg:px-8 py-4 text-right">Detail</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach ($pengaduanTerbaru as $p)
                        <tr class="hover:bg-gray-50/80 transition-colors group">
                            <td class="px-6 lg:px-8 py-4 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <span class="text-gray-900 font-bold">{{ $p->created_at->format('d M Y') }}</span>
                                    <span class="text-xs text-gray-500 font-medium mt-0.5">{{ $p->created_at->format('H:i') }} WIB</span>
                                </div>
                            </td>
                            <td class="px-6 lg:px-8 py-4 max-w-xs">
                                <p class="text-gray-900 font-bold line-clamp-1 text-sm group-hover:text-polmed-blue transition-colors">{{ $p->subjek }}</p>
                            </td>
                            <td class="px-6 lg:px-8 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2.5 py-1 rounded-md text-[11px] font-bold bg-gray-100 text-gray-600 tracking-wide border border-gray-200">
                                    {{ $p->kategori->nama_kategori }}
                                </span>
                            </td>
                            <td class="px-6 lg:px-8 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $badgeClass[$p->status] ?? 'bg-gray-100 text-gray-700 ring-1 ring-gray-200' }}">
                                    @if($p->status === 'menunggu_verifikasi')
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-500 mr-1.5 animate-pulse"></span>
                                    @endif
                                    {{ \App\Models\Pengaduan::statusLabels()[$p->status] ?? $p->status }}
                                </span>
                            </td>
                            <td class="px-6 lg:px-8 py-4 text-right whitespace-nowrap">
                                <a href="{{ route('mahasiswa.pengaduan.show', $p) }}"
                                   class="inline-flex items-center justify-center p-2 bg-white border border-gray-200 hover:border-polmed-blue hover:text-polmed-blue text-gray-600 rounded-lg transition-all shadow-sm focus:ring-4 focus:ring-blue-500/20"
                                   title="Lihat Detail">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
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
