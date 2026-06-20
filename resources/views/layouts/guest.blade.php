<!DOCTYPE html>
<html lang="id" class="h-full bg-gray-50">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Autentikasi SILPM')</title>

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
</head>
<body class="h-full font-sans antialiased text-gray-800 bg-white">

<div class="min-h-full flex flex-col lg:flex-row">
    
    <!-- Panel Kiri (Branding Polmed) -->
    <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-polmed-blue to-blue-900 flex-col justify-center items-center p-12 relative overflow-hidden shadow-2xl z-10">
        <!-- Decorative Elements -->
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 100 100" fill="none">
                <circle cx="50" cy="50" r="40" stroke="white" stroke-width="0.5"/>
                <circle cx="50" cy="50" r="30" stroke="white" stroke-width="0.5"/>
                <circle cx="50" cy="50" r="20" stroke="white" stroke-width="0.5"/>
            </svg>
        </div>
        <div class="absolute top-0 left-0 -mt-20 -ml-20 w-64 h-64 bg-polmed-yellow opacity-10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 right-0 -mb-20 -mr-20 w-80 h-80 bg-blue-400 opacity-20 rounded-full blur-3xl"></div>

        <div class="relative text-center text-white z-10 flex flex-col items-center">
            <div class="w-24 h-24 bg-white/10 backdrop-blur-md rounded-3xl flex items-center justify-center mb-10 shadow-2xl border border-white/20 transform transition hover:scale-105 duration-300">
                <svg class="w-12 h-12 text-polmed-yellow" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3z"/>
                    <path d="M3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                </svg>
            </div>
            <h1 class="text-4xl lg:text-5xl font-extrabold mb-4 tracking-tight drop-shadow-lg">SILPM</h1>
            <p class="text-xl lg:text-2xl text-blue-100 font-medium mb-6 leading-snug drop-shadow-md">
                Sistem Informasi Layanan<br>Pengaduan Mahasiswa
            </p>
            <div class="w-16 h-1.5 bg-gradient-to-r from-polmed-yellow to-yellow-300 rounded-full my-6 shadow-sm"></div>
            <p class="text-blue-200 text-sm font-bold tracking-widest uppercase">
                Politeknik Negeri Medan
            </p>
        </div>
    </div>

    <!-- Panel Kanan (Konten Dinamis) -->
    <div class="flex-1 flex flex-col justify-center items-center py-12 px-6 sm:px-10 lg:px-20 bg-gray-50/50 relative">
        <div class="w-full max-w-md bg-white p-8 sm:p-10 rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 relative z-20">
            <!-- Mobile Logo -->
            <div class="lg:hidden text-center mb-8">
                <div class="w-16 h-16 bg-gradient-to-br from-polmed-blue to-blue-800 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-lg shadow-blue-900/20">
                    <svg class="w-8 h-8 text-polmed-yellow" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">SILPM</h1>
                <p class="text-polmed-blue text-xs font-bold mt-1 uppercase tracking-wider">Politeknik Negeri Medan</p>
            </div>

            @yield('content')
            {{ $slot ?? '' }}

        </div>
    </div>
</div>

</body>
</html>
