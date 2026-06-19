@extends('layouts.admin')
@section('title', 'Dashboard Admin')
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

    {{-- ===== Kartu Statistik ===== --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-5 gap-4">
        @php
            $cards = [
                ['label' => 'Total Pengaduan',    'value' => $stats['total'],      'border' => 'border-gray-200',  'bg' => 'bg-white',      'text' => 'text-gray-800'],
                ['label' => 'Menunggu Verifikasi','value' => $stats['menunggu'],   'border' => 'border-gray-200',  'bg' => 'bg-gray-50',    'text' => 'text-gray-700'],
                ['label' => 'Sedang Diproses',    'value' => $stats['diproses'],   'border' => 'border-blue-200',  'bg' => 'bg-blue-50',    'text' => 'text-blue-700'],
                ['label' => 'Selesai Ditangani',  'value' => $stats['selesai'],    'border' => 'border-green-200', 'bg' => 'bg-green-50',   'text' => 'text-green-700'],
                ['label' => 'Ditolak',            'value' => $stats['ditolak'],    'border' => 'border-red-200',   'bg' => 'bg-red-50',     'text' => 'text-red-700'],
            ];
        @endphp
        @foreach ($cards as $card)
        <div class="rounded-xl border {{ $card['border'] }} {{ $card['bg'] }} p-5 text-center shadow-sm">
            <p class="text-3xl font-bold {{ $card['text'] }}">{{ $card['value'] }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $card['label'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- ===== Tabel 10 Pengaduan Terbaru ===== --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-800">10 Pengaduan Terbaru Masuk</h2>
            <a href="{{ route('admin.pengaduan.index') }}"
               class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                Kelola semua →
            </a>
        </div>

        @if ($pengaduanTerbaru->isEmpty())
            <div class="px-6 py-10 text-center text-gray-400 text-sm">
                Belum ada pengaduan masuk.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Tanggal</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Mahasiswa</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Subjek</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                            <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach ($pengaduanTerbaru as $p)
                        {{-- Highlight baris jika status masih menunggu_verifikasi --}}
                        <tr class="hover:bg-gray-50 transition-colors {{ $p->status === 'menunggu_verifikasi' ? 'bg-yellow-50 hover:bg-yellow-100' : '' }}">
                            <td class="px-5 py-3.5 text-gray-400 whitespace-nowrap text-xs">
                                {{ $p->created_at->format('d/m/Y') }}<br>{{ $p->created_at->format('H:i') }}
                            </td>
                            <td class="px-5 py-3.5">
                                <p class="font-medium text-gray-700 text-sm">{{ $p->user->name }}</p>
                                <p class="text-xs text-gray-400">NIM {{ $p->user->nim }}</p>
                            </td>
                            <td class="px-5 py-3.5 max-w-xs">
                                <p class="text-gray-700 line-clamp-1 text-sm">{{ $p->subjek }}</p>
                                <p class="text-xs text-gray-400">{{ $p->kategori->nama_kategori }}</p>
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                             {{ $badgeClass[$p->status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ \App\Models\Pengaduan::statusLabels()[$p->status] ?? $p->status }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <a href="{{ route('admin.pengaduan.show', $p) }}"
                                   class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg text-xs font-semibold transition">
                                    Kelola →
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
