<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="SILPM Mahasiswa — Sistem Informasi Layanan Pengaduan Mahasiswa" />
    <title>@yield('title', 'Dashboard Mahasiswa') — SILPM</title>

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
<body class="h-full font-sans antialiased text-gray-800" x-data="{ mobileMenuOpen: false }">

<div class="min-h-full flex flex-col">
    <!-- Navbar (Atas) -->
    <nav class="bg-gradient-to-r from-polmed-blue to-blue-900 border-b border-blue-800 sticky top-0 z-50 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center gap-4">
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center gap-3">
                        <div class="w-10 h-10 bg-polmed-yellow rounded-xl flex items-center justify-center shadow-sm">
                            <svg class="w-6 h-6 text-polmed-blue" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3z"/>
                                <path d="M3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                            </svg>
                        </div>
                        <div>
                            <span class="text-white font-extrabold text-lg tracking-tight">SILPM</span>
                            <span class="hidden sm:inline-block text-blue-200 text-xs ml-2 font-medium tracking-wide uppercase">Politeknik Negeri Medan</span>
                        </div>
                    </div>

                    <!-- Menu Desktop -->
                    <div class="hidden sm:ml-8 sm:flex sm:space-x-2">
                        <a href="{{ route('mahasiswa.dashboard') }}"
                           class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-bold transition-colors
                                  {{ request()->routeIs('mahasiswa.dashboard') ? 'bg-white/10 text-white' : 'text-blue-200 hover:bg-white/5 hover:text-white' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('mahasiswa.pengaduan.index') }}"
                           class="inline-flex items-center px-3 py-2 rounded-lg text-sm font-bold transition-colors
                                  {{ request()->routeIs('mahasiswa.pengaduan.index') || request()->routeIs('mahasiswa.pengaduan.show') ? 'bg-white/10 text-white' : 'text-blue-200 hover:bg-white/5 hover:text-white' }}">
                            Riwayat Pengaduan
                        </a>
                        <a href="{{ route('mahasiswa.pengaduan.create') }}"
                           class="inline-flex items-center px-4 py-2 bg-polmed-yellow hover:bg-yellow-400 text-polmed-blue rounded-lg text-sm font-bold shadow-sm transition-colors ml-2">
                            + Buat Pengaduan
                        </a>
                    </div>
                </div>

                <!-- User Dropdown Desktop -->
                <div class="hidden sm:ml-6 sm:flex sm:items-center">
                    <div class="relative" x-data="{ userMenuOpen: false }">
                        <button @click="userMenuOpen = !userMenuOpen" @click.away="userMenuOpen = false" class="flex items-center gap-2 focus:outline-none">
                            <div class="text-right hidden md:block">
                                <p class="text-sm font-bold text-white">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-blue-300">{{ auth()->user()->nim }}</p>
                            </div>
                            <div class="w-9 h-9 rounded-full bg-blue-800 text-white flex items-center justify-center font-bold text-sm ring-2 ring-white/20">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <svg class="w-4 h-4 text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                        
                        <div x-cloak x-show="userMenuOpen"
                             x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100"
                             x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95"
                             class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg ring-1 ring-black ring-opacity-5 py-1 z-50 border border-gray-100">
                            <!-- Link Profil -->
                            <a href="{{ route('mahasiswa.profil.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 font-medium">Profil Saya</a>
                            
                            <!-- Form Logout -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 font-bold transition-colors">
                                    Keluar
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Tombol Mobile Menu -->
                <div class="flex items-center sm:hidden">
                    <button @click="mobileMenuOpen = !mobileMenuOpen" class="text-blue-200 hover:text-white focus:outline-none p-2 rounded-lg hover:bg-white/10 transition-colors">
                        <svg class="w-6 h-6" x-show="!mobileMenuOpen" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        <svg class="w-6 h-6" x-show="mobileMenuOpen" x-cloak fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Menu Mobile (Slide down) -->
        <div x-cloak x-show="mobileMenuOpen"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             class="sm:hidden bg-polmed-blue border-t border-blue-800 absolute w-full shadow-xl">
            <div class="px-4 pt-2 pb-3 space-y-1">
                <a href="{{ route('mahasiswa.dashboard') }}"
                   class="block px-3 py-2 rounded-lg text-base font-bold transition-colors {{ request()->routeIs('mahasiswa.dashboard') ? 'bg-white/10 text-white' : 'text-blue-200 hover:bg-white/5 hover:text-white' }}">
                    Dashboard
                </a>
                <a href="{{ route('mahasiswa.pengaduan.index') }}"
                   class="block px-3 py-2 rounded-lg text-base font-bold transition-colors {{ request()->routeIs('mahasiswa.pengaduan.*') && !request()->routeIs('mahasiswa.pengaduan.create') ? 'bg-white/10 text-white' : 'text-blue-200 hover:bg-white/5 hover:text-white' }}">
                    Riwayat Pengaduan
                </a>
                <a href="{{ route('mahasiswa.pengaduan.create') }}"
                   class="block px-3 py-2 rounded-lg text-base font-bold transition-colors {{ request()->routeIs('mahasiswa.pengaduan.create') ? 'bg-polmed-yellow text-polmed-blue' : 'text-polmed-yellow hover:bg-white/5' }}">
                    + Buat Pengaduan Baru
                </a>
            </div>
            <div class="pt-4 pb-3 border-t border-blue-800">
                <div class="px-5 flex items-center gap-3">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-800 text-white flex items-center justify-center font-bold ring-2 ring-white/20">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="text-base font-bold text-white">{{ auth()->user()->name }}</div>
                        <div class="text-sm font-medium text-blue-300">{{ auth()->user()->nim }}</div>
                    </div>
                </div>
                <div class="mt-3 px-2 space-y-1">
                    <a href="{{ route('mahasiswa.profil.edit') }}" class="block px-3 py-2 rounded-lg text-base font-medium text-blue-200 hover:text-white hover:bg-white/5">Profil Saya</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-3 py-2 rounded-lg text-base font-bold text-red-400 hover:text-white hover:bg-red-500/20">Keluar</button>
                    </form>
                </div>
            </div>
        </div>
    </nav>

    <!-- Header Section (Page Title) -->
    <header class="bg-white border-b border-gray-200 shadow-sm relative z-40">
        <div class="max-w-7xl mx-auto py-5 px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">@yield('title', 'Dashboard')</h1>
            <div class="text-sm font-semibold text-gray-500 bg-gray-100 px-3 py-1.5 rounded-lg w-fit">
                {{ now()->isoFormat('dddd, D MMMM YYYY') }}
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1">
        <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
            <!-- Flash Messages -->
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

    <footer class="bg-white border-t border-gray-200 mt-auto">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <p class="text-gray-400 text-sm font-medium text-center md:text-left">
                &copy; {{ date('Y') }} SILPM — Politeknik Negeri Medan.
            </p>
            <div class="flex items-center gap-4 text-sm text-gray-400 font-medium">
                <a href="#" class="hover:text-polmed-blue transition-colors">Bantuan</a>
                <a href="#" class="hover:text-polmed-blue transition-colors">Panduan Penggunaan</a>
            </div>
        </div>
    </footer>
</div>

</body>
</html>
