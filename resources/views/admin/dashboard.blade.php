@extends('layouts.admin')
@section('title', 'Dashboard Admin')
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

<div class="space-y-8">

    {{-- ===== Banner / Welcome Message ===== --}}
    <div class="bg-gradient-to-r from-polmed-blue to-blue-800 rounded-2xl p-8 shadow-lg shadow-blue-900/10 text-white relative overflow-hidden">
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h2 class="text-2xl font-bold mb-2">Selamat Datang di Panel Admin</h2>
                <p class="text-blue-100/90 text-sm md:text-base max-w-xl leading-relaxed">
                    Pantau dan kelola seluruh pengaduan mahasiswa secara real-time. Pastikan Anda merespon pengaduan yang menunggu verifikasi.
                </p>
            </div>
            <div class="flex-shrink-0">
                <a href="{{ route('admin.pengaduan.index') }}" class="inline-flex items-center gap-2 bg-polmed-yellow text-polmed-blue hover:bg-yellow-400 font-bold px-6 py-3 rounded-xl shadow-md transition-colors duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    Tinjau Pengaduan
                </a>
            </div>
        </div>
        <!-- Decorative Shapes -->
        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white opacity-5 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-40 -mb-10 w-32 h-32 bg-polmed-yellow opacity-10 rounded-full blur-2xl"></div>
    </div>

    {{-- ===== Kartu Statistik ===== --}}
    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4 lg:gap-6">
        @php
            $cards = [
                ['label' => 'Total Pengaduan',     'value' => $stats['total'],               'bg' => 'bg-white', 'border' => 'border-gray-200',   'text' => 'text-gray-900',  'icon_bg' => 'bg-blue-50 text-blue-600',    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>'],
                ['label' => 'Menunggu Verifikasi',  'value' => $stats['menunggu'],            'bg' => 'bg-white', 'border' => 'border-gray-200',   'text' => 'text-gray-900',  'icon_bg' => 'bg-gray-100 text-gray-600',   'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
                ['label' => 'Sedang Diproses',      'value' => $stats['diproses'],            'bg' => 'bg-white', 'border' => 'border-blue-100',   'text' => 'text-blue-700',  'icon_bg' => 'bg-blue-50 text-blue-600',    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>'],
                ['label' => 'Menunggu Konfirmasi',  'value' => $stats['menunggu_konfirmasi'], 'bg' => 'bg-white', 'border' => 'border-cyan-100',   'text' => 'text-cyan-700',  'icon_bg' => 'bg-cyan-50 text-cyan-600',    'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
                ['label' => 'Selesai Ditangani',    'value' => $stats['selesai'],             'bg' => 'bg-white', 'border' => 'border-emerald-100','text' => 'text-emerald-700','icon_bg' => 'bg-emerald-50 text-emerald-600', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
                ['label' => 'Ditolak',              'value' => $stats['ditolak'],             'bg' => 'bg-white', 'border' => 'border-red-100',    'text' => 'text-red-700',    'icon_bg' => 'bg-red-50 text-red-600',      'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>'],
            ];
        @endphp
        @foreach ($cards as $card)
        <div class="relative group rounded-2xl border {{ $card['border'] }} {{ $card['bg'] }} p-6 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-start justify-between">
                <div>
                    <p class="text-3xl font-extrabold {{ $card['text'] }} tracking-tight">{{ $card['value'] }}</p>
                    <p class="text-xs font-medium text-gray-500 mt-1 uppercase tracking-wide">{{ $card['label'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-xl {{ $card['icon_bg'] }} flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform duration-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $card['icon'] !!}</svg>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ===== Peringatan Pengaduan Terlambat (SLA) ===== --}}
    @if ($pengaduanOverdue->isNotEmpty())
    <div class="bg-red-50 border border-red-200 rounded-2xl overflow-hidden">
        <div class="flex items-center justify-between px-6 lg:px-8 py-5 border-b border-red-100">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-red-100 text-red-600 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-red-800">{{ $stats['overdue'] }} Pengaduan Terlambat (&gt;{{ \App\Models\Pengaduan::SLA_HARI }} Hari Tanpa Perubahan)</h2>
                    <p class="text-xs text-red-600 mt-0.5">Mohon segera ditindaklanjuti agar tidak terlantar.</p>
                </div>
            </div>
        </div>
        <ul class="divide-y divide-red-100">
            @foreach ($pengaduanOverdue as $p)
            <li class="px-6 lg:px-8 py-3.5 flex items-center justify-between gap-4 hover:bg-red-100/40 transition-colors">
                <div class="min-w-0">
                    <p class="text-sm font-semibold text-gray-900 truncate">{{ $p->subjek }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">
                        {{ \App\Models\Pengaduan::statusLabels()[$p->status] ?? $p->status }} — sejak {{ $p->updated_at->diffForHumans() }}
                    </p>
                </div>
                <a href="{{ route('admin.pengaduan.show', $p) }}"
                   class="flex-shrink-0 inline-flex items-center px-3.5 py-1.5 bg-white border border-red-200 hover:border-red-400 text-red-700 rounded-lg text-xs font-bold transition-all shadow-sm">
                    Tindak Lanjuti
                </a>
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- ===== Tabel 10 Pengaduan Terbaru ===== --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden flex flex-col">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between px-6 lg:px-8 py-5 border-b border-gray-100 bg-white gap-4">
            <div>
                <h2 class="text-lg font-bold text-gray-900">10 Pengaduan Terbaru Masuk</h2>
                <p class="text-sm text-gray-500 mt-1">Daftar pengaduan terkini yang diajukan oleh mahasiswa.</p>
            </div>
            <a href="{{ route('admin.pengaduan.index') }}"
               class="inline-flex items-center gap-2 text-sm text-polmed-blue hover:text-blue-800 font-semibold bg-blue-50 hover:bg-blue-100 px-4 py-2 rounded-lg transition-colors">
                Lihat Semua
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </a>
        </div>

        @if ($pengaduanTerbaru->isEmpty())
            <div class="px-6 py-20 text-center flex flex-col items-center justify-center">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                </div>
                <p class="text-base font-medium text-gray-600">Belum ada pengaduan masuk.</p>
                <p class="text-sm text-gray-400 mt-1">Pengaduan baru akan muncul di sini secara otomatis.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-gray-50 border-b border-gray-100 text-gray-500 text-xs uppercase tracking-wider font-semibold">
                        <tr>
                            <th scope="col" class="px-6 lg:px-8 py-4">Info Tanggal</th>
                            <th scope="col" class="px-6 lg:px-8 py-4">Mahasiswa</th>
                            <th scope="col" class="px-6 lg:px-8 py-4">Detail Pengaduan</th>
                            <th scope="col" class="px-6 lg:px-8 py-4">Status</th>
                            <th scope="col" class="px-6 lg:px-8 py-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 bg-white">
                        @foreach ($pengaduanTerbaru as $p)
                        {{-- Highlight baris jika status masih menunggu_verifikasi dengan indikator border kiri --}}
                        <tr class="hover:bg-gray-50/80 transition-colors {{ $p->status === 'menunggu_verifikasi' ? 'bg-amber-50/30' : '' }} relative">
                            @if($p->status === 'menunggu_verifikasi')
                                <td class="absolute left-0 top-0 bottom-0 w-1 bg-amber-400 rounded-r-md"></td>
                            @endif
                            <td class="px-6 lg:px-8 py-4 whitespace-nowrap">
                                <div class="flex flex-col">
                                    <span class="text-gray-900 font-medium">{{ $p->created_at->format('d M Y') }}</span>
                                    <span class="text-xs text-gray-500 mt-0.5">{{ $p->created_at->format('H:i') }} WIB</span>
                                </div>
                            </td>
                            <td class="px-6 lg:px-8 py-4">
                                <div class="flex items-center gap-3">
                                    @if ($p->is_anonymous)
                                        <div class="w-8 h-8 rounded-full bg-purple-50 text-purple-400 flex items-center justify-center flex-shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.243 4.243L9.88 9.88"/></svg>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-500 text-sm italic">Mahasiswa Anonim</p>
                                            <p class="text-xs text-gray-400 mt-0.5">Identitas disembunyikan</p>
                                        </div>
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-blue-100 text-blue-700 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                            {{ strtoupper(substr($p->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-gray-900 text-sm">{{ $p->user->name }}</p>
                                            <p class="text-xs text-gray-500 mt-0.5">NIM: {{ $p->user->nim }}</p>
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 lg:px-8 py-4 max-w-sm">
                                <p class="text-gray-900 font-medium line-clamp-1 text-sm">{{ $p->subjek }}</p>
                                <p class="text-xs text-polmed-blue mt-0.5">{{ $p->kategori->nama_kategori }}</p>
                            </td>
                            <td class="px-6 lg:px-8 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                             {{ $badgeClass[$p->status] ?? 'bg-gray-100 text-gray-700 ring-1 ring-gray-200' }}">
                                    @if($p->status === 'menunggu_verifikasi')
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-500 mr-1.5 animate-pulse"></span>
                                    @endif
                                    {{ \App\Models\Pengaduan::statusLabels()[$p->status] ?? $p->status }}
                                </span>
                            </td>
                            <td class="px-6 lg:px-8 py-4 text-right whitespace-nowrap">
                                <a href="{{ route('admin.pengaduan.show', $p) }}"
                                   class="inline-flex items-center justify-center px-4 py-2 bg-white border border-gray-200 hover:border-polmed-blue hover:text-polmed-blue text-gray-700 rounded-lg text-sm font-semibold transition-all shadow-sm">
                                    Kelola
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
