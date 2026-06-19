@extends('layouts.mahasiswa')

@section('title', 'Buat Pengaduan Baru')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
    <!-- Header -->
    <div>
        <nav class="flex text-sm text-gray-400 mb-2 gap-1 items-center">
            <a href="{{ route('mahasiswa.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
            <span>/</span>
            <a href="{{ route('mahasiswa.pengaduan.index') }}" class="hover:text-blue-600">Pengaduan Saya</a>
            <span>/</span>
            <span class="text-gray-600">Buat Baru</span>
        </nav>
        <h1 class="text-xl font-bold text-gray-900">Buat Pengaduan Baru</h1>
        <p class="text-sm text-gray-500 mt-1">Isi formulir di bawah ini dengan informasi yang lengkap dan jelas.</p>
    </div>

    <form id="form-pengaduan" method="POST" action="{{ route('mahasiswa.pengaduan.store') }}"
          class="bg-white rounded-2xl border border-gray-200 p-8 space-y-6">
        @csrf

        <!-- Identitas Mahasiswa (Auto-fill, Read-only) -->
        <div class="bg-blue-50 rounded-xl p-5 border border-blue-100">
            <p class="text-xs font-semibold text-blue-600 uppercase tracking-wide mb-3">Identitas Pelapor</p>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-sm">
                <div>
                    <p class="text-gray-500 text-xs mb-1">Nama Lengkap</p>
                    <p class="font-medium text-gray-800">{{ $user->name }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs mb-1">NIM</p>
                    <p class="font-medium text-gray-800">{{ $user->nim }}</p>
                </div>
                <div>
                    <p class="text-gray-500 text-xs mb-1">Kelas</p>
                    <p class="font-medium text-gray-800">{{ $user->class }}</p>
                </div>
            </div>
        </div>

        <!-- Kategori Pengaduan -->
        <div>
            <label for="kategori_id" class="block text-sm font-medium text-gray-700 mb-1">
                Kategori Pengaduan <span class="text-red-500">*</span>
            </label>
            <select id="kategori_id" name="kategori_id" required
                    class="w-full px-4 py-2.5 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition
                           {{ $errors->has('kategori_id') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}">
                <option value="">-- Pilih Kategori --</option>
                @foreach ($kategori as $kat)
                    <option value="{{ $kat->id }}" {{ old('kategori_id') == $kat->id ? 'selected' : '' }}>
                        {{ $kat->nama_kategori }}
                    </option>
                @endforeach
            </select>
            @error('kategori_id')
                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Tanggal Kejadian -->
        <div>
            <label for="tanggal_kejadian" class="block text-sm font-medium text-gray-700 mb-1">
                Tanggal & Waktu Kejadian <span class="text-red-500">*</span>
            </label>
            <input id="tanggal_kejadian" name="tanggal_kejadian" type="datetime-local" required
                   max="{{ now()->format('Y-m-d\TH:i') }}"
                   value="{{ old('tanggal_kejadian') }}"
                   class="w-full px-4 py-2.5 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition
                          {{ $errors->has('tanggal_kejadian') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}" />
            @error('tanggal_kejadian')
                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Subjek -->
        <div>
            <label for="subjek" class="block text-sm font-medium text-gray-700 mb-1">
                Subjek Pengaduan <span class="text-red-500">*</span>
            </label>
            <input id="subjek" name="subjek" type="text" required
                   value="{{ old('subjek') }}"
                   maxlength="255"
                   class="w-full px-4 py-2.5 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition
                          {{ $errors->has('subjek') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                   placeholder="Ringkasan singkat mengenai pengaduan Anda (min. 10 karakter)" />
            @error('subjek')
                <p class="mt-1.5 text-xs text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <!-- Isi Pengaduan -->
        <div>
            <label for="isi_pengaduan" class="block text-sm font-medium text-gray-700 mb-1">
                Isi Pengaduan <span class="text-red-500">*</span>
            </label>
            <textarea id="isi_pengaduan" name="isi_pengaduan" rows="7" required
                      maxlength="5000"
                      class="w-full px-4 py-2.5 border rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition resize-none
                             {{ $errors->has('isi_pengaduan') ? 'border-red-400 bg-red-50' : 'border-gray-300' }}"
                      placeholder="Jelaskan pengaduan Anda secara lengkap dan rinci. Sertakan waktu, tempat, dan pihak yang terlibat. (min. 30 karakter)">{{ old('isi_pengaduan') }}</textarea>
            <div class="flex justify-between mt-1">
                @error('isi_pengaduan')
                    <p class="text-xs text-red-600">{{ $message }}</p>
                @else
                    <p class="text-xs text-gray-400">Minimal 30 karakter</p>
                @enderror
                <p class="text-xs text-gray-400" id="char-count">0 / 5000</p>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="flex items-center justify-between pt-2 border-t border-gray-100">
            <a href="{{ route('mahasiswa.pengaduan.index') }}"
               class="px-5 py-2.5 border border-gray-300 text-gray-600 hover:bg-gray-50 rounded-lg text-sm font-medium transition">
                Batal
            </a>
            <button type="submit" id="btn-submit"
                    class="px-6 py-2.5 bg-blue-700 hover:bg-blue-800 text-white font-semibold rounded-lg text-sm transition focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                Kirim Pengaduan
            </button>
        </div>
    </form>
</div>

<script>
    // Counter karakter untuk textarea
    const textarea = document.getElementById('isi_pengaduan');
    const charCount = document.getElementById('char-count');
    function updateCount() {
        charCount.textContent = textarea.value.length + ' / 5000';
    }
    textarea.addEventListener('input', updateCount);
    updateCount();
</script>
@endsection
