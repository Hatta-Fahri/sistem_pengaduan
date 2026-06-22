<section class="space-y-6">
    <header>
        <h2 class="text-lg font-bold text-red-200">
            Hapus Akun
        </h2>

        <p class="mt-1 text-sm text-red-100/80 font-medium">
            Setelah akun dihapus, semua sumber daya dan data akan dihapus secara permanen. Sebelum menghapus akun, harap unduh data atau informasi yang ingin Anda simpan.
        </p>
    </header>

    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="px-6 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl text-sm shadow-lg shadow-red-500/40 transition-all focus:ring-4 focus:ring-red-500/50"
    >Hapus Akun</button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('mahasiswa.profil.destroy') }}" class="p-6 sm:p-8 bg-[#2b4cba] text-white">
            @csrf
            @method('delete')

            <h2 class="text-lg font-bold text-white">
                Apakah Anda yakin ingin menghapus akun?
            </h2>

            <p class="mt-2 text-sm text-white/80 leading-relaxed">
                Setelah akun Anda dihapus, semua sumber daya dan data akan dihapus secara permanen. Silakan masukkan password Anda untuk mengonfirmasi bahwa Anda ingin menghapus akun secara permanen.
            </p>

            <div class="mt-6">
                <label for="password" class="sr-only">Password</label>

                <input
                    id="password"
                    name="password"
                    type="password"
                    class="mt-1 block w-full px-4 py-3 bg-white/5 border rounded-xl text-sm font-semibold text-white focus:ring-2 focus:ring-red-500 focus:border-red-500 focus:bg-white/10 outline-none transition-all {{ $errors->userDeletion->has('password') ? 'border-red-400' : 'border-white/30' }}"
                    placeholder="Password"
                />

                @if($errors->userDeletion->has('password'))
                    <p class="mt-1.5 text-xs font-bold text-red-300">{{ $errors->userDeletion->first('password') }}</p>
                @endif
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')" class="px-5 py-2.5 bg-white/10 hover:bg-white/20 text-white font-bold rounded-xl text-sm transition-all border border-white/20">
                    Batal
                </button>

                <button type="submit" class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-bold rounded-xl text-sm shadow-lg shadow-red-500/40 transition-all focus:ring-4 focus:ring-red-500/50">
                    Hapus Akun Permanen
                </button>
            </div>
        </form>
    </x-modal>
</section>
