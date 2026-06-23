@extends('layouts.admin')
@section('title', 'Manajemen Pengguna')
@section('content')

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <div>
            <h1 class="text-2xl font-extrabold text-gray-900 tracking-tight">Manajemen Mahasiswa</h1>
            <p class="text-sm text-gray-500 mt-1 font-medium">Kelola akun mahasiswa yang terdaftar di dalam sistem SILPM.</p>
        </div>
        <div>
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
                        <th scope="col" class="px-6 py-4 text-center">Status</th>
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
                                <div class="flex-shrink-0 w-10 h-10 rounded-full bg-[#2b4cba]/10 border border-[#2b4cba]/20 flex items-center justify-center ring-2 ring-[#2b4cba]/10">
                                    <svg class="w-5 h-5 text-[#2b4cba]/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-gray-900 font-bold group-hover:text-[#2b4cba] transition-colors">{{ $user->name }}</div>
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
                            @if ($user->isActive())
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 ring-1 ring-emerald-200">Aktif</span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-red-50 text-red-600 ring-1 ring-red-200">Diblokir</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center whitespace-nowrap">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.users.show', $user) }}"
                                   class="inline-flex items-center justify-center px-3 py-2 bg-white border border-gray-200 hover:border-polmed-blue hover:text-polmed-blue text-gray-700 rounded-lg text-xs font-bold transition-all shadow-sm">
                                    Detail
                                </a>
                                <form method="POST" action="{{ route('admin.users.toggle-active', $user) }}">
                                    @csrf
                                    @method('PATCH')
                                    @if ($user->isActive())
                                        <button type="submit"
                                                onclick="return confirm('Yakin ingin menonaktifkan pengguna ini?')"
                                                class="inline-flex items-center justify-center px-3 py-2 bg-white border border-red-200 hover:bg-red-50 text-red-600 rounded-lg text-xs font-bold transition-all shadow-sm">
                                            Nonaktifkan
                                        </button>
                                    @else
                                        <button type="submit"
                                                onclick="return confirm('Yakin ingin mengaktifkan pengguna ini?')"
                                                class="inline-flex items-center justify-center px-3 py-2 bg-white border border-emerald-200 hover:bg-emerald-50 text-emerald-700 rounded-lg text-xs font-bold transition-all shadow-sm">
                                            Aktifkan
                                        </button>
                                    @endif
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-24 text-center">
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
        
        @if ($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</div>

@endsection