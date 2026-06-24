@extends('layouts.guest')
@section('title', 'Atur Ulang Password — SILPM')
@section('content')

<div class="fixed inset-0 z-50 bg-[#2b4cba] overflow-y-auto">

    <div class="fixed top-0 left-0 w-80 h-80 bg-[#2441a1] rounded-br-full opacity-80 mix-blend-multiply pointer-events-none"></div>
    <div class="fixed bottom-0 right-0 w-[30rem] h-[30rem] bg-[#2441a1] rounded-tl-full opacity-80 mix-blend-multiply pointer-events-none"></div>
    <div class="fixed -bottom-20 -left-20 w-96 h-96 bg-[#3657c9] rounded-tr-full opacity-50 pointer-events-none"></div>

    <div class="relative z-10 min-h-screen flex items-center justify-center p-4 py-10">

        <div class="w-full max-w-md p-8 bg-white/10 backdrop-blur-md border border-white/20 rounded-3xl shadow-2xl">

            <div class="flex items-center justify-center gap-4 mb-8">
                <img src="https://polmed.ac.id/wp-content/uploads/2014/04/logo-polmed-png.png" alt="Logo Polmed" class="h-16 w-auto object-contain drop-shadow-lg flex-shrink-0">
                <div class="text-left border-l border-white/30 pl-4 whitespace-nowrap">
                    <h1 class="text-white font-bold text-lg leading-none">Layanan Pengaduan Mahasiswa</h1>
                    <p class="text-white/80 text-sm font-medium mt-1.5">Politeknik Negeri Medan</p>
                </div>
            </div>

            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-white mb-1">Atur Ulang Password</h2>
                <p class="text-white/80 text-sm">Silakan buat password baru untuk akun Anda.</p>
            </div>

            {{-- Session Status --}}
            @if (session('status'))
                <div class="mb-5 p-4 bg-emerald-500/20 border border-emerald-300/40 rounded-2xl flex items-start gap-3">
                    <svg class="w-5 h-5 text-emerald-300 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-emerald-200 text-sm font-medium">{{ session('status') }}</p>
                </div>
            @endif
            @if (session('error'))
                <div class="mb-5 p-4 bg-red-500/20 border border-red-300/40 rounded-2xl flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-300 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <p class="text-red-200 text-sm font-medium">{{ session('error') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                {{-- Email --}}
                <div>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username"
                               class="w-full pl-12 pr-4 py-3 bg-transparent border border-white/80 rounded-full text-sm text-white placeholder-white/70 focus:ring-2 focus:ring-white focus:border-white focus:bg-white/10 outline-none transition-all {{ $errors->has('email') ? 'border-red-400' : '' }}"
                               placeholder="Email" readonly />
                    </div>
                    @error('email')
                        <p class="mt-2 text-xs font-bold text-red-300 flex items-center gap-1 px-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Password Baru --}}
                <div x-data="{ show: false }">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <input id="password" :type="show ? 'text' : 'password'" name="password" required autocomplete="new-password"
                               class="w-full pl-12 pr-12 py-3 bg-transparent border border-white/80 rounded-full text-sm text-white placeholder-white/70 focus:ring-2 focus:ring-white focus:border-white focus:bg-white/10 outline-none transition-all {{ $errors->has('password') ? 'border-red-400' : '' }}"
                               placeholder="Password Baru (Minimal 8 karakter)" />
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

                {{-- Konfirmasi Password --}}
                <div x-data="{ show: false }">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <input id="password_confirmation" :type="show ? 'text' : 'password'" name="password_confirmation" required autocomplete="new-password"
                               class="w-full pl-12 pr-12 py-3 bg-transparent border border-white/80 rounded-full text-sm text-white placeholder-white/70 focus:ring-2 focus:ring-white focus:border-white focus:bg-white/10 outline-none transition-all {{ $errors->has('password_confirmation') ? 'border-red-400' : '' }}"
                               placeholder="Konfirmasi Password Baru" />
                        <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-white/80 hover:text-white focus:outline-none">
                            <svg x-show="!show" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            <svg x-show="show" x-cloak class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <p class="mt-2 text-xs font-bold text-red-300 flex items-center gap-1 px-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <button type="submit" class="w-full py-3.5 mt-6 bg-blue-500 text-white font-bold rounded-lg text-sm shadow-lg shadow-blue-500/40 hover:bg-blue-600 hover:shadow-blue-600/50 transition-all focus:ring-4 focus:ring-blue-500/50 flex justify-center items-center gap-2">
                    Simpan Password Baru
                </button>

            </form>

        </div>
    </div>
</div>

@endsection