<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="SILPM Admin Panel — Sistem Informasi Layanan Pengaduan Mahasiswa" />
    <title>@yield('title', 'Dashboard') — SILPM Admin</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        polmed: {
                            blue: '#1E3A8A',    // Dark Blue
                            light: '#EFF6FF',   // Very light blue for backgrounds
                            yellow: '#F59E0B',  // Amber/Yellow for accents
                            green: '#10B981',   // Emerald
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'system-ui', 'sans-serif'],
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; }
        .line-clamp-1 { overflow: hidden; display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; }
        .line-clamp-2 { overflow: hidden; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; }
        /* Animasi Transisi Halus */
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="h-full font-sans antialiased text-gray-800" x-data="{ sidebarOpen: false }">

<div class="h-screen flex overflow-hidden">

    <!-- Sidebar Overlay Mobile -->
    <div x-cloak x-show="sidebarOpen" 
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm z-40 lg:hidden" 
         @click="sidebarOpen = false"></div>

    <!-- Sidebar Admin -->
    <aside class="fixed inset-y-0 left-0 z-50 w-72 bg-polmed-blue text-white flex flex-col flex-shrink-0 transition-transform duration-300 ease-in-out lg:static lg:h-screen lg:translate-x-0"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        
        <!-- Sidebar Header (Logo) -->
        <div class="h-20 flex items-center justify-between px-6 border-b border-blue-800/50 bg-polmed-blue/95 backdrop-blur">
            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-polmed-yellow rounded-xl flex items-center justify-center shadow-lg shadow-polmed-yellow/20">
                    <svg class="w-6 h-6 text-polmed-blue" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3z"/>
                        <path d="M3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                    </svg>
                </div>
                <div>
                    <h1 class="font-bold text-lg tracking-wide text-white">SILPM</h1>
                    <p class="text-blue-200 text-xs tracking-wider uppercase">Polmed Admin</p>
                </div>
            </div>
            <!-- Tombol Close Sidebar (Hanya Mobile) -->
            <button @click="sidebarOpen = false" class="lg:hidden text-blue-200 hover:text-white p-2 rounded-lg hover:bg-blue-800 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <!-- Menu Navigasi Sidebar -->
        <div class="flex-1 overflow-y-auto px-4 py-6">
            <nav class="space-y-2">
                <p class="px-4 text-xs font-semibold text-blue-300 uppercase tracking-widest mb-3">Menu Utama</p>
                <a href="{{ route('admin.dashboard') }}"
                   class="group flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200
                          {{ request()->routeIs('admin.dashboard') ? 'bg-polmed-yellow text-polmed-blue shadow-md' : 'text-blue-100 hover:bg-blue-800/50 hover:text-white' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.dashboard') ? 'text-polmed-blue' : 'text-blue-300 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard
                </a>
                
                <a href="{{ route('admin.pengaduan.index') }}"
                   class="group flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200
                          {{ request()->routeIs('admin.pengaduan.*') ? 'bg-polmed-yellow text-polmed-blue shadow-md' : 'text-blue-100 hover:bg-blue-800/50 hover:text-white' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.pengaduan.*') ? 'text-polmed-blue' : 'text-blue-300 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Manajemen Pengaduan
                </a>

                <a href="{{ route('admin.statistik') }}"
                   class="group flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200
                          {{ request()->routeIs('admin.statistik') ? 'bg-polmed-yellow text-polmed-blue shadow-md' : 'text-blue-100 hover:bg-blue-800/50 hover:text-white' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.statistik') ? 'text-polmed-blue' : 'text-blue-300 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 012-2h0a2 2 0 012 2v6m-4 0h4m-4 0H5a1 1 0 01-1-1V9a1 1 0 011-1h0a1 1 0 011 1v9m4 0V5a1 1 0 011-1h0a1 1 0 011 1v14m4 0v-9a1 1 0 011-1h0a1 1 0 011 1v9m-4 0h4"/>
                    </svg>
                    Statistik Laporan
                </a>
                
                <p class="px-4 text-xs font-semibold text-blue-300 uppercase tracking-widest mt-6 mb-3">Pengaturan</p>

                <a href="{{ route('admin.users.index') }}"
                   class="group flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200
                          {{ request()->routeIs('admin.users.*') ? 'bg-polmed-yellow text-polmed-blue shadow-md' : 'text-blue-100 hover:bg-blue-800/50 hover:text-white' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.users.*') ? 'text-polmed-blue' : 'text-blue-300 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    Kelola Pengguna
                </a>

                <a href="{{ route('admin.kategori.index') }}"
                   class="group flex items-center gap-3 px-4 py-3 rounded-xl text-sm font-medium transition-all duration-200
                          {{ request()->routeIs('admin.kategori.*') ? 'bg-polmed-yellow text-polmed-blue shadow-md' : 'text-blue-100 hover:bg-blue-800/50 hover:text-white' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.kategori.*') ? 'text-polmed-blue' : 'text-blue-300 group-hover:text-white' }} transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    Kelola Kategori
                </a>
            </nav>
        </div>

        <!-- Info Admin & Logout -->
        <div class="p-4 border-t border-blue-800/50 bg-blue-900/30">
            <div class="flex items-center gap-3 mb-4 px-2">
                <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-polmed-yellow to-yellow-300 flex items-center justify-center text-polmed-blue font-bold shadow-md ring-2 ring-blue-800">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm text-white font-semibold truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-blue-300">Administrator</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-blue-800/50 hover:bg-red-500/90 text-blue-100 hover:text-white rounded-xl text-sm font-medium transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Keluar Sistem
                </button>
            </form>
        </div>
    </aside>

    <!-- Konten Utama Admin -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-gray-50/50">
        
        <!-- Header (Mobile & Desktop) -->
        <header class="h-20 bg-white/80 backdrop-blur-md border-b border-gray-200 flex items-center justify-between px-6 lg:px-10 sticky top-0 z-30 shadow-sm">
            <div class="flex items-center gap-4">
                <!-- Hamburger Button (Mobile) -->
                <button @click="sidebarOpen = true" class="lg:hidden text-gray-500 hover:text-polmed-blue hover:bg-polmed-light p-2 rounded-lg transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
                <h1 class="text-xl font-bold text-gray-800 tracking-tight">@yield('title', 'Dashboard')</h1>
            </div>
            
            <div class="hidden sm:flex items-center gap-3">
                <div class="text-right">
                    <p class="text-sm font-semibold text-gray-700">{{ now()->isoFormat('dddd, D MMMM YYYY') }}</p>
                    <p class="text-xs text-gray-500">Tahun Akademik Berjalan</p>
                </div>
                <div class="h-10 w-10 bg-polmed-light rounded-full flex items-center justify-center text-polmed-blue ring-1 ring-blue-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
            </div>
        </header>

        <!-- Flash Messages Area -->
        <div class="px-6 lg:px-10 pt-6">
            @if (session('success'))
                <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.500ms
                     class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex items-start gap-4 shadow-sm mb-2 relative">
                    <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm font-bold text-emerald-800">Berhasil!</h3>
                        <p class="text-emerald-700 text-sm mt-0.5">{{ session('success') }}</p>
                    </div>
                    <button @click="show = false" class="text-emerald-500 hover:bg-emerald-100 rounded-lg p-1.5 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>
            @endif
            
            @if (session('error'))
                <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.500ms
                     class="bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-4 shadow-sm mb-2 relative">
                    <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-sm font-bold text-red-800">Terdapat Kesalahan</h3>
                        <p class="text-red-700 text-sm mt-0.5">{{ session('error') }}</p>
                    </div>
                    <button @click="show = false" class="text-red-500 hover:bg-red-100 rounded-lg p-1.5 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                </div>
            @endif
        </div>

        <main class="flex-1 px-6 lg:px-10 py-6 overflow-y-auto">
            @yield('content')
        </main>

    </div>
</div>

@stack('scripts')
</body>
</html>
