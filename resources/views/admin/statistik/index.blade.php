@extends('layouts.admin')
@section('title', 'Statistik Pengaduan')
@section('content')

<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Statistik & Laporan</h1>
            <p class="text-sm text-gray-500 mt-1 font-medium">Pantau rekapitulasi data pengaduan untuk kebutuhan analisis dan laporan.</p>
        </div>
        <div>
            <!-- Tombol Export (Akan dihubungkan dengan fitur cetak PDF/Excel nanti di Fase 3) -->
            <a href="#" class="inline-flex justify-center items-center gap-2 bg-polmed-blue text-white hover:bg-blue-800 font-bold px-6 py-3 rounded-xl shadow-md transition-all duration-200 hover:-translate-y-0.5 focus:ring-4 focus:ring-blue-500/30">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Unduh Rekap Laporan
            </a>
        </div>
    </div>

    <!-- Ringkasan Cepat -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-polmed-blue to-blue-900 rounded-2xl p-6 shadow-md text-white relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-blue-100 font-bold text-sm uppercase tracking-wider mb-2">Total Pengaduan Bulan Ini</p>
                <div class="flex items-end gap-3">
                    <h3 class="text-5xl font-extrabold">{{ $stats['total'] ?? 0 }}</h3>
                    <p class="text-blue-200 text-sm font-medium mb-1">laporan masuk</p>
                </div>
            </div>
            <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:scale-125 transition-transform duration-500"></div>
        </div>

        <div class="bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-2xl p-6 shadow-md text-white relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-emerald-100 font-bold text-sm uppercase tracking-wider mb-2">Rasio Penyelesaian</p>
                <div class="flex items-end gap-3">
                    @php
                        $total = $stats['total'] ?? 0;
                        $selesai = $stats['selesai'] ?? 0;
                        $rasio = $total > 0 ? round(($selesai / $total) * 100) : 0;
                    @endphp
                    <h3 class="text-5xl font-extrabold">{{ $rasio }}<span class="text-3xl">%</span></h3>
                    <p class="text-emerald-200 text-sm font-medium mb-1">telah ditangani</p>
                </div>
            </div>
            <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:scale-125 transition-transform duration-500"></div>
        </div>

        <div class="bg-gradient-to-br from-polmed-yellow to-yellow-500 rounded-2xl p-6 shadow-md text-white relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-yellow-100 font-bold text-sm uppercase tracking-wider mb-2">Rata-rata Waktu Respon</p>
                <div class="flex items-end gap-3">
                    <h3 class="text-5xl font-extrabold"><span class="text-3xl">< </span>24</h3>
                    <p class="text-yellow-100 text-sm font-medium mb-1">jam</p>
                </div>
            </div>
            <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-white/10 rounded-full blur-2xl group-hover:scale-125 transition-transform duration-500"></div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Chart Dummy (Status) -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Distribusi Status Pengaduan</h3>
            
            <div class="space-y-5">
                @php
                    $statuses = [
                        ['label' => 'Selesai Ditangani', 'value' => $stats['selesai'] ?? 0, 'color' => 'bg-emerald-500'],
                        ['label' => 'Sedang Diproses', 'value' => $stats['diproses'] ?? 0, 'color' => 'bg-blue-500'],
                        ['label' => 'Membutuhkan Informasi', 'value' => $stats['butuh_info'] ?? 0, 'color' => 'bg-amber-500'],
                        ['label' => 'Menunggu Verifikasi', 'value' => $stats['menunggu'] ?? 0, 'color' => 'bg-gray-400'],
                        ['label' => 'Ditolak', 'value' => $stats['ditolak'] ?? 0, 'color' => 'bg-red-500'],
                    ];
                    $max = max(array_column($statuses, 'value'));
                    $max = $max > 0 ? $max : 1; // avoid devision by zero
                @endphp

                @foreach ($statuses as $st)
                <div>
                    <div class="flex justify-between text-sm mb-1.5 font-bold">
                        <span class="text-gray-700">{{ $st['label'] }}</span>
                        <span class="text-gray-900">{{ $st['value'] }} Laporan</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                        <div class="{{ $st['color'] }} h-2.5 rounded-full transition-all duration-1000" style="width: {{ ($st['value'] / $max) * 100 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Tabel Tren per Kategori -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 flex flex-col">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Rekap Berdasarkan Kategori</h3>
            
            <div class="overflow-x-auto flex-1">
                <table class="w-full text-sm text-left">
                    <thead class="text-xs text-gray-500 uppercase tracking-wider font-bold border-b border-gray-200">
                        <tr>
                            <th scope="col" class="pb-3 px-2">Kategori</th>
                            <th scope="col" class="pb-3 px-2 text-center">Total</th>
                            <th scope="col" class="pb-3 px-2 text-center">Selesai</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <!-- Data ini perlu dinamis dari backend nantinya, saat ini menggunakan placeholder statis -->
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-4 px-2 font-bold text-gray-800">Layanan Sarana & Prasarana</td>
                            <td class="py-4 px-2 text-center font-bold text-polmed-blue">12</td>
                            <td class="py-4 px-2 text-center font-bold text-emerald-600">8</td>
                        </tr>
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-4 px-2 font-bold text-gray-800">Layanan Dosen Pengampu</td>
                            <td class="py-4 px-2 text-center font-bold text-polmed-blue">5</td>
                            <td class="py-4 px-2 text-center font-bold text-emerald-600">4</td>
                        </tr>
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-4 px-2 font-bold text-gray-800">Layanan Administrasi</td>
                            <td class="py-4 px-2 text-center font-bold text-polmed-blue">3</td>
                            <td class="py-4 px-2 text-center font-bold text-emerald-600">3</td>
                        </tr>
                        <tr class="hover:bg-gray-50/50 transition">
                            <td class="py-4 px-2 font-bold text-gray-800">Layanan Laboratorium</td>
                            <td class="py-4 px-2 text-center font-bold text-polmed-blue">7</td>
                            <td class="py-4 px-2 text-center font-bold text-emerald-600">5</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="mt-4 pt-4 border-t border-gray-100 text-center">
                <p class="text-xs text-gray-400 font-medium italic">* Data rinci per kategori akan dikembangkan penuh di Fase 3.</p>
            </div>
        </div>
    </div>
</div>

@endsection
