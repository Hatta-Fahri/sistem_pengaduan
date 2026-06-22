@extends('layouts.guest')
@section('title', 'Login - Sistem Informasi Layanan Pengaduan Mahasiswa MI')
@section('content')

    <div class="fixed inset-0 z-50 flex items-center justify-center bg-[#2b4cba] overflow-hidden">
        
        <div class="absolute top-0 left-0 w-80 h-80 bg-[#2441a1] rounded-br-full opacity-80 mix-blend-multiply"></div>
        <div class="absolute bottom-0 right-0 w-[30rem] h-[30rem] bg-[#2441a1] rounded-tl-full opacity-80 mix-blend-multiply"></div>
        <div class="absolute -bottom-20 -left-20 w-96 h-96 bg-[#3657c9] rounded-tr-full opacity-50"></div>

        <div class="relative z-10 w-full max-w-lg p-8 bg-white/10 backdrop-blur-md border border-white/20 rounded-3xl shadow-2xl mx-4">
            
            <div class="flex items-center justify-center gap-4 mb-8">
                <img src="https://polmed.ac.id/wp-content/uploads/2014/04/logo-polmed-png.png" alt="Logo Polmed" class="h-16 w-auto object-contain drop-shadow-lg flex-shrink-0">
                <div class="text-left border-l border-white/30 pl-4 whitespace-nowrap">
                    <h1 class="text-white font-bold text-lg leading-none">Layanan Pengaduan Mahasiswa</h1>
                    <p class="text-white/80 text-sm font-medium mt-1.5">Politeknik Negeri Medan</p>
                </div>
            </div>

            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-white mb-1">Selamat Datang</h2>
                <p class="text-white/80 text-sm">Silakan Login ke akun Anda untuk melanjutkan.</p>
            </div>

            @if (session('status'))
                <div class="mb-6 p-4 bg-emerald-500/20 border border-emerald-500/50 rounded-xl flex items-start gap-3 backdrop-blur-sm">
                    <svg class="w-5 h-5 text-emerald-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-emerald-100 text-sm font-medium">{{ session('status') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 p-4 bg-red-500/20 border border-red-500/50 rounded-xl flex items-start gap-3 backdrop-blur-sm">
                    <svg class="w-5 h-5 text-red-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-red-100 text-sm font-medium">{{ session('error') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf

                <div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username"
                               class="w-full pl-12 pr-4 py-3 bg-transparent border border-white/80 rounded-full text-sm text-white placeholder-white/70 focus:ring-2 focus:ring-white focus:border-white focus:bg-white/10 outline-none transition-all {{ $errors->has('email') ? 'border-red-400' : '' }}"
                               placeholder="Email" />
                    </div>
                    @error('email')
                        <p class="mt-2 text-xs font-bold text-red-300 flex items-center gap-1 px-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div>
                    <div class="relative" x-data="{ show: false }">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <input id="password" :type="show ? 'text' : 'password'" name="password" required autocomplete="current-password"
                               class="w-full pl-12 pr-12 py-3 bg-transparent border border-white/80 rounded-full text-sm text-white placeholder-white/70 focus:ring-2 focus:ring-white focus:border-white focus:bg-white/10 outline-none transition-all {{ $errors->has('password') ? 'border-red-400' : '' }}"
                               placeholder="Password" />
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-white/80 hover:text-white focus:outline-none">
                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-2 text-xs font-bold text-red-300 flex items-center gap-1 px-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="flex items-center justify-between px-2 pt-1">
                    <div class="flex items-center">
                        <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 text-blue-500 bg-transparent border-white/80 rounded focus:ring-blue-500">
                        <label for="remember_me" class="ml-2 text-xs font-medium text-white hover:text-gray-200 transition-colors cursor-pointer">
                            Ingat Saya
                        </label>
                    </div>
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="text-xs font-bold text-blue-300 hover:text-white transition-colors">
                            Lupa password?
                        </a>
                    @endif
                </div>

                <button type="submit" class="w-full py-3.5 mt-4 bg-blue-600 text-white font-bold rounded-lg text-sm shadow-lg shadow-blue-500/40 hover:bg-blue-700 hover:shadow-blue-600/50 transition-all focus:ring-4 focus:ring-blue-500/50 flex justify-center items-center gap-2">
                    Login
                </button>

            </form>

            <div class="mt-6 text-center">
                <p class="text-sm text-white/90 font-medium">
                    Belum memiliki akun? 
                    <a href="{{ route('register') }}" class="font-bold text-blue-300 hover:text-white transition-colors">Daftar Akun</a>
                </p>
            </div>
        </div>
    </div>

@endsection