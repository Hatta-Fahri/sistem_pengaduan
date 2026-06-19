<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>404 — Halaman Tidak Ditemukan | SILPM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { font-family: 'Inter', system-ui, -apple-system, sans-serif; }</style>
</head>
<body class="h-full font-sans antialiased">

<div class="min-h-full flex flex-col items-center justify-center px-4 py-12">
    <div class="max-w-md w-full text-center">
        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>

        <p class="text-sm font-semibold text-gray-500 mb-2">Error 404</p>
        <h1 class="text-2xl font-bold text-gray-900 mb-3">Halaman Tidak Ditemukan</h1>
        <p class="text-gray-500 text-sm leading-relaxed mb-8">
            Halaman yang Anda cari tidak ditemukan atau mungkin telah dipindahkan.
            Periksa kembali alamat URL atau kembali ke dashboard.
        </p>

        <a href="{{ auth()->check() ? (auth()->user()->isAdmin() ? route('admin.dashboard') : route('mahasiswa.dashboard')) : route('login') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-700 hover:bg-blue-800 text-white font-semibold text-sm rounded-lg shadow transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            Kembali ke Dashboard
        </a>
    </div>
</div>

</body>
</html>
