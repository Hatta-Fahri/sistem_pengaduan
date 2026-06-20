<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>413 — Berkas Terlalu Besar | SILPM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { font-family: 'Inter', system-ui, -apple-system, sans-serif; }</style>
</head>
<body class="h-full font-sans antialiased">

<div class="min-h-full flex flex-col items-center justify-center px-4 py-12">
    <div class="max-w-md w-full text-center">
        <div class="w-20 h-20 bg-amber-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
        </div>

        <p class="text-sm font-semibold text-amber-600 mb-2">Error 413</p>
        <h1 class="text-2xl font-bold text-gray-900 mb-3">Berkas Terlalu Besar</h1>
        <p class="text-gray-500 text-sm leading-relaxed mb-8">
            Berkas atau data yang Anda kirim melebihi batas maksimal yang diizinkan server.
            Untuk lampiran bukti, pastikan ukuran berkas tidak lebih dari <strong>5MB</strong>
            (format JPG, PNG, atau PDF), lalu coba kirim ulang.
        </p>

        <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
            <button onclick="history.back()"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-700 hover:bg-blue-800 text-white font-semibold text-sm rounded-lg shadow transition">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Coba Lagi
            </button>
            <a href="{{ auth()->check() ? (auth()->user()->isAdmin() ? route('admin.dashboard') : route('mahasiswa.dashboard')) : route('login') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 font-semibold text-sm rounded-lg transition">
                Ke Dashboard
            </a>
        </div>
    </div>
</div>

</body>
</html>
