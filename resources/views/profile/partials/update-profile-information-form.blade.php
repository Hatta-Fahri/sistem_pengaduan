<section>
    <header>
        <h2 class="text-lg font-bold text-gray-900">Informasi Profil</h2>
        <p class="mt-1 text-sm text-gray-500 font-medium">Perbarui informasi profil dan alamat email akun Anda.</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('mahasiswa.profil.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="block text-sm font-bold text-gray-700 mb-1.5">Nama Lengkap</label>
            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name"
                   class="w-full px-4 py-3 bg-gray-50 border rounded-xl text-sm font-semibold text-gray-800 focus:ring-4 focus:ring-blue-500/20 focus:border-polmed-blue focus:bg-white transition-all {{ $errors->has('name') ? 'border-red-400' : 'border-gray-200' }}" />
            @error('name')
                <p class="mt-1.5 text-xs font-bold text-red-500">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-bold text-gray-700 mb-1.5">Alamat Email</label>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username"
                   class="w-full px-4 py-3 bg-gray-50 border rounded-xl text-sm font-semibold text-gray-800 focus:ring-4 focus:ring-blue-500/20 focus:border-polmed-blue focus:bg-white transition-all {{ $errors->has('email') ? 'border-red-400' : 'border-gray-200' }}" />
            @error('email')
                <p class="mt-1.5 text-xs font-bold text-red-500">{{ $message }}</p>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 p-3 bg-amber-50 rounded-lg border border-amber-200">
                    <p class="text-sm text-amber-800 font-medium">
                        Email Anda belum diverifikasi.
                        <button form="send-verification" class="underline text-sm font-bold text-amber-900 hover:text-amber-700 rounded-md focus:outline-none">
                            Klik di sini untuk mengirim ulang email verifikasi.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-xs font-bold text-emerald-600">
                            Tautan verifikasi baru telah dikirim ke email Anda.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Role spesifik info (Hanya tampil, tidak bisa diubah disini) -->
        @if(!$user->isAdmin())
        <div class="grid grid-cols-2 gap-4 pt-2">
            <div>
                <label class="block text-sm font-bold text-gray-400 mb-1.5">NIM</label>
                <input type="text" value="{{ $user->nim }}" disabled class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl text-sm font-medium text-gray-500 cursor-not-allowed" />
            </div>
            <div>
                <label class="block text-sm font-bold text-gray-400 mb-1.5">Kelas</label>
                <input type="text" value="{{ $user->class }}" disabled class="w-full px-4 py-3 bg-gray-100 border border-gray-200 rounded-xl text-sm font-medium text-gray-500 cursor-not-allowed" />
            </div>
        </div>
        <p class="text-xs text-gray-400 mt-2 italic">*NIM dan Kelas tidak dapat diubah. Hubungi Administrator jika terdapat kesalahan.</p>
        @endif

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="px-6 py-2.5 bg-gray-900 hover:bg-black text-white font-bold rounded-xl text-sm shadow-sm transition-all focus:ring-4 focus:ring-gray-300">
                Simpan Perubahan
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm font-bold text-emerald-600 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Tersimpan.
                </p>
            @endif
        </div>
    </form>
</section>
