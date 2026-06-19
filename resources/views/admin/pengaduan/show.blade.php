@extends('layouts.admin')
@section('title', 'Detail Pengaduan #' . $pengaduan->id)
@section('content')

@php
    $badgeClass = [
        'menunggu_verifikasi'           => 'bg-gray-100 text-gray-700',
        'sedang_diproses'               => 'bg-blue-100 text-blue-700',
        'membutuhkan_informasi_tambahan'=> 'bg-yellow-100 text-yellow-700',
        'selesai_ditangani'             => 'bg-green-100 text-green-700',
        'ditolak'                       => 'bg-red-100 text-red-700',
    ];
    $dotClass = [
        'menunggu_verifikasi'           => 'bg-gray-400',
        'sedang_diproses'               => 'bg-blue-500',
        'membutuhkan_informasi_tambahan'=> 'bg-yellow-500',
        'selesai_ditangani'             => 'bg-green-500',
        'ditolak'                       => 'bg-red-500',
    ];
    $currentBadge = $badgeClass[$pengaduan->status] ?? 'bg-gray-100 text-gray-700';
    $statusList = [
        \App\Models\Pengaduan::STATUS_MENUNGGU,
        \App\Models\Pengaduan::STATUS_DIPROSES,
        \App\Models\Pengaduan::STATUS_BUTUH_INFO,
        \App\Models\Pengaduan::STATUS_SELESAI,
        \App\Models\Pengaduan::STATUS_DITOLAK,
    ];
    // Status yang wajib catatan
    $wajibCatatan = [
        \App\Models\Pengaduan::STATUS_DITOLAK,
        \App\Models\Pengaduan::STATUS_BUTUH_INFO,
    ];
@endphp

<div class="max-w-5xl mx-auto space-y-6">

    {{-- Breadcrumb --}}
    <nav class="flex text-sm text-gray-400 gap-1 items-center">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
        <span>/</span>
        <a href="{{ route('admin.pengaduan.index') }}" class="hover:text-blue-600">Manajemen Pengaduan</a>
        <span>/</span>
        <span class="text-gray-600">Detail #{{ $pengaduan->id }}</span>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- ===== KOLOM KIRI: Detail + Timeline ===== --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Kartu Utama Pengaduan --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <div class="flex items-start justify-between gap-4 mb-5">
                    <h1 class="text-lg font-bold text-gray-900 leading-snug flex-1 min-w-0">{{ $pengaduan->subjek }}</h1>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold flex-shrink-0
                                 {{ $currentBadge }}">
                        {{ $statusLabels[$pengaduan->status] ?? $pengaduan->status }}
                    </span>
                </div>

                {{-- Identitas Pelapor --}}
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 mb-5">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Identitas Pelapor</p>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-sm">
                        <div>
                            <p class="text-gray-400 text-xs mb-0.5">Nama</p>
                            <p class="font-semibold text-gray-800">{{ $pengaduan->user->name }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 text-xs mb-0.5">NIM</p>
                            <p class="font-medium text-gray-700">{{ $pengaduan->user->nim }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 text-xs mb-0.5">Kelas</p>
                            <p class="font-medium text-gray-700">{{ $pengaduan->user->class }}</p>
                        </div>
                        <div>
                            <p class="text-gray-400 text-xs mb-0.5">Email</p>
                            <p class="font-medium text-gray-700 break-all">{{ $pengaduan->user->email }}</p>
                        </div>
                    </div>
                </div>

                {{-- Meta Info Pengaduan --}}
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-4 py-4 border-t border-b border-gray-100 text-sm mb-5">
                    <div>
                        <p class="text-gray-400 text-xs mb-0.5">Kategori</p>
                        <p class="font-medium text-gray-700">{{ $pengaduan->kategori->nama_kategori }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs mb-0.5">Tanggal Kejadian</p>
                        <p class="font-medium text-gray-700">{{ $pengaduan->tanggal_kejadian->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs mb-0.5">Tanggal Masuk</p>
                        <p class="font-medium text-gray-700">{{ $pengaduan->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>

                {{-- Isi Pengaduan --}}
                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">Isi Pengaduan</p>
                <div class="bg-gray-50 rounded-xl p-4 text-sm text-gray-700 leading-relaxed whitespace-pre-wrap border border-gray-100">{{ $pengaduan->isi_pengaduan }}</div>
            </div>

            {{-- ===== Timeline Riwayat Status (ASC) ===== --}}
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <h2 class="font-semibold text-gray-800 mb-6">Riwayat Status</h2>

                @if ($pengaduan->statusHistory->isEmpty())
                    <p class="text-sm text-gray-400 text-center py-4">Belum ada riwayat status.</p>
                @else
                    <div class="space-y-0">
                        @foreach ($pengaduan->statusHistory as $history)
                        @php
                            $hBadge  = $badgeClass[$history->status_baru] ?? 'bg-gray-100 text-gray-700';
                            $hDot    = $dotClass[$history->status_baru] ?? 'bg-gray-400';
                            $hLabel  = $statusLabels[$history->status_baru] ?? $history->status_baru;
                            $hLama   = $history->status_lama
                                ? ($statusLabels[$history->status_lama] ?? $history->status_lama)
                                : null;
                        @endphp
                        <div class="flex gap-4">
                            <div class="flex flex-col items-center w-4 flex-shrink-0">
                                <div class="w-3.5 h-3.5 rounded-full {{ $hDot }} ring-2 ring-white flex-shrink-0 mt-0.5"></div>
                                @if (!$loop->last)
                                    <div class="w-0.5 flex-1 bg-gray-200 my-1"></div>
                                @endif
                            </div>
                            <div class="pb-6 flex-1 min-w-0">
                                <div class="flex items-start justify-between gap-2 flex-wrap">
                                    <div class="flex items-center gap-2 flex-wrap">
                                        @if ($hLama)
                                            <span class="text-xs text-gray-400">{{ $hLama }}</span>
                                            <span class="text-gray-300">→</span>
                                        @endif
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold {{ $hBadge }}">
                                            {{ $hLabel }}
                                        </span>
                                    </div>
                                    <span class="text-xs text-gray-400 whitespace-nowrap">
                                        {{ $history->created_at->format('d M Y, H:i') }}
                                    </span>
                                </div>
                                @if ($history->catatan)
                                    <p class="text-sm text-gray-600 mt-1.5 leading-relaxed">{{ $history->catatan }}</p>
                                @endif
                                <p class="text-xs text-gray-400 mt-1">
                                    Oleh: {{ $history->changedBy->name }}
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        {{-- ===== KOLOM KANAN: Form Update Status (Sticky) ===== --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6 sticky top-6">
                <h2 class="font-semibold text-gray-800 mb-1">Update Status</h2>
                <p class="text-xs text-gray-400 mb-5">Admin hanya dapat mengubah status dan catatan — bukan isi pengaduan.</p>

                {{-- Tampilkan error validasi --}}
                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4">
                        @foreach ($errors->all() as $error)
                            <p class="text-xs text-red-700">• {{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form id="form-update-status"
                      method="POST"
                      action="{{ route('admin.pengaduan.update-status', $pengaduan) }}">
                    @csrf
                    @method('PATCH')

                    {{-- Pilih Status Baru --}}
                    <div class="mb-4">
                        <label for="status" class="block text-xs font-semibold text-gray-600 mb-1.5">
                            Status Baru <span class="text-red-500">*</span>
                        </label>
                        <select id="status" name="status" required
                                onchange="toggleCatatanRequired(this.value)"
                                class="w-full px-3 py-2.5 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition
                                       {{ $errors->has('status') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                            <option value="">-- Pilih Status --</option>
                            @foreach ($statusLabels as $key => $label)
                                <option value="{{ $key }}"
                                        data-wajib-catatan="{{ in_array($key, $wajibCatatan) ? '1' : '0' }}"
                                        {{ old('status', $pengaduan->status) === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Catatan Admin --}}
                    <div class="mb-5">
                        <label for="catatan_admin" class="block text-xs font-semibold text-gray-600 mb-1.5">
                            Catatan Admin
                            <span id="catatan-required-badge"
                                  class="hidden ml-1 text-red-500">*wajib</span>
                        </label>
                        <textarea id="catatan_admin" name="catatan_admin" rows="5"
                                  maxlength="2000"
                                  placeholder="Tambahkan keterangan untuk mahasiswa... (wajib diisi jika status Ditolak atau Membutuhkan Informasi Tambahan)"
                                  class="w-full px-3 py-2.5 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition resize-none
                                         {{ $errors->has('catatan_admin') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">{{ old('catatan_admin', $pengaduan->catatan_admin) }}</textarea>
                        <p class="text-xs text-gray-400 mt-1">Catatan ini ditampilkan kepada mahasiswa.</p>
                    </div>

                    <button type="submit" id="btn-update"
                            class="w-full py-2.5 bg-blue-700 hover:bg-blue-800 text-white font-semibold rounded-lg text-sm transition focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Simpan Perubahan Status
                    </button>
                </form>

                <div class="mt-4 pt-4 border-t border-gray-100 text-center">
                    <a href="{{ route('admin.pengaduan.index') }}"
                       class="text-sm text-gray-400 hover:text-gray-600 transition">
                        ← Kembali ke Daftar
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- JavaScript untuk validasi catatan kondisional --}}
<script>
    // Status yang wajib catatan admin
    const statusWajibCatatan = @json($wajibCatatan);

    function toggleCatatanRequired(selectedStatus) {
        const badge     = document.getElementById('catatan-required-badge');
        const textarea  = document.getElementById('catatan_admin');

        if (statusWajibCatatan.includes(selectedStatus)) {
            badge.classList.remove('hidden');
            textarea.setAttribute('required', 'required');
            textarea.placeholder = 'Catatan wajib diisi untuk status ini...';
        } else {
            badge.classList.add('hidden');
            textarea.removeAttribute('required');
            textarea.placeholder = 'Tambahkan keterangan untuk mahasiswa... (opsional)';
        }
    }

    // Inisialisasi saat halaman load
    const selectStatus = document.getElementById('status');
    if (selectStatus && selectStatus.value) {
        toggleCatatanRequired(selectStatus.value);
    }
</script>
@endsection
