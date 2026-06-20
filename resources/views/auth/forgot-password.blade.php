@extends('layouts.guest')
@section('title', 'Lupa Password — SILPM')
@section('content')

    <div class="mb-8">
        <h2 class="text-3xl font-extrabold text-gray-900 tracking-tight mb-3">Lupa Password?</h2>
        <p class="text-gray-500 text-sm font-medium leading-relaxed">
            Tidak masalah. Masukkan alamat email yang terdaftar pada akun Anda, dan kami akan mengirimkan tautan untuk mengatur ulang password Anda.
        </p>
    </div>

    <!-- Session Status -->
    @if (session('status'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 rounded-xl flex items-start gap-3">
            <svg class="w-5 h-5 text-emerald-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p class="text-emerald-700 text-sm font-medium">{{ session('status') }}</p>
        </div>
    @endif
    @if (session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl flex items-start gap-3">
            <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <p class="text-red-700 text-sm font-medium">{{ session('error') }}</p>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email" class="block text-sm font-bold text-gray-700 mb-1.5">Alamat Email</label>
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full pl-11 pr-4 py-3 bg-gray-50 border rounded-xl text-sm font-semibold text-gray-800 focus:ring-4 focus:ring-blue-500/20 focus:border-polmed-blue focus:bg-white outline-none transition-all {{ $errors->has('email') ? 'border-red-400' : 'border-gray-200' }}"
                       placeholder="nama@example.com" />
            </div>
            @error('email')
                <p class="mt-2 text-xs font-bold text-red-500 flex items-center gap-1">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    {{ $message }}
                </p>
            @enderror
        </div>

        <button type="submit" class="w-full py-3.5 bg-polmed-blue hover:bg-blue-800 text-white font-bold rounded-xl text-sm shadow-lg shadow-blue-900/20 transition-all focus:ring-4 focus:ring-blue-500/30 flex justify-center items-center gap-2">
            Kirim Link Reset Password
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
        </button>
    </form>

    <div class="mt-8 text-center">
        <a href="{{ route('login') }}" class="text-sm font-bold text-polmed-blue hover:text-blue-800 transition-colors inline-flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke halaman Login
        </a>
    </div>

@endsection
