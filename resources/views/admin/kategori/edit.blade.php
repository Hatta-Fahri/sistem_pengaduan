@extends('layouts.admin')
@section('title', 'Edit Kategori Pengaduan')
@section('content')

<div class="max-w-2xl mx-auto space-y-6">
    <nav class="flex text-sm text-gray-500 gap-2 items-center font-medium">
        <a href="{{ route('admin.kategori.index') }}" class="hover:text-polmed-blue transition-colors">Kelola Kategori</a>
        <svg class="w-4 h-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <span class="text-polmed-blue font-bold">Edit Kategori</span>
    </nav>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 sm:p-8">
        <h1 class="text-xl font-bold text-gray-900 mb-6">Edit Kategori: {{ $kategori->nama_kategori }}</h1>

        @if ($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                <ul class="list-disc list-inside text-xs font-medium text-red-700 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.kategori.update', $kategori) }}">
            @csrf
            @method('PUT')
            <div class="mb-5">
                <label for="nama_kategori" class="block text-sm font-bold text-gray-700 mb-2">
                    Nama Kategori <span class="text-red-500">*</span>
                </label>
                <input type="text" id="nama_kategori" name="nama_kategori" value="{{ old('nama_kategori', $kategori->nama_kategori) }}" required maxlength="100"
                       class="w-full px-4 py-3 bg-gray-50 border rounded-xl text-sm font-semibold text-gray-800 focus:ring-4 focus:ring-blue-500/20 focus:border-polmed-blue focus:bg-white outline-none transition-all
                              {{ $errors->has('nama_kategori') ? 'border-red-400 bg-red-50' : 'border-gray-200' }}">
            </div>

            <div class="mb-8">
                <label for="deskripsi" class="block text-sm font-bold text-gray-700 mb-2">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="4" maxlength="500"
                          class="w-full px-4 py-3 bg-gray-50 border rounded-xl text-sm font-medium text-gray-800 focus:ring-4 focus:ring-blue-500/20 focus:border-polmed-blue focus:bg-white outline-none transition-all resize-none
                                 {{ $errors->has('deskripsi') ? 'border-red-400 bg-red-50' : 'border-gray-200' }}">{{ old('deskripsi', $kategori->deskripsi) }}</textarea>
            </div>

            <div class="flex items-center gap-3">
                <button type="submit"
                        class="px-6 py-3 bg-polmed-blue hover:bg-blue-800 text-white font-bold rounded-xl text-sm shadow-md shadow-blue-900/20 transition-all">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.kategori.index') }}" class="px-6 py-3 bg-white border-2 border-gray-200 hover:bg-gray-50 text-gray-600 font-bold rounded-xl text-sm transition-all">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>

@endsection
