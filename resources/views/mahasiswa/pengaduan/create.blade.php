@extends('layouts.mahasiswa')
@section('title', 'Buat Pengaduan Baru')
@section('content')

<div class="max-w-4xl mx-auto space-y-6">

    {{-- ===== Breadcrumb ===== --}}
    <nav class="flex text-sm text-gray-500 gap-2 items-center font-medium mb-4">
        <a href="{{ route('mahasiswa.dashboard') }}" class="hover:text-polmed-blue transition-colors flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
            Dashboard
        </a>
        <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <a href="{{ route('mahasiswa.pengaduan.index') }}" class="hover:text-polmed-blue transition-colors">Pengaduan Saya</a>
        <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <span class="text-polmed-blue font-bold">Buat Baru</span>
    </nav>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden relative">
        <!-- Decorative Top Border -->
          <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-polmed-blue to-blue-400"></div>
        
        <div class="p-6 sm:p-8">
            <div class="flex items-start gap-4 mb-8 pb-6 border-b border-gray-100">
                <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-polmed-blue flex-shrink-0 ring-1 ring-blue-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </div>
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Formulir Pengaduan</h2>
                    <p class="text-sm text-gray-500 font-medium mt-1">Sampaikan keluhan, kritik, atau saran Anda. Laporan akan diteruskan ke admin untuk ditindaklanjuti.</p>
                </div>
            </div>

            {{-- Tampilkan error validasi umum --}}
            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-8">
                    <div class="flex items-center gap-2 text-red-800 font-bold text-sm mb-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Mohon periksa kembali input Anda:
                    </div>
                    <ul class="list-disc list-inside text-xs font-medium text-red-700 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('mahasiswa.pengaduan.store') }}" class="space-y-6" enctype="multipart/form-data">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Kategori --}}
                    <div>
                        <label for="kategori_id" class="block text-sm font-bold text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <select id="kategori_id" name="kategori_id" required
                                    class="appearance-none w-full px-4 py-3.5 bg-gray-50 border rounded-xl text-sm font-semibold text-gray-800 focus:ring-4 focus:ring-blue-500/20 focus:border-polmed-blue focus:bg-white outline-none transition-all cursor-pointer {{ $errors->has('kategori_id') ? 'border-red-400' : 'border-gray-200' }}">
                                <option value="">Pilih Kategori</option>
                                @foreach ($kategoriList as $kat)
                                    <option value="{{ $kat->id }}" {{ old('kategori_id') == $kat->id ? 'selected' : '' }}>
                                        {{ $kat->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-500">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </div>
                    </div>

                    {{-- Tanggal Kejadian --}}
                    <div>
                        <label for="tanggal_kejadian" class="block text-sm font-bold text-gray-700 mb-2">Waktu Kejadian <span class="text-red-500">*</span></label>
                        <input id="tanggal_kejadian" name="tanggal_kejadian" type="datetime-local" required
                               value="{{ old('tanggal_kejadian') }}"
                               class="w-full px-4 py-3 bg-gray-50 border rounded-xl text-sm font-semibold text-gray-800 focus:ring-4 focus:ring-blue-500/20 focus:border-polmed-blue focus:bg-white outline-none transition-all {{ $errors->has('tanggal_kejadian') ? 'border-red-400' : 'border-gray-200' }}" />
                    </div>
                </div>

                {{-- Subjek --}}
                <div>
                    <label for="subjek" class="block text-sm font-bold text-gray-700 mb-2">Subjek / Judul Pengaduan <span class="text-red-500">*</span></label>
                    <input id="subjek" name="subjek" type="text" required maxlength="100"
                           value="{{ old('subjek') }}"
                           class="w-full px-4 py-3.5 bg-gray-50 border rounded-xl text-sm font-semibold text-gray-800 focus:ring-4 focus:ring-blue-500/20 focus:border-polmed-blue focus:bg-white outline-none transition-all {{ $errors->has('subjek') ? 'border-red-400' : 'border-gray-200' }}"
                           placeholder="Contoh: Proyektor di Ruangan N-210 Rusak" />
                    <p class="text-xs text-gray-400 font-medium mt-1.5 flex justify-end">Maksimal 100 karakter</p>
                </div>

                {{-- Isi Pengaduan --}}
                <div>
                    <label for="isi_pengaduan" class="block text-sm font-bold text-gray-700 mb-2">Detail Pengaduan <span class="text-red-500">*</span></label>
                    <textarea id="isi_pengaduan" name="isi_pengaduan" rows="8" required
                              class="w-full px-4 py-3.5 bg-gray-50 border rounded-xl text-sm font-medium text-gray-800 leading-relaxed focus:ring-4 focus:ring-blue-500/20 focus:border-polmed-blue focus:bg-white outline-none transition-all resize-none {{ $errors->has('isi_pengaduan') ? 'border-red-400' : 'border-gray-200' }}"
                              placeholder="Ceritakan detail kejadian secara jelas dan objektif...">{{ old('isi_pengaduan') }}</textarea>
                </div>

                {{-- Bukti Pendukung (Opsional) --}}
                <div>
                    <label for="bukti" class="block text-sm font-bold text-gray-700 mb-2">Bukti Pendukung <span class="text-gray-400 font-medium">(opsional)</span></label>
                    <input id="bukti" name="bukti" type="file" accept=".jpg,.jpeg,.png,.pdf"
                           class="w-full text-sm font-medium text-gray-600 border rounded-xl bg-gray-50 cursor-pointer focus:ring-4 focus:ring-blue-500/20 focus:border-polmed-blue transition-all
                                  file:mr-4 file:py-3.5 file:px-4 file:border-0 file:font-bold file:text-sm file:bg-polmed-blue file:text-white hover:file:bg-blue-800 file:cursor-pointer file:transition-colors
                                  {{ $errors->has('bukti') ? 'border-red-400' : 'border-gray-200' }}" />
                    <p class="text-xs text-gray-400 font-medium mt-1.5">Unggah foto atau dokumen PDF sebagai bukti (JPG, PNG, PDF — maksimal 5MB).</p>
                </div>

                {{-- Opsi Anonim --}}
                <div class="bg-blue-50/50 border border-blue-100 rounded-xl p-4 flex items-start gap-3">
                    <input id="is_anonymous" name="is_anonymous" type="checkbox" value="1" {{ old('is_anonymous') ? 'checked' : '' }}
                           class="mt-1 w-4 h-4 rounded border-gray-300 text-polmed-blue focus:ring-4 focus:ring-blue-500/20 cursor-pointer" />
                    <label for="is_anonymous" class="text-sm cursor-pointer">
                        <span class="font-bold text-gray-900 block">Ajukan secara anonim</span>
                        <span class="text-xs text-gray-500 leading-relaxed">Identitas Anda (nama, NIM, kelas, email) akan disembunyikan dari admin saat meninjau pengaduan ini. Notifikasi status tetap dikirim ke email Anda seperti biasa.</span>
                    </label>
                </div>

                <div class="pt-6 mt-6 border-t border-gray-100 flex flex-col-reverse sm:flex-row items-center justify-end gap-3">
                    <a href="{{ route('mahasiswa.pengaduan.index') }}"
                       class="w-full sm:w-auto text-center px-6 py-3.5 bg-white border border-gray-200 text-gray-500 hover:text-gray-800 hover:border-gray-300 font-semibold rounded-xl transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                            class="w-full sm:w-auto px-8 py-3.5 bg-[#2b4cba] hover:bg-[#2441a1] text-white font-bold rounded-xl shadow-lg shadow-blue-900/25 transition-all hover:-translate-y-0.5 focus:ring-4 focus:ring-blue-500/30 flex justify-center items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        Kirim Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
