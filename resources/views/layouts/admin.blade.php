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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        polmed: {
                            blue: '#1E3A8A',
                            light: '#EFF6FF',
                            yellow: '#F59E0B',
                            green: '#10B981',
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
         class="fixed inset-0 bg-gray-900/70 backdrop-blur-sm z-40 lg:hidden"
         @click="sidebarOpen = false"></div>

    <!-- ===== Sidebar Admin ===== -->
    <aside class="fixed inset-y-0 left-0 z-50 w-64 bg-[#2b4cba] text-white flex flex-col flex-shrink-0 transition-transform duration-300 ease-in-out lg:static lg:h-screen lg:translate-x-0"
           :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

        <!-- Sidebar Header: Logo Polmed -->
        <div class="h-16 flex items-center justify-between px-4 border-b border-white/15 flex-shrink-0">
            <div class="flex items-center gap-3">
                <img src="https://polmed.ac.id/wp-content/uploads/2014/04/logo-polmed-png.png"
                     alt="Logo Polmed"
                     class="h-9 w-auto object-contain drop-shadow-md flex-shrink-0">
                <div class="text-left border-l border-white/30 pl-3">
                    <span class="block text-white font-bold text-sm leading-none">SILPM</span>
                    <span class="block text-white/60 text-xs font-medium mt-0.5">Panel Admin</span>
                </div>
            </div>
            <!-- Tombol Close Sidebar (Hanya Mobile) -->
            <button @click="sidebarOpen = false" class="lg:hidden text-white/70 hover:text-white p-1.5 rounded-lg hover:bg-white/10 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <!-- Menu Navigasi Sidebar -->
        <div class="flex-1 overflow-y-auto px-3 py-5">
            <nav class="space-y-1">
                <p class="px-3 text-[10px] font-bold text-white/40 uppercase tracking-widest mb-2">Menu Utama</p>

                <a href="{{ route('admin.dashboard') }}"
                   class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200
                          {{ request()->routeIs('admin.dashboard')
                             ? 'bg-white/20 text-white shadow-inner border border-white/25'
                             : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-4.5 h-4.5 w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    Dashboard
                </a>

                <a href="{{ route('admin.pengaduan.index') }}"
                   class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200
                          {{ request()->routeIs('admin.pengaduan.*')
                             ? 'bg-white/20 text-white shadow-inner border border-white/25'
                             : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Manajemen Pengaduan
                </a>

                <a href="{{ route('admin.statistik') }}"
                   class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200
                          {{ request()->routeIs('admin.statistik')
                             ? 'bg-white/20 text-white shadow-inner border border-white/25'
                             : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 012-2h0a2 2 0 012 2v6m-4 0h4m-4 0H5a1 1 0 01-1-1V9a1 1 0 011-1h0a1 1 0 011 1v9m4 0V5a1 1 0 011-1h0a1 1 0 011 1v14m4 0v-9a1 1 0 011-1h0a1 1 0 011 1v9m-4 0h4"/>
                    </svg>
                    Statistik Laporan
                </a>

                <div class="pt-4 pb-1">
                    <p class="px-3 text-[10px] font-bold text-white/40 uppercase tracking-widest mb-2">Pengaturan</p>
                </div>

                <a href="{{ route('admin.users.index') }}"
                   class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200
                          {{ request()->routeIs('admin.users.*')
                             ? 'bg-white/20 text-white shadow-inner border border-white/25'
                             : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    Kelola Pengguna
                </a>

                <a href="{{ route('admin.kategori.index') }}"
                   class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200
                          {{ request()->routeIs('admin.kategori.*')
                             ? 'bg-white/20 text-white shadow-inner border border-white/25'
                             : 'text-white/70 hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                    </svg>
                    Kelola Kategori
                </a>
            </nav>
        </div>

        <!-- Info Admin & Logout -->
        <div class="flex-shrink-0 p-3 border-t border-white/15">
            <div class="flex items-center gap-3 px-2 py-2 mb-2">
                <!-- Ikon profil SVG (sama dengan mahasiswa) -->
                <div class="w-9 h-9 rounded-full bg-white/15 border border-white/25 flex items-center justify-center flex-shrink-0 ring-2 ring-white/10">
                    <svg class="w-5 h-5 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-white/50 truncate">Administrator MI</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 px-3 py-2.5 bg-white/10 hover:bg-red-500/80 text-white/80 hover:text-white rounded-xl text-sm font-semibold transition-all duration-200 border border-white/10 hover:border-red-400/50">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Keluar Sistem
                </button>
            </form>
        </div>
    </aside>

    <!-- ===== Konten Utama Admin ===== -->
    <div class="flex-1 flex flex-col min-w-0 overflow-hidden">

        <!-- Header (Mobile & Desktop) — sama gaya dengan mahasiswa -->
        <header class="bg-white border-b border-gray-200 shadow-sm flex-shrink-0 sticky top-0 z-30">
            <div class="h-16 flex items-center justify-between px-4 lg:px-8">
                <div class="flex items-center gap-3">
                    <!-- Hamburger Button (Mobile) -->
                    <button @click="sidebarOpen = true" class="lg:hidden text-gray-500 hover:text-[#2b4cba] p-2 rounded-lg hover:bg-blue-50 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <h1 class="text-xl font-bold text-gray-900 tracking-tight">@yield('title', 'Dashboard Admin')</h1>
                </div>

                <div class="flex items-center gap-3">
                    <div class="text-sm font-semibold text-gray-500 bg-gray-100 px-3 py-1.5 rounded-lg hidden sm:block">
                        {{ \Carbon\Carbon::now('Asia/Jakarta')->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                    </div>
                </div>
            </div>
        </header>

        <!-- Flash Messages & Content -->
        <main class="flex-1 overflow-y-auto">
            <div class="px-4 lg:px-8 py-6">

                @if (session('success'))
                    <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.500ms
                         class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex items-start gap-4 shadow-sm mb-6 relative">
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
                         class="bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-4 shadow-sm mb-6 relative">
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

                @yield('content')
            </div>
        </main>

    </div>
</div>

@stack('scripts')
</body>
</html>
