<section>
    <header>
        <h2 class="text-lg font-bold text-white">Informasi Profil</h2>
        <p class="mt-1 text-sm text-white/80 font-medium">Perbarui informasi profil dan alamat email akun Anda.</p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('mahasiswa.profil.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="block text-sm font-bold text-white/90 mb-1.5">Nama Lengkap</label>
            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name"
                   class="w-full px-4 py-3 bg-white/5 border rounded-xl text-sm font-semibold text-white focus:ring-2 focus:ring-white focus:border-white focus:bg-white/10 outline-none transition-all {{ $errors->has('name') ? 'border-red-400' : 'border-white/30' }}" />
            @error('name')
                <p class="mt-1.5 text-xs font-bold text-red-300">{{ $message }}</p>
            @enderror
        </div>

        <div>
            <label for="email" class="block text-sm font-bold text-white/90 mb-1.5">Alamat Email</label>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required autocomplete="username"
                   class="w-full px-4 py-3 bg-white/5 border rounded-xl text-sm font-semibold text-white focus:ring-2 focus:ring-white focus:border-white focus:bg-white/10 outline-none transition-all {{ $errors->has('email') ? 'border-red-400' : 'border-white/30' }}" />
            @error('email')
                <p class="mt-1.5 text-xs font-bold text-red-300">{{ $message }}</p>
            @enderror

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3 p-3 bg-amber-500/20 backdrop-blur-sm rounded-xl border border-amber-500/50">
                    <p class="text-sm text-amber-100 font-medium">
                        Email Anda belum diverifikasi.
                        <button form="send-verification" class="underline text-sm font-bold text-white hover:text-amber-200 rounded-md focus:outline-none">
                            Klik di sini untuk mengirim ulang email verifikasi.
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-xs font-bold text-emerald-300">
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
                <label class="block text-sm font-bold text-white/60 mb-1.5">NIM</label>
                <input type="text" value="{{ $user->nim }}" disabled class="w-full px-4 py-3 bg-white/10 border border-white/10 rounded-xl text-sm font-medium text-white/50 cursor-not-allowed" />
            </div>
            <div>
                <label class="block text-sm font-bold text-white/60 mb-1.5">Kelas</label>
                <input type="text" value="{{ $user->class }}" disabled class="w-full px-4 py-3 bg-white/10 border border-white/10 rounded-xl text-sm font-medium text-white/50 cursor-not-allowed" />
            </div>
        </div>
        <p class="text-xs text-white/50 mt-2 italic">*NIM dan Kelas tidak dapat diubah. Hubungi Administrator jika terdapat kesalahan.</p>
        @endif

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-sm shadow-lg shadow-blue-500/40 transition-all focus:ring-4 focus:ring-blue-500/50">
                Simpan Perubahan
            </button>

            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm font-bold text-emerald-300 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Tersimpan.
                </p>
            @endif
        </div>
    </form>
</section>
