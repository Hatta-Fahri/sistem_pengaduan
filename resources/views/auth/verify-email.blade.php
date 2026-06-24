<x-guest-layout>

    <div class="fixed inset-0 z-50 bg-[#2b4cba] overflow-y-auto">
        
        <div class="fixed top-0 left-0 w-80 h-80 bg-[#2441a1] rounded-br-full opacity-80 mix-blend-multiply pointer-events-none"></div>
        <div class="fixed bottom-0 right-0 w-[30rem] h-[30rem] bg-[#2441a1] rounded-tl-full opacity-80 mix-blend-multiply pointer-events-none"></div>
        <div class="fixed -bottom-20 -left-20 w-96 h-96 bg-[#3657c9] rounded-tr-full opacity-50 pointer-events-none"></div>

        <div class="relative z-10 min-h-screen flex items-center justify-center p-4 py-10" x-data="verificationLimit()" x-init="init()">
            
            <div class="w-full max-w-lg p-8 bg-white/10 backdrop-blur-md border border-white/20 rounded-3xl shadow-2xl">
                
                <div class="flex items-center justify-center gap-4 mb-8">
                    <img src="https://polmed.ac.id/wp-content/uploads/2014/04/logo-polmed-png.png" alt="Logo Polmed" class="h-16 w-auto object-contain drop-shadow-lg flex-shrink-0">
                    <div class="text-left border-l border-white/30 pl-4 whitespace-nowrap">
                        <h1 class="text-white font-bold text-lg leading-none">Layanan Pengaduan Mahasiswa</h1>
                        <p class="text-white/80 text-sm font-medium mt-1.5">Politeknik Negeri Medan</p>
                    </div>
                </div>

                <div class="mb-4 text-center">
                    <h2 class="text-2xl font-bold text-white">Verifikasi Alamat Email</h2>
                </div>

                <div class="mb-6 text-sm text-white/80 leading-relaxed text-center">
                    Terima kasih telah mendaftar! Sebelum melanjutkan, mohon verifikasi alamat email Anda dengan mengklik tautan yang baru saja kami kirimkan. Jika Anda tidak menerima emailnya, kami akan dengan senang hati mengirimkan yang baru.
                </div>

                @if (session('status') == 'verification-link-sent')
                    <div class="mb-6 p-4 bg-emerald-500/20 border border-emerald-500/50 rounded-xl flex items-start gap-3 backdrop-blur-sm">
                        <svg class="w-5 h-5 text-emerald-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-emerald-100 text-sm font-medium">Tautan verifikasi baru telah dikirim ke alamat email yang Anda gunakan saat mendaftar.</p>
                    </div>
                @endif

                <div class="mt-6 space-y-4">
                    
                    <form method="POST" action="{{ route('verification.send') }}" class="w-full" @submit="submitForm($event)">
                        @csrf
                        
                        <button type="submit" 
                                :disabled="isDisabled"
                                class="w-full py-3.5 bg-blue-500 text-white font-bold rounded-lg text-sm shadow-lg shadow-blue-500/40 hover:bg-blue-600 hover:shadow-blue-600/50 transition-all focus:ring-4 focus:ring-blue-500/50 flex justify-center items-center gap-2 disabled:opacity-60 disabled:cursor-not-allowed disabled:hover:bg-blue-500 disabled:hover:shadow-none">
                            <span x-text="buttonText">Kirim Ulang Email Verifikasi</span>
                            <svg x-show="!isDisabled" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}" class="text-center w-full">
                        @csrf
                        <button type="submit" class="text-sm font-bold text-blue-300 hover:text-white transition-colors focus:outline-none focus:underline mt-2">
                            Kembali
                        </button>
                    </form>

                </div>

            </div>
        </div>
    </div>

    <script>
        function verificationLimit() {
            return {
                clicks: parseInt(localStorage.getItem('verif_req_count')) || 0,
                isDisabled: false,
                buttonText: 'Kirim Ulang Email Verifikasi',

                init() {
                    this.evaluateState();
                },

                evaluateState() {
                    if (this.clicks >= 5) {
                        this.isDisabled = true;
                        this.buttonText = 'Batas pengiriman tercapai (Maksimal 5x)';
                    } else {
                        this.isDisabled = false;
                        this.buttonText = 'Kirim Ulang Email Verifikasi';
                    }
                },

                submitForm(e) {
                    if (this.isDisabled) {
                        e.preventDefault();
                        return;
                    }

                    this.clicks++;
                    localStorage.setItem('verif_req_count', this.clicks);

                    this.evaluateState();
                }
            }
        }
    </script>
</x-guest-layout>