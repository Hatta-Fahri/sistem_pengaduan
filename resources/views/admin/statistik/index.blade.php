@extends('layouts.admin')
@section('title', 'Statistik & Rekap')
@section('content')

<div class="space-y-6">

    {{-- ===== Filter Tahun ===== --}}
    <form method="GET" action="{{ route('admin.statistik') }}"
          class="bg-white rounded-xl border border-gray-200 shadow-sm p-5 flex items-center justify-between gap-4 flex-wrap">
        <div>
            <h1 class="text-lg font-bold text-gray-900">Statistik &amp; Rekap Pengaduan</h1>
            <p class="text-sm text-gray-500 mt-0.5">Ringkasan data pengaduan untuk tahun terpilih.</p>
        </div>
        <div class="flex items-center gap-2">
            <label for="tahun" class="text-xs font-medium text-gray-500">Tahun</label>
            <select id="tahun" name="tahun" onchange="this.form.submit()"
                    class="px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @foreach ($tahunList as $thn)
                    <option value="{{ $thn }}" {{ $tahunTerpilih === $thn ? 'selected' : '' }}>{{ $thn }}</option>
                @endforeach
            </select>
        </div>
    </form>

    {{-- ===== Kartu Ringkasan ===== --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="rounded-xl border border-gray-200 bg-white p-5 text-center shadow-sm">
            <p class="text-3xl font-bold text-gray-800">{{ $totalPengaduan }}</p>
            <p class="text-xs text-gray-500 mt-1">Total Pengaduan Tahun {{ $tahunTerpilih }}</p>
        </div>
        <div class="rounded-xl border border-blue-200 bg-blue-50 p-5 text-center shadow-sm">
            <p class="text-3xl font-bold text-blue-700">{{ $rataRataJam }} <span class="text-base font-medium">jam</span></p>
            <p class="text-xs text-gray-500 mt-1">Rata-rata Waktu Penyelesaian</p>
        </div>
        <div class="rounded-xl border border-green-200 bg-green-50 p-5 text-center shadow-sm">
            <p class="text-lg font-bold text-green-700 line-clamp-1">
                {{ $kategoriTerbanyak && $kategoriTerbanyak->pengaduan_count > 0 ? $kategoriTerbanyak->nama_kategori : '—' }}
            </p>
            <p class="text-xs text-gray-500 mt-1">
                Kategori Terbanyak
                @if ($kategoriTerbanyak && $kategoriTerbanyak->pengaduan_count > 0)
                    ({{ $kategoriTerbanyak->pengaduan_count }} pengaduan)
                @endif
            </p>
        </div>
    </div>

    {{-- ===== Grafik ===== --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <h2 class="font-semibold text-gray-800 mb-4">Pengaduan per Kategori</h2>
            <canvas id="chartKategori" height="260"></canvas>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <h2 class="font-semibold text-gray-800 mb-4">Distribusi per Status</h2>
            <canvas id="chartStatus" height="260"></canvas>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
        <h2 class="font-semibold text-gray-800 mb-4">Tren Pengaduan — 12 Bulan Terakhir</h2>
        <canvas id="chartTren" height="100"></canvas>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const dataKategoriLabels = JSON.parse(@js($kategoriLabelsJson));
    const dataKategoriJumlah = JSON.parse(@js($kategoriDataJson));
    const dataStatusLabels   = JSON.parse(@js($statusLabelsJson));
    const dataStatusJumlah   = JSON.parse(@js($statusDataJson));
    const dataStatusWarna    = JSON.parse(@js($statusColorsJson));
    const dataTrenLabels     = JSON.parse(@js($trendLabelsJson));
    const dataTrenJumlah     = JSON.parse(@js($trendDataJson));

    new Chart(document.getElementById('chartKategori'), {
        type: 'bar',
        data: {
            labels: dataKategoriLabels,
            datasets: [{
                label: 'Jumlah Pengaduan',
                data: dataKategoriJumlah,
                backgroundColor: '#1d4ed8',
                borderRadius: 6,
            }],
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
        },
    });

    new Chart(document.getElementById('chartStatus'), {
        type: 'doughnut',
        data: {
            labels: dataStatusLabels,
            datasets: [{
                data: dataStatusJumlah,
                backgroundColor: dataStatusWarna,
                borderWidth: 0,
            }],
        },
        options: {
            responsive: true,
            plugins: { legend: { position: 'bottom' } },
        },
    });

    new Chart(document.getElementById('chartTren'), {
        type: 'line',
        data: {
            labels: dataTrenLabels,
            datasets: [{
                label: 'Pengaduan Masuk',
                data: dataTrenJumlah,
                borderColor: '#1d4ed8',
                backgroundColor: 'rgba(29, 78, 216, 0.1)',
                fill: true,
                tension: 0.3,
            }],
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true, ticks: { precision: 0 } } },
        },
    });
</script>
@endsection
