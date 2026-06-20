@extends(auth()->user()->isAdmin() ? 'layouts.admin' : 'layouts.mahasiswa')
@section('title', 'Profil Saya')
@section('content')

<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-polmed-blue to-blue-800 rounded-2xl p-6 sm:p-8 shadow-md text-white relative overflow-hidden">
        <div class="relative z-10 flex items-center gap-5">
            <div class="w-16 h-16 rounded-full bg-white text-polmed-blue flex items-center justify-center font-extrabold text-2xl shadow-inner">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div>
                <h1 class="text-2xl font-extrabold tracking-tight">{{ auth()->user()->name }}</h1>
                <p class="text-blue-200 font-medium text-sm mt-1">
                    {{ auth()->user()->isAdmin() ? 'Administrator Sistem' : 'Mahasiswa • ' . (auth()->user()->nim ?? '-') }}
                </p>
            </div>
        </div>
        <!-- Decorative Shapes -->
        <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white opacity-10 rounded-full blur-3xl"></div>
    </div>

    <!-- Container Form -->
    <div class="space-y-6">
        <!-- Update Profile Info -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm">
            <div class="max-w-xl">
                @include('profile.partials.update-profile-information-form')
            </div>
        </div>

        <!-- Update Password -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-gray-200 shadow-sm">
            <div class="max-w-xl">
                @include('profile.partials.update-password-form')
            </div>
        </div>

        <!-- Delete Account -->
        <div class="bg-white p-6 sm:p-8 rounded-2xl border border-red-100 shadow-sm">
            <div class="max-w-xl">
                @include('profile.partials.delete-user-form')
            </div>
        </div>
    </div>
</div>

@endsection
