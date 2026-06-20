@extends('layouts.guest')
@section('title', 'Daftar Akun — SILPM')
@section('content')

    <div class="mb-8">
        <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-2">Buat Akun Baru</h2>
        <p class="text-gray-500 text-sm font-medium">Lengkapi form berikut untuk mendaftar sebagai mahasiswa.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf

        <!-- Name -->
        <div>
            <label for="name" class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1.5">Nama Lengkap</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus autocomplete="name"
                       class="w-full pl-10 pr-4 py-3 bg-gray-50 border rounded-xl text-sm font-semibold text-gray-800 focus:ring-4 focus:ring-blue-500/20 focus:border-polmed-blue focus:bg-white outline-none transition-all {{ $errors->has('name') ? 'border-red-400' : 'border-gray-200' }}"
                       placeholder="Sesuai kartu mahasiswa" />
            </div>
            @error('name')
                <p class="mt-1 text-xs font-bold text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid grid-cols-2 gap-4">
            <!-- NIM -->
            <div>
                <label for="nim" class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1.5">NIM</label>
                <input id="nim" type="text" name="nim" value="{{ old('nim') }}" required
                       class="w-full px-4 py-3 bg-gray-50 border rounded-xl text-sm font-semibold text-gray-800 focus:ring-4 focus:ring-blue-500/20 focus:border-polmed-blue focus:bg-white outline-none transition-all {{ $errors->has('nim') ? 'border-red-400' : 'border-gray-200' }}"
                       placeholder="Contoh: 2305001" />
                @error('nim')
                    <p class="mt-1 text-xs font-bold text-red-500">{{ $message }}</p>
                @enderror
            </div>
            <!-- Class -->
            <div>
                <label for="class" class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1.5">Kelas</label>
                <input id="class" type="text" name="class" value="{{ old('class') }}" required
                       class="w-full px-4 py-3 bg-gray-50 border rounded-xl text-sm font-semibold text-gray-800 focus:ring-4 focus:ring-blue-500/20 focus:border-polmed-blue focus:bg-white outline-none transition-all {{ $errors->has('class') ? 'border-red-400' : 'border-gray-200' }}"
                       placeholder="Contoh: MI-4A" />
                @error('class')
                    <p class="mt-1 text-xs font-bold text-red-500">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1.5">Email</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="username"
                       class="w-full pl-10 pr-4 py-3 bg-gray-50 border rounded-xl text-sm font-semibold text-gray-800 focus:ring-4 focus:ring-blue-500/20 focus:border-polmed-blue focus:bg-white outline-none transition-all {{ $errors->has('email') ? 'border-red-400' : 'border-gray-200' }}"
                       placeholder="nama@example.com" />
            </div>
            @error('email')
                <p class="mt-1 text-xs font-bold text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Password -->
        <div x-data="{ show: false }">
            <label for="password" class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1.5">Password</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                </div>
                <input id="password" :type="show ? 'text' : 'password'" name="password" required autocomplete="new-password"
                       class="w-full pl-10 pr-10 py-3 bg-gray-50 border rounded-xl text-sm font-semibold text-gray-800 focus:ring-4 focus:ring-blue-500/20 focus:border-polmed-blue focus:bg-white outline-none transition-all {{ $errors->has('password') ? 'border-red-400' : 'border-gray-200' }}"
                       placeholder="Minimal 8 karakter" />
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-polmed-blue focus:outline-none">
                    <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                </button>
            </div>
            @error('password')
                <p class="mt-1 text-xs font-bold text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <!-- Confirm Password -->
        <div x-data="{ show: false }">
            <label for="password_confirmation" class="block text-xs font-bold text-gray-700 uppercase tracking-wide mb-1.5">Konfirmasi Password</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <input id="password_confirmation" :type="show ? 'text' : 'password'" name="password_confirmation" required autocomplete="new-password"
                       class="w-full pl-10 pr-10 py-3 bg-gray-50 border rounded-xl text-sm font-semibold text-gray-800 focus:ring-4 focus:ring-blue-500/20 focus:border-polmed-blue focus:bg-white outline-none transition-all {{ $errors->has('password_confirmation') ? 'border-red-400' : 'border-gray-200' }}"
                       placeholder="Ulangi password" />
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-polmed-blue focus:outline-none">
                    <svg x-show="!show" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                    <svg x-show="show" x-cloak class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                </button>
            </div>
            @error('password_confirmation')
                <p class="mt-1 text-xs font-bold text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="w-full py-3.5 bg-polmed-blue hover:bg-blue-800 text-white font-bold rounded-xl text-sm shadow-lg shadow-blue-900/20 transition-all focus:ring-4 focus:ring-blue-500/30 mt-6">
            Daftar Akun
        </button>
    </form>

    <div class="mt-6 text-center">
        <p class="text-sm text-gray-500 font-medium">
            Sudah memiliki akun?
            <a href="{{ route('login') }}" class="font-bold text-polmed-blue hover:text-blue-800 transition-colors">Masuk di sini</a>
        </p>
    </div>

@endsection
