@extends(auth()->user()->isAdmin() ? 'layouts.admin' : 'layouts.mahasiswa')
@section('title', 'Profil Saya')
@section('content')

<div class="relative bg-[#3b5fc0] rounded-2xl shadow-lg">

    <div class="relative z-10 p-6 sm:p-8 space-y-8 max-w-4xl mx-auto">
        
        {{-- ===== Header Banner Profil ===== --}}
        <div class="flex items-center gap-5 border-b border-white/20 pb-6">
            {{-- Avatar ikon profil --}}
            <div class="w-16 h-16 rounded-full bg-white/15 backdrop-blur-sm border border-white/30 flex items-center justify-center flex-shrink-0 shadow-inner">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold tracking-tight text-white">{{ auth()->user()->name }}</h2>
                <p class="text-white/80 text-sm font-medium mt-1">
                    {{ auth()->user()->isAdmin() ? 'Administrator Sistem' : 'Mahasiswa • ' . (auth()->user()->nim ?? '-') }}
                </p>
            </div>
        </div>

        {{-- ===== Container Formulir (Liquid Glass) ===== --}}
        <div class="space-y-6">
            {{-- Informasi Profil --}}
            <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-6 sm:p-8 shadow-lg transition-all hover:bg-white/15">
                @include('profile.partials.update-profile-information-form')
            </div>

            {{-- Ubah Password --}}
            <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-6 sm:p-8 shadow-lg transition-all hover:bg-white/15">
                @include('profile.partials.update-password-form')
            </div>

            {{-- Hapus Akun --}}
            <div class="bg-red-500/10 backdrop-blur-md border border-red-400/25 rounded-2xl p-6 sm:p-8 shadow-lg transition-all hover:bg-red-500/20">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>
@endsection
