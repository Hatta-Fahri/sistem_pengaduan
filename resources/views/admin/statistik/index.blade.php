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
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <form method="GET" action="{{ route('admin.statistik') }}" class="flex-1 sm:flex-none">
                <select name="tahun" onchange="this.form.submit()"
                        class="w-full sm:w-auto px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold text-gray-700 focus:ring-4 focus:ring-blue-500/20 focus:border-polmed-blue cursor-pointer">
                    @foreach ($tahunList as $tahun)
                        <option value="{{ $tahun }}" {{ $tahunTerpilih == $tahun ? 'selected' : '' }}>Tahun {{ $tahun }}</option>
                    @endforeach
                </select>
            </form>
            {{-- Export Buttons Group --}}
            <div class="flex-1 sm:flex-none flex rounded-xl overflow-hidden shadow-md" role="group" aria-label="Pilihan format ekspor laporan">
                {{-- Ekspor CSV --}}
                <a id="btn-export-csv"
                   href="{{ route('admin.pengaduan.export', ['tanggal_dari' => $tahunTerpilih . '-01-01', 'tanggal_sampai' => $tahunTerpilih . '-12-31']) }}"
                   title="Unduh laporan dalam format CSV"
                   class="inline-flex flex-1 sm:flex-none justify-center items-center gap-2 bg-polmed-blue text-white hover:bg-blue-800 font-bold px-5 py-3 text-sm transition-all duration-200 hover:-translate-y-0.5 focus:ring-4 focus:ring-blue-500/30 border-r border-blue-700/50">
                    <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    CSV
                </a>
                {{-- Ekspor PDF --}}
                <a id="btn-export-pdf"
                   href="{{ route('admin.statistik.export-pdf', ['tahun' => $tahunTerpilih]) }}"
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

    <!-- Ringkasan Cepat -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-gradient-to-br from-polmed-blue to-blue-900 rounded-2xl p-6 shadow-md text-white relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-blue-100 font-bold text-sm uppercase tracking-wider mb-2">Total Pengaduan Tahun {{ $tahunTerpilih }}</p>
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
        <!-- Doughnut: Distribusi Status -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Distribusi Status Pengaduan — Tahun {{ $tahunTerpilih }}</h3>
            <div class="relative" style="height: 280px;">
                <canvas id="chartStatus"></canvas>
            </div>
        </div>

        <!-- Bar: Rekap per Kategori -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <h3 class="text-lg font-bold text-gray-900 mb-6">Rekap Berdasarkan Kategori — Tahun {{ $tahunTerpilih }}</h3>
            <div class="relative" style="height: 280px;">
                <canvas id="chartKategori"></canvas>
            </div>
        </div>
    </div>

    <!-- Line: Tren 12 Bulan Terakhir -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
        <h3 class="text-lg font-bold text-gray-900 mb-1">Tren Pengaduan — 12 Bulan Terakhir</h3>
        <p class="text-xs text-gray-400 font-medium mb-6">Grafik ini selalu menampilkan 12 bulan berjalan, terlepas dari filter tahun di atas.</p>
        <div class="relative" style="height: 280px;">
            <canvas id="chartTren"></canvas>
        </div>
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
