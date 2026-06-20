@extends('layouts.admin')
@section('title', 'Detail Pengaduan #' . $pengaduan->id)
@section('content')

@php
    $badgeClass = [
        'menunggu_verifikasi'           => 'bg-gray-100 text-gray-700 ring-1 ring-gray-200',
        'sedang_diproses'               => 'bg-blue-50 text-blue-700 ring-1 ring-blue-200',
        'membutuhkan_informasi_tambahan'=> 'bg-amber-50 text-amber-700 ring-1 ring-amber-200',
        'menunggu_konfirmasi_mahasiswa' => 'bg-cyan-50 text-cyan-700 ring-1 ring-cyan-200',
        'selesai_ditangani'             => 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200',
        'ditolak'                       => 'bg-red-50 text-red-700 ring-1 ring-red-200',
    ];
    $dotClass = [
        'menunggu_verifikasi'           => 'bg-gray-300 ring-gray-100',
        'sedang_diproses'               => 'bg-blue-500 ring-blue-100',
        'membutuhkan_informasi_tambahan'=> 'bg-amber-500 ring-amber-100',
        'menunggu_konfirmasi_mahasiswa' => 'bg-cyan-500 ring-cyan-100',
        'selesai_ditangani'             => 'bg-emerald-500 ring-emerald-100',
        'ditolak'                       => 'bg-red-500 ring-red-100',
    ];
    $currentBadge = $badgeClass[$pengaduan->status] ?? 'bg-gray-100 text-gray-700';
    $wajibCatatan = [
        \App\Models\Pengaduan::STATUS_DITOLAK,
        \App\Models\Pengaduan::STATUS_BUTUH_INFO,
    ];
@endphp

<div class="max-w-6xl mx-auto space-y-6">

    {{-- ===== Breadcrumb ===== --}}
    <nav class="flex text-sm text-gray-500 gap-2 items-center font-medium mb-2">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-polmed-blue transition-colors flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Dashboard
        </a>
        <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <a href="{{ route('admin.pengaduan.index') }}" class="hover:text-polmed-blue transition-colors">Manajemen Pengaduan</a>
        <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <span class="text-polmed-blue font-bold">Detail #{{ $pengaduan->id }}</span>
    </nav>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 lg:gap-8">

        {{-- ===== KOLOM KIRI: Detail + Timeline ===== --}}
        <div class="xl:col-span-2 space-y-6 lg:space-y-8">

            {{-- Kartu Utama Pengaduan --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden relative">
                <!-- Decorative Top Border -->
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-polmed-blue to-blue-400"></div>
                
                <div class="p-6 sm:p-8">
                    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4 mb-8">
                        <div class="flex-1 min-w-0">
                            <h1 class="text-2xl font-bold text-gray-900 leading-snug tracking-tight mb-2">{{ $pengaduan->subjek }}</h1>
                            <div class="flex flex-wrap items-center gap-3 text-sm text-gray-500 font-medium">
                                <span class="flex items-center gap-1.5 bg-gray-50 px-2.5 py-1 rounded-md border border-gray-100">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                    {{ $pengaduan->kategori->nama_kategori }}
                                </span>
                                <span class="flex items-center gap-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Dilaporkan {{ $pengaduan->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                        <span class="inline-flex items-center px-3.5 py-1.5 rounded-full text-sm font-bold flex-shrink-0 {{ $currentBadge }}">
                            {{ $statusLabels[$pengaduan->status] ?? $pengaduan->status }}
                        </span>
                    </div>

                    {{-- Identitas Pelapor --}}
                    <div class="bg-blue-50/50 rounded-xl p-5 border border-blue-100/50 mb-8">
                        <div class="flex items-center gap-2 mb-4">
                            <svg class="w-5 h-5 text-polmed-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            <h3 class="font-bold text-gray-800 tracking-tight">Informasi Pelapor</h3>
                        </div>
                        @if ($pengaduan->is_anonymous)
                            <div class="flex items-center gap-3 text-gray-500">
                                <svg class="w-8 h-8 text-purple-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.243 4.243L9.88 9.88"/></svg>
                                <div>
                                    <p class="font-bold text-gray-600">Identitas pelapor disembunyikan (anonim)</p>
                                    <p class="text-xs text-gray-400 mt-0.5">Mahasiswa memilih untuk tidak menampilkan identitasnya. Notifikasi status tetap terkirim otomatis ke akun terkait.</p>
                                </div>
                            </div>
                        @else
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
                                <div>
                                    <p class="text-gray-400 text-xs mb-1 uppercase tracking-wider font-semibold">Nama Lengkap</p>
                                    <p class="font-bold text-gray-900">{{ $pengaduan->user->name }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-400 text-xs mb-1 uppercase tracking-wider font-semibold">NIM</p>
                                    <p class="font-bold text-gray-900">{{ $pengaduan->user->nim }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-400 text-xs mb-1 uppercase tracking-wider font-semibold">Kelas</p>
                                    <p class="font-bold text-gray-900">{{ $pengaduan->user->class }}</p>
                                </div>
                                <div>
                                    <p class="text-gray-400 text-xs mb-1 uppercase tracking-wider font-semibold">Email</p>
                                    <p class="font-medium text-gray-700 truncate" title="{{ $pengaduan->user->email }}">{{ $pengaduan->user->email }}</p>
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Isi Pengaduan --}}
                    <div>
                        <div class="flex items-center gap-2 mb-3 text-gray-800">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            <h3 class="font-bold tracking-tight">Isi Pengaduan</h3>
                        </div>
                        <div class="bg-gray-50 rounded-xl p-5 text-gray-700 leading-relaxed whitespace-pre-wrap border border-gray-100 text-[15px] font-medium">{{ $pengaduan->isi_pengaduan }}</div>
                        
                        <div class="mt-4 flex items-center justify-end text-xs font-semibold text-gray-400">
                            <span>Kejadian pada: <strong class="text-gray-600">{{ $pengaduan->tanggal_kejadian->format('d M Y, H:i') }}</strong></span>
                        </div>
                    </div>

                    {{-- Bukti Pendukung --}}
                    @if ($pengaduan->bukti)
                    <div class="mt-6">
                        <div class="flex items-center gap-2 mb-3 text-gray-800">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21.44 11.05l-9.19 9.19a6 6 0 01-8.49-8.49l9.19-9.19a4 4 0 015.66 5.66l-9.2 9.19a2 2 0 01-2.83-2.83l8.49-8.48"></path></svg>
                            <h3 class="font-bold tracking-tight">Bukti Pendukung</h3>
                        </div>
                        @if (str_ends_with($pengaduan->bukti, '.pdf'))
                            <a href="{{ $pengaduan->bukti_url }}" target="_blank"
                               class="inline-flex items-center gap-2 px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl text-sm font-bold text-polmed-blue hover:bg-gray-100 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                Lihat Dokumen PDF
                            </a>
                        @else
                            <a href="{{ $pengaduan->bukti_url }}" target="_blank" class="block w-fit">
                                <img src="{{ $pengaduan->bukti_url }}" alt="Bukti pendukung"
                                     class="max-h-64 rounded-xl border border-gray-200 hover:opacity-90 transition-opacity" />
                            </a>
                        @endif
                    </div>
                    @endif
                </div>
            </div>

            {{-- ===== Timeline Riwayat Status (ASC) ===== --}}
            <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8">
                <div class="flex items-center gap-2 mb-8">
                    <svg class="w-6 h-6 text-polmed-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <h2 class="text-xl font-bold text-gray-900 tracking-tight">Riwayat Penanganan</h2>
                </div>

                @if ($pengaduan->statusHistory->isEmpty())
                    <div class="py-10 text-center">
                        <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3">
                            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <p class="text-sm font-medium text-gray-500">Belum ada riwayat status pada pengaduan ini.</p>
                    </div>
                @else
                    <div class="space-y-0 relative before:absolute before:inset-0 before:ml-4 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-gray-200 before:to-transparent">
                        @foreach ($pengaduan->statusHistory as $history)
                        @php
                            $hBadge  = $badgeClass[$history->status_baru] ?? 'bg-gray-100 text-gray-700';
                            $hDot    = $dotClass[$history->status_baru] ?? 'bg-gray-300 ring-gray-100';
                            $hLabel  = $statusLabels[$history->status_baru] ?? $history->status_baru;
                            $hLama   = $history->status_lama
                                ? ($statusLabels[$history->status_lama] ?? $history->status_lama)
                                : null;
                        @endphp
                        <div class="relative flex items-start justify-between md:justify-normal md:odd:flex-row-reverse group mb-8">
                            <!-- Icon -->
                            <div class="flex items-center justify-center w-8 h-8 rounded-full border-4 border-white {{ $hDot }} shadow-sm shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2 absolute left-0 md:left-1/2 -ml-4 md:ml-0 top-0">
                                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            
                            <!-- Content -->
                            <div class="w-[calc(100%-3rem)] md:w-[calc(50%-2rem)] ml-8 md:ml-0 bg-white border border-gray-100 p-5 rounded-2xl shadow-sm hover:shadow-md transition-shadow group-odd:md:text-right">
                                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-2 mb-3">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $hBadge }}">
                                        {{ $hLabel }}
                                    </span>
                                    <span class="text-xs font-bold text-gray-400">
                                        {{ $history->created_at->format('d M Y, H:i') }}
                                    </span>
                                </div>
                                
                                @if ($hLama)
                                    <div class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2 flex items-center gap-1.5 group-odd:md:justify-end">
                                        Perubahan dari <span class="bg-gray-100 text-gray-500 px-1.5 py-0.5 rounded">{{ $hLama }}</span>
                                    </div>
                                @endif
                                
                                @if ($history->catatan)
                                    <div class="bg-gray-50/80 p-3 rounded-xl border border-gray-100 mb-3 text-sm text-gray-600 font-medium group-odd:md:text-left">
                                        "{{ $history->catatan }}"
                                    </div>
                                @endif
                                
                                <div class="text-xs font-semibold text-gray-400 flex items-center gap-1.5 group-odd:md:justify-end">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    {{ $history->changedBy?->name ?? 'Sistem (Otomatis)' }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- ===== KOLOM KANAN: Form Update Status (Sticky) ===== --}}
        <div class="xl:col-span-1">
            <div class="bg-white rounded-2xl border border-gray-200 shadow-lg shadow-gray-200/50 p-6 sm:p-8 sticky top-24"
                 x-data="{ 
                    selectedStatus: '{{ old('status', $pengaduan->status) }}',
                    wajibCatatanList: {{ json_encode($wajibCatatan) }},
                    get isCatatanWajib() {
                        return this.wajibCatatanList.includes(this.selectedStatus);
                    }
                 }">
                 
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 text-polmed-blue flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    </div>
                    <h2 class="text-xl font-bold text-gray-900 tracking-tight">Update Status</h2>
                </div>
                
                @if ($pengaduan->isFinal())
                    <div class="bg-gray-50 border border-gray-200 rounded-xl p-5 flex items-start gap-3">
                        <svg class="w-5 h-5 text-gray-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 10-8 0v4h8z"/></svg>
                        <p class="text-sm font-semibold text-gray-600 leading-relaxed">
                            Pengaduan ini sudah <strong>{{ $statusLabels[$pengaduan->status] ?? $pengaduan->status }}</strong> dan terkunci permanen. Status tidak dapat diubah lagi oleh admin.
                        </p>
                    </div>
                @else
                <p class="text-sm text-gray-500 font-medium mb-6 leading-relaxed">
                    Ubah status penanganan pengaduan ini. Mahasiswa akan menerima notifikasi email secara otomatis.
                    Pilih "Menunggu Konfirmasi Mahasiswa" jika sudah selesai ditangani — pengaduan baru benar-benar
                    selesai setelah dikonfirmasi mahasiswa (atau otomatis setelah {{ \App\Models\Pengaduan::SLA_HARI }} hari tanpa respons).
                </p>

                {{-- Tampilkan error validasi --}}
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                        <div class="flex items-center gap-2 text-red-800 font-bold text-sm mb-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            Terdapat Kesalahan
                        </div>
                        <ul class="list-disc list-inside text-xs font-medium text-red-700 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.pengaduan.update-status', $pengaduan) }}">
                    @csrf
                    @method('PATCH')

                    {{-- Pilih Status Baru --}}
                    <div class="mb-5">
                        <label for="status" class="block text-sm font-bold text-gray-700 mb-2">
                            Status Baru <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <select id="status" name="status" x-model="selectedStatus" required
                                    class="appearance-none w-full px-4 py-3 bg-gray-50 border rounded-xl text-sm font-semibold text-gray-800 focus:ring-4 focus:ring-blue-500/20 focus:border-polmed-blue focus:bg-white outline-none transition-all
                                           {{ $errors->has('status') ? 'border-red-400 bg-red-50' : 'border-gray-200' }}">
                                <option value="">-- Pilih Status --</option>
                                @foreach ($statusOptions as $key => $label)
                                    <option value="{{ $key }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>

                    {{-- Catatan Admin --}}
                    <div class="mb-8">
                        <label for="catatan_admin" class="flex justify-between text-sm font-bold text-gray-700 mb-2">
                            <span>Catatan Admin</span>
                            <span x-cloak x-show="isCatatanWajib"
                                  x-transition.opacity
                                  class="text-xs text-red-500 font-bold bg-red-50 px-2 py-0.5 rounded-md border border-red-100">
                                Wajib diisi!
                            </span>
                        </label>
                        <textarea id="catatan_admin" name="catatan_admin" rows="5"
                                  maxlength="2000"
                                  :required="isCatatanWajib"
                                  :placeholder="isCatatanWajib ? 'Catatan wajib diisi untuk status ini...' : 'Tambahkan keterangan untuk mahasiswa... (opsional)'"
                                  class="w-full px-4 py-3 bg-gray-50 border rounded-xl text-sm font-medium text-gray-800 focus:ring-4 focus:ring-blue-500/20 focus:border-polmed-blue focus:bg-white outline-none transition-all resize-none
                                         {{ $errors->has('catatan_admin') ? 'border-red-400 bg-red-50' : 'border-gray-200' }}">{{ old('catatan_admin', $pengaduan->catatan_admin) }}</textarea>
                    </div>

                    <button type="submit"
                            class="w-full py-3.5 bg-polmed-blue hover:bg-blue-800 text-white font-bold rounded-xl text-sm shadow-md shadow-blue-900/20 transition-all focus:ring-4 focus:ring-blue-500/30">
                        Simpan Perubahan
                    </button>
                </form>
                @endif

            </div>
        </div>

    </div>
</div>
@endsection
