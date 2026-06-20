<x-guest-layout>
    <div class="mb-2 text-center">
        <h2 class="text-xl font-bold text-gray-900">Verifikasi Alamat Email</h2>
    </div>

    <div class="mb-4 text-sm text-gray-600 leading-relaxed">
        Terima kasih telah mendaftar! Sebelum melanjutkan, mohon verifikasi alamat email Anda dengan mengklik tautan yang baru saja kami kirimkan. Jika Anda tidak menerima emailnya, kami akan dengan senang hati mengirimkan yang baru.
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-emerald-600">
            Tautan verifikasi baru telah dikirim ke alamat email yang Anda gunakan saat mendaftar.
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    Kirim Ulang Email Verifikasi
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-polmed-blue">
                Keluar
            </button>
        </form>
    </div>
</x-guest-layout>
