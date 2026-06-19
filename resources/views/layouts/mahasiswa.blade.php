<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="SILPM — Sistem Informasi Layanan Pengaduan Mahasiswa, Program Studi Manajemen Informatika, Politeknik Negeri Medan" />
    <title>@yield('title', 'Dashboard') — SILPM Mahasiswa</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
        .line-clamp-1 { overflow: hidden; display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; }
        .line-clamp-2 { overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; }
    </style>
</head>
<body class="h-full font-sans antialiased">

<div class="min-h-full">
    <!-- Navigasi Atas -->
    <nav class="bg-blue-700 shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <!-- Logo -->
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-white rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-700" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3z"/>
                            <path d="M3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                        </svg>
                    </div>
                    <a href="{{ route('mahasiswa.dashboard') }}" class="text-white font-bold text-lg tracking-wide">SILPM</a>
                    <span class="text-blue-300 text-sm hidden sm:block">Portal Mahasiswa</span>
                </div>

                <!-- Menu Navigasi -->
                <div class="hidden md:flex items-center gap-1">
                    <a href="{{ route('mahasiswa.dashboard') }}"
                       class="px-3 py-2 rounded-md text-sm font-medium text-blue-100 hover:bg-blue-600 hover:text-white transition {{ request()->routeIs('mahasiswa.dashboard') ? 'bg-blue-800 text-white' : '' }}">
                        Dashboard
                    </a>
                    <a href="{{ route('mahasiswa.pengaduan.index') }}"
                       class="px-3 py-2 rounded-md text-sm font-medium text-blue-100 hover:bg-blue-600 hover:text-white transition {{ request()->routeIs('mahasiswa.pengaduan.*') ? 'bg-blue-800 text-white' : '' }}">
                        Pengaduan Saya
                    </a>
                    <a href="{{ route('mahasiswa.pengaduan.create') }}"
                       class="px-3 py-2 rounded-md text-sm font-medium bg-white text-blue-700 hover:bg-blue-50 transition rounded-md">
                        + Buat Pengaduan
                    </a>
                </div>

                <!-- Info User & Logout -->
                <div class="flex items-center gap-3">
                    <div class="hidden sm:block text-right">
                        <p class="text-white text-sm font-medium">{{ auth()->user()->name }}</p>
                        <p class="text-blue-300 text-xs">NIM: {{ auth()->user()->nim }} · {{ auth()->user()->class }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                                class="flex items-center gap-1 px-3 py-1.5 rounded-md text-sm text-blue-100 hover:bg-blue-600 hover:text-white transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if (session('success'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-green-50 border border-green-200 rounded-lg p-4 flex items-center gap-3">
                <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                <p class="text-green-800 text-sm font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif
    @if (session('error'))
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-red-50 border border-red-200 rounded-lg p-4 flex items-center gap-3">
                <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                <p class="text-red-800 text-sm font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Konten Utama -->
    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-auto border-t border-gray-200 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 text-center text-gray-400 text-xs">
            &copy; {{ date('Y') }} SILPM — Program Studi Manajemen Informatika, Politeknik Negeri Medan
        </div>
    </footer>
</div>

</body>
</html>
