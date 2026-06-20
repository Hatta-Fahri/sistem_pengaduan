@extends('layouts.admin')
@section('title', 'Manajemen Pengguna')
@section('content')

<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Manajemen Mahasiswa</h1>
            <p class="text-sm text-gray-500 mt-1 font-medium">Kelola akun mahasiswa yang terdaftar di dalam sistem SILPM.</p>
        </div>
        <div>
            <!-- Kolom Pencarian -->
            <form method="GET" action="{{ route('admin.users.index') }}" class="relative w-full sm:w-72">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" name="search" value="{{ request('search') }}"
                       class="w-full pl-10 pr-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl text-sm font-medium focus:ring-4 focus:ring-blue-500/20 focus:border-polmed-blue focus:bg-white transition-all placeholder-gray-400"
                       placeholder="Cari nama, NIM, atau email...">
            </form>
        </div>
    </div>

    <!-- Flash Messages -->
    @if (session('success'))
        <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.500ms
             class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 flex items-start gap-4 shadow-sm relative">
            <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <div class="flex-1">
                <h3 class="text-sm font-bold text-emerald-800">Berhasil!</h3>
                <p class="text-emerald-700 text-sm mt-0.5">{{ session('success') }}</p>
            </div>
            <button @click="show = false" class="text-emerald-500 hover:bg-emerald-100 rounded-lg p-1.5 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        </div>
    @endif
    @if (session('error'))
        <div x-data="{ show: true }" x-show="show" x-transition.opacity.duration.500ms
             class="bg-red-50 border border-red-200 rounded-xl p-4 flex items-start gap-4 shadow-sm relative">
            <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center flex-shrink-0">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div class="flex-1">
                <h3 class="text-sm font-bold text-red-800">Gagal!</h3>
                <p class="text-red-700 text-sm mt-0.5">{{ session('error') }}</p>
            </div>
            <button @click="show = false" class="text-red-500 hover:bg-red-100 rounded-lg p-1.5 transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        </div>
    @endif

    <!-- Tabel Pengguna -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-gray-50/80 border-b border-gray-200 text-gray-500 text-xs uppercase tracking-wider font-bold">
                    <tr>
                        <th scope="col" class="px-6 py-4 w-12 text-center">No</th>
                        <th scope="col" class="px-6 py-4">Mahasiswa</th>
                        <th scope="col" class="px-6 py-4">NIM & Kelas</th>
                        <th scope="col" class="px-6 py-4 text-center">Total Pengaduan</th>
                        <th scope="col" class="px-6 py-4">Bergabung Pada</th>
                        <th scope="col" class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 bg-white">
                    @forelse ($users as $index => $user)
                    <tr class="hover:bg-gray-50/80 transition-colors group">
                        <td class="px-6 py-4 text-center text-gray-400 text-xs font-bold">
                            {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-3">
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-polmed-blue text-white flex items-center justify-center font-bold ring-2 ring-white shadow-sm">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="text-gray-900 font-bold group-hover:text-polmed-blue transition-colors">{{ $user->name }}</div>
                                    <div class="text-xs font-medium text-gray-500 mt-0.5">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-gray-900 font-bold">{{ $user->nim ?? '-' }}</div>
                            <div class="text-xs font-medium text-gray-500 mt-0.5">{{ $user->class ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 text-center whitespace-nowrap">
                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-blue-50 text-polmed-blue font-bold border border-blue-100">
                                {{ $user->pengaduan_count ?? 0 }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500 font-medium">
                            {{ $user->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-center whitespace-nowrap">
                            <div class="flex items-center justify-center gap-2">
                                <!-- Tombol Hapus dengan Konfirmasi Modal AlpineJS -->
                                <div x-data="{ open: false }">
                                    <button @click="open = true" 
                                            class="inline-flex items-center justify-center p-2 bg-white border border-red-200 hover:bg-red-50 hover:border-red-300 hover:text-red-600 text-gray-500 rounded-lg transition-all shadow-sm focus:ring-4 focus:ring-red-500/20"
                                            title="Hapus Akun">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                    
                                    <!-- Modal Konfirmasi Hapus -->
                                    <div x-cloak x-show="open" class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                        <div x-show="open" x-transition.opacity class="fixed inset-0 bg-gray-900 bg-opacity-75 transition-opacity backdrop-blur-sm"></div>
                                        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
                                            <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                                <div x-show="open" 
                                                     x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                                     x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                                     @click.away="open = false"
                                                     class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg border border-gray-100">
                                                    <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                                                        <div class="sm:flex sm:items-start">
                                                            <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
                                                                <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                                            </div>
                                                            <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                                                                <h3 class="text-lg font-bold leading-6 text-gray-900" id="modal-title">Hapus Akun Mahasiswa</h3>
                                                                <div class="mt-2">
                                                                    <p class="text-sm text-gray-500 font-medium">Apakah Anda yakin ingin menghapus akun <b>{{ $user->name }}</b>? Semua data terkait (termasuk riwayat pengaduan) akan ikut terhapus secara permanen dan tidak dapat dikembalikan.</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 border-t border-gray-100">
                                                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="inline-flex w-full justify-center rounded-xl bg-red-600 px-3 py-2 text-sm font-bold text-white shadow-sm hover:bg-red-500 sm:ml-3 sm:w-auto transition-colors focus:ring-4 focus:ring-red-500/30">
                                                                Ya, Hapus Permanen
                                                            </button>
                                                        </form>
                                                        <button @click="open = false" type="button" class="mt-3 inline-flex w-full justify-center rounded-xl bg-white px-3 py-2 text-sm font-bold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto transition-colors">
                                                            Batal
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-24 text-center">
                            <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900">Tidak ada data mahasiswa</h3>
                            <p class="text-sm text-gray-500 mt-1">Sistem belum memiliki akun mahasiswa yang terdaftar atau data yang dicari tidak ditemukan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        @if ($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>

@endsection
