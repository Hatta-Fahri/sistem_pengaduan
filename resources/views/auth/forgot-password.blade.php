@extends('layouts.guest')
@section('title', 'Lupa Password - Sistem Informasi Layanan Pengaduan Mahasiswa MI')
@section('content')

    <style>
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus, 
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 50px #2441a1 inset !important;
            -webkit-text-fill-color: #ffffff !important;
            caret-color: #ffffff !important;
            border-color: rgba(255, 255, 255, 0.8) !important;
        }
    </style>

    <div class="fixed inset-0 z-50 bg-[#2b4cba] overflow-y-auto">
        
        <div class="fixed top-0 left-0 w-80 h-80 bg-[#2441a1] rounded-br-full opacity-80 mix-blend-multiply pointer-events-none"></div>
        <div class="fixed bottom-0 right-0 w-[30rem] h-[30rem] bg-[#2441a1] rounded-tl-full opacity-80 mix-blend-multiply pointer-events-none"></div>
        <div class="fixed -bottom-20 -left-20 w-96 h-96 bg-[#3657c9] rounded-tr-full opacity-50 pointer-events-none"></div>

        <div class="relative z-10 min-h-screen flex items-center justify-center p-4 py-10">
            
            <div class="w-full max-w-lg p-8 bg-white/10 backdrop-blur-md border border-white/20 rounded-3xl shadow-2xl">
                
                <div class="flex items-center justify-center gap-4 mb-8">
                    <img src="https://polmed.ac.id/wp-content/uploads/2014/04/logo-polmed-png.png" alt="Logo Polmed" class="h-16 w-auto object-contain drop-shadow-lg flex-shrink-0">
                    <div class="text-left border-l border-white/30 pl-4 whitespace-nowrap">
                        <h1 class="text-white font-bold text-lg leading-none">Layanan Pengaduan Mahasiswa</h1>
                        <p class="text-white/80 text-sm font-medium mt-1.5">Politeknik Negeri Medan</p>
                    </div>
                </div>

                <div class="text-center mb-8">
                    <h2 class="text-3xl font-bold text-white mb-3">Lupa Password?</h2>
                    <p class="text-white/80 text-sm font-medium leading-relaxed">
                        Tidak masalah. Masukkan alamat email yang terdaftar pada akun Anda, dan kami akan mengirimkan tautan untuk mengatur ulang password Anda.
                    </p>
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

                <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                    @csrf

                    <div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-white/80" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                                   class="w-full pl-12 pr-4 py-3 bg-transparent border border-white/80 rounded-full text-sm text-white placeholder-white/70 focus:ring-2 focus:ring-white focus:border-white focus:bg-white/10 outline-none transition-all {{ $errors->has('email') ? 'border-red-400' : '' }}"
                                   placeholder="nama@example.com" />
                        </div>
                        @error('email')
                            <p class="mt-2 text-xs font-bold text-red-300 flex items-center gap-1 px-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <button type="submit"
                            class="w-full py-3.5 bg-blue-500 text-white font-bold rounded-lg text-sm shadow-lg shadow-blue-500/40 hover:bg-blue-600 hover:shadow-blue-600/50 transition-all focus:ring-4 focus:ring-blue-500/50 flex justify-center items-center gap-2">
                        <span>Kirim Link Reset Password</span>
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    </button>
                </form>

                <div class="mt-8 text-center">
                    <a href="{{ route('login') }}" class="text-sm font-bold text-blue-300 hover:text-white transition-colors inline-flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali ke halaman Login
                    </a>
                </div>

            </div>
        </div>
    </div>



@endsection