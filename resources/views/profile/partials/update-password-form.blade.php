<section>
    <header>
        <h2 class="text-lg font-bold text-white">
            Ubah Password
        </h2>
        <p class="mt-1 text-sm text-white/80 font-medium">
            Pastikan akun Anda menggunakan password yang panjang dan acak agar tetap aman.
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-sm font-bold text-white/90 mb-1.5">Password Saat Ini</label>
            <input id="update_password_current_password" name="current_password" type="password" autocomplete="current-password"
                   class="w-full px-4 py-3 bg-white/5 border rounded-xl text-sm font-semibold text-white focus:ring-2 focus:ring-white focus:border-white focus:bg-white/10 outline-none transition-all {{ $errors->updatePassword->has('current_password') ? 'border-red-400' : 'border-white/30' }}" />
            @if($errors->updatePassword->has('current_password'))
                <p class="mt-1.5 text-xs font-bold text-red-300">{{ $errors->updatePassword->first('current_password') }}</p>
            @endif
        </div>

        <div>
            <label for="update_password_password" class="block text-sm font-bold text-white/90 mb-1.5">Password Baru</label>
            <input id="update_password_password" name="password" type="password" autocomplete="new-password"
                   class="w-full px-4 py-3 bg-white/5 border rounded-xl text-sm font-semibold text-white focus:ring-2 focus:ring-white focus:border-white focus:bg-white/10 outline-none transition-all {{ $errors->updatePassword->has('password') ? 'border-red-400' : 'border-white/30' }}" />
            @if($errors->updatePassword->has('password'))
                <p class="mt-1.5 text-xs font-bold text-red-300">{{ $errors->updatePassword->first('password') }}</p>
            @endif
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-sm font-bold text-white/90 mb-1.5">Konfirmasi Password Baru</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password" autocomplete="new-password"
                   class="w-full px-4 py-3 bg-white/5 border rounded-xl text-sm font-semibold text-white focus:ring-2 focus:ring-white focus:border-white focus:bg-white/10 outline-none transition-all {{ $errors->updatePassword->has('password_confirmation') ? 'border-red-400' : 'border-white/30' }}" />
            @if($errors->updatePassword->has('password_confirmation'))
                <p class="mt-1.5 text-xs font-bold text-red-300">{{ $errors->updatePassword->first('password_confirmation') }}</p>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-xl text-sm shadow-lg shadow-blue-500/40 transition-all focus:ring-4 focus:ring-blue-500/50">
                Simpan Password
            </button>

            @if (session('status') === 'password-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm font-bold text-emerald-300 flex items-center gap-1.5">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Tersimpan.
                </p>
            @endif
        </div>
    </form>
</section>
