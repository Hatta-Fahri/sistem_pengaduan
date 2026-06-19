<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>403 — Akses Ditolak | SILPM</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>body { font-family: 'Inter', system-ui, -apple-system, sans-serif; }</style>
</head>
<body class="h-full font-sans antialiased">

<div class="min-h-full flex flex-col items-center justify-center px-4 py-12">
    <div class="max-w-md w-full text-center">
        <div class="w-20 h-20 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-12V7a4 4 0 10-8 0v2"/>
            </svg>
        </div>

        <p class="text-sm font-semibold text-red-600 mb-2">Error 403</p>
        <h1 class="text-2xl font-bold text-gray-900 mb-3">Akses Ditolak</h1>
        <p class="text-gray-500 text-sm leading-relaxed mb-8">
            Anda tidak memiliki izin untuk mengakses halaman ini. Jika menurut Anda ini adalah
            kesalahan, silakan hubungi administrator SILPM.
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
