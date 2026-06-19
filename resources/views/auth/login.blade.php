<!DOCTYPE html>
<html lang="id" class="h-full bg-blue-50">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Masuk — SILPM</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased">

<div class="min-h-full flex">
    <!-- Panel Kiri (Branding) -->
    <div class="hidden lg:flex lg:w-1/2 bg-blue-700 flex-col justify-center items-center p-12 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <svg class="w-full h-full" viewBox="0 0 100 100" fill="none">
                <circle cx="50" cy="50" r="40" stroke="white" stroke-width="0.5"/>
                <circle cx="50" cy="50" r="30" stroke="white" stroke-width="0.5"/>
                <circle cx="50" cy="50" r="20" stroke="white" stroke-width="0.5"/>
            </svg>
        </div>
        <div class="relative text-center text-white">
            <div class="w-20 h-20 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3z"/>
                    <path d="M3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                </svg>
            </div>
            <h1 class="text-4xl font-bold mb-3">SILPM</h1>
            <p class="text-xl text-blue-100 font-medium mb-2">Sistem Informasi Layanan<br>Pengaduan Mahasiswa</p>
            <p class="text-blue-200 text-sm mt-4">Program Studi Manajemen Informatika<br>Politeknik Negeri Medan</p>
        </div>
    </div>

    <!-- Panel Kanan (Form Login) -->
    <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-20 xl:px-24">
        <div class="mx-auto w-full max-w-sm">
            <!-- Mobile Logo -->
            <div class="lg:hidden text-center mb-8">
                <div class="w-14 h-14 bg-blue-700 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3z"/>
                    </svg>
                </div>
                <h1 class="text-2xl font-bold text-gray-900">SILPM</h1>
                <p class="text-gray-500 text-sm">Politeknik Negeri Medan</p>
            </div>

            <h2 class="text-2xl font-bold text-gray-900 mb-1">Selamat Datang</h2>
            <p class="text-gray-500 text-sm mb-8">Masuk ke akun Anda untuk melanjutkan.</p>

            <!-- Session Status -->
            @if (session('status'))
                <div class="mb-4 p-3 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm">
                    {{ session('status') }}
                </div>
            @endif
            @if (session('error'))
                <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            <form id="login-form" method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
                        Alamat Email
                    </label>
                    <input id="email" name="email" type="email" autocomplete="email" required
                           value="{{ old('email') }}"
                           class="w-full px-4 py-2.5 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition
                                  {{ $errors->has('email') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                           placeholder="nama@example.com" />
                    @error('email')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs text-blue-600 hover:text-blue-800">Lupa password?</a>
                        @endif
                    </div>
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                           class="w-full px-4 py-2.5 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition
                                  {{ $errors->has('password') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                           placeholder="Masukkan password" />
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input id="remember_me" name="remember" type="checkbox"
                           class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500">
                    <label for="remember_me" class="ml-2 text-sm text-gray-600">Ingat saya</label>
                </div>

                <!-- Tombol Login -->
                <button type="submit" id="btn-login"
                        class="w-full py-2.5 px-4 bg-blue-700 hover:bg-blue-800 text-white font-semibold rounded-lg text-sm transition focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    Masuk
                </button>
            </form>

            <!-- Link Daftar -->
            <p class="mt-6 text-center text-sm text-gray-500">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-medium text-blue-600 hover:text-blue-800">Daftar sebagai Mahasiswa</a>
            </p>
        </div>
    </div>
</div>

</body>
</html>
