<!DOCTYPE html>
<html lang="id" class="h-full bg-blue-50">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Daftar Akun — SILPM</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full font-sans antialiased">

<div class="min-h-full flex flex-col justify-center py-10 px-4 sm:px-6 lg:px-8">
    <div class="mx-auto w-full max-w-lg">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="w-14 h-14 bg-blue-700 rounded-full flex items-center justify-center mx-auto mb-3">
                <svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.838L7.667 9.088l1.94.831a1 1 0 00.787 0l7-3a1 1 0 000-1.838l-7-3z"/>
                </svg>
            </div>
            <h1 class="text-2xl font-bold text-gray-900">Daftar Akun Mahasiswa</h1>
            <p class="text-gray-500 text-sm mt-1">SILPM — Politeknik Negeri Medan</p>
        </div>

        <!-- Form Registrasi -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
            <form id="register-form" method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <!-- Nama Lengkap -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                    <input id="name" name="name" type="text" autocomplete="name" required
                           value="{{ old('name') }}"
                           class="w-full px-4 py-2.5 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition
                                  {{ $errors->has('name') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                           placeholder="Masukkan nama lengkap sesuai identitas" />
                    @error('name')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- NIM dan Kelas (2 kolom) -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="nim" class="block text-sm font-medium text-gray-700 mb-1">NIM</label>
                        <input id="nim" name="nim" type="text" required
                               value="{{ old('nim') }}"
                               class="w-full px-4 py-2.5 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition
                                      {{ $errors->has('nim') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                               placeholder="Contoh: 2305001" />
                        @error('nim')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="class" class="block text-sm font-medium text-gray-700 mb-1">Kelas</label>
                        <input id="class" name="class" type="text" required
                               value="{{ old('class') }}"
                               class="w-full px-4 py-2.5 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition
                                      {{ $errors->has('class') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                               placeholder="Contoh: MI-4A" />
                        @error('class')
                            <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
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
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <input id="password" name="password" type="password" autocomplete="new-password" required
                           class="w-full px-4 py-2.5 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition
                                  {{ $errors->has('password') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                           placeholder="Minimal 8 karakter" />
                    @error('password')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Konfirmasi Password -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
                        Konfirmasi Password
                    </label>
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
                           class="w-full px-4 py-2.5 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition border-gray-300"
                           placeholder="Ulangi password" />
                    @error('password_confirmation')
                        <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Tombol Daftar -->
                <button type="submit" id="btn-register"
                        class="w-full py-2.5 px-4 bg-blue-700 hover:bg-blue-800 text-white font-semibold rounded-lg text-sm transition focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 mt-2">
                    Buat Akun
                </button>
            </form>
        </div>

        <!-- Link Login -->
        <p class="mt-6 text-center text-sm text-gray-500">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="font-medium text-blue-600 hover:text-blue-800">Masuk di sini</a>
        </p>
    </div>
</div>

</body>
</html>
