<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Sistem Informasi Layanan Pengaduan Mahasiswa" />
    <title>@yield('title', 'Dashboard Mahasiswa')</title>

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
    <nav class="bg-[#2b4cba] border-b border-[#2441a1]/60 sticky top-0 z-50 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative flex items-center justify-between h-16">
                <div class="flex items-center gap-4">
                    <!-- Logo -->
                    <div class="flex-shrink-0 flex items-center gap-3">
                        <img src="https://polmed.ac.id/wp-content/uploads/2014/04/logo-polmed-png.png"
                             alt="Logo Polmed"
                             class="h-10 w-auto object-contain drop-shadow-md flex-shrink-0">
                        <div class="text-left border-l border-white/30 pl-3 whitespace-nowrap">
                            <span class="block text-white font-bold text-sm leading-none">Layanan Pengaduan Mahasiswa</span>
                            <span class="block text-white/70 text-xs font-medium mt-1">Politeknik Negeri Medan</span>
                        </div>
                    </div>

                </div>

                    <!-- Menu Desktop — ditengahkan secara absolut -->
                    <div class="hidden sm:flex items-center gap-2 absolute left-1/2 -translate-x-1/2">
                        <a href="{{ route('mahasiswa.dashboard') }}"
                           class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-bold backdrop-blur-sm border transition-all duration-200
                                  {{ request()->routeIs('mahasiswa.dashboard')
                                     ? 'bg-white/20 border-white/40 text-white shadow-inner'
                                     : 'bg-white/10 border-white/20 text-white/80 hover:bg-white/20 hover:border-white/40 hover:text-white' }}">
                            Dashboard
                        </a>
                        <a href="{{ route('mahasiswa.pengaduan.index') }}"
                           class="inline-flex items-center px-4 py-2 rounded-xl text-sm font-bold backdrop-blur-sm border transition-all duration-200
                                  {{ request()->routeIs('mahasiswa.pengaduan.index') || request()->routeIs('mahasiswa.pengaduan.show')
                                     ? 'bg-white/20 border-white/40 text-white shadow-inner'
                                     : 'bg-white/10 border-white/20 text-white/80 hover:bg-white/20 hover:border-white/40 hover:text-white' }}">
                            Riwayat Pengaduan
                        </a>
                        <a href="{{ route('mahasiswa.pengaduan.create') }}"
                           class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-bold backdrop-blur-sm border border-white/20 bg-white/10 text-white/80 hover:bg-white/20 hover:border-white/40 hover:text-white transition-all duration-200
                                  {{ request()->routeIs('mahasiswa.pengaduan.create') ? 'bg-white/20 border-white/40 text-white' : '' }}">
                            <span class="text-base leading-none"></span>Buat Pengaduan
                        </a>
                    </div>

                <!-- User Dropdown Desktop -->
                <div class="hidden sm:ml-6 sm:flex sm:items-center">
                    <div class="relative" x-data="{ userMenuOpen: false }">
                        <!-- Trigger Button -->
                        <button @click="userMenuOpen = !userMenuOpen"
                                @click.away="userMenuOpen = false"
                                class="flex items-center gap-2.5 px-3 py-1.5 rounded-xl hover:bg-white/10 focus:outline-none transition-all duration-200 group">
                            <div class="text-right hidden md:block">
                                <p class="text-sm font-semibold text-white leading-tight">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-white/50 mt-0.5">{{ auth()->user()->nim ?? 'Admin' }}</p>
                            </div>
                            <!-- Ikon Profil SVG -->
                            <div class="w-9 h-9 rounded-full bg-white/15 border border-white/25 flex items-center justify-center flex-shrink-0 ring-2 ring-white/10 group-hover:bg-white/25 transition-all duration-200">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                            </div>
                            <svg class="w-3.5 h-3.5 text-white/40 transition-transform duration-200" :class="userMenuOpen ? 'rotate-180' : ''"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <!-- Dropdown Liquid Glass -->
                        <div x-cloak x-show="userMenuOpen"
                             x-transition:enter="transition ease-out duration-150"
                             x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                             x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-100"
                             x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="opacity-0 scale-95 -translate-y-1"
                             class="absolute right-0 mt-2.5 w-60 bg-[#1e3a8a]/95 backdrop-blur-xl border border-white/15 rounded-2xl shadow-2xl overflow-hidden z-50">

                            <!-- Info User -->
                            <div class="px-4 py-3.5 border-b border-white/10">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full bg-white/15 border border-white/20 flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                                        <p class="text-xs text-white/50 truncate mt-0.5">{{ auth()->user()->email }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Menu Items -->
                            <div class="py-1.5 px-1.5">
                                <a href="{{ route('mahasiswa.profil.edit') }}"
                                   class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm text-white/80 hover:text-white hover:bg-white/10 font-medium transition-all duration-150">
                                    <svg class="w-4 h-4 text-white/50 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Profil Saya
                                </a>
                            </div>

                            <!-- Divider -->
                            <div class="border-t border-white/10 mx-3"></div>

                            <!-- Logout -->
                            <div class="py-1.5 px-1.5">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                            class="w-full flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-sm text-red-300 hover:text-red-200 hover:bg-red-500/15 font-medium transition-all duration-150">
                                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        Keluar
                                    </button>
                                </form>
                            </div>
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
             class="sm:hidden bg-[#2b4cba] border-t border-[#2441a1]/60 absolute w-full shadow-xl">
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
            <div class="pt-4 pb-3 border-t border-white/10">
                <div class="px-5 flex items-center gap-3">
                    <div class="flex-shrink-0 w-10 h-10 rounded-full bg-white/15 border border-white/20 text-white flex items-center justify-center ring-2 ring-white/10">
                        <svg class="w-5 h-5 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <div class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</div>
                        <div class="text-xs text-white/50 truncate">{{ auth()->user()->email }}</div>
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
                {{ \Carbon\Carbon::now('Asia/Jakarta')->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
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
        </div>
    </footer>
</div>
</body>
</html>
