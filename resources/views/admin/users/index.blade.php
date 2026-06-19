@extends('layouts.admin')
@section('title', 'Kelola Pengguna')
@section('content')

<div class="space-y-5">

    <div>
        <h1 class="text-xl font-bold text-gray-900">Kelola Pengguna</h1>
        <p class="text-sm text-gray-500 mt-0.5">Daftar seluruh mahasiswa yang terdaftar di SILPM.</p>
    </div>

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">

        @if ($users->isEmpty())
            <div class="px-6 py-14 text-center">
                <p class="text-sm font-medium text-gray-500">Belum ada mahasiswa terdaftar.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Nama</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">NIM</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Kelas</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Email</th>
                            <th class="text-center px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Pengaduan</th>
                            <th class="text-left px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status Akun</th>
                            <th class="text-right px-4 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach ($users as $user)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-4 py-3.5 font-medium text-gray-800">{{ $user->name }}</td>
                            <td class="px-4 py-3.5 text-gray-500 text-xs whitespace-nowrap">{{ $user->nim }}</td>
                            <td class="px-4 py-3.5 text-gray-500 text-xs whitespace-nowrap">{{ $user->class }}</td>
                            <td class="px-4 py-3.5 text-gray-500 text-xs break-all">{{ $user->email }}</td>
                            <td class="px-4 py-3.5 text-center text-gray-700 font-semibold">{{ $user->pengaduan_count }}</td>
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                @if ($user->isActive())
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-green-100 text-green-700">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3.5 text-right whitespace-nowrap">
                                <a href="{{ route('admin.users.show', $user) }}"
                                   class="px-3 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg text-xs font-semibold transition">
                                    Lihat Detail
                                </a>
                                @if ($user->isActive())
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" class="inline"
                                          onsubmit="return confirm('Nonaktifkan akun {{ $user->name }}? Mahasiswa ini tidak akan bisa login lagi.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="px-3 py-1.5 bg-red-50 hover:bg-red-100 text-red-700 rounded-lg text-xs font-semibold transition">
                                            Nonaktifkan
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if ($users->hasPages())
                <div class="px-5 py-4 border-t border-gray-100">
                    {{ $users->links() }}
                </div>
            @endif
        @endif
    </div>

</div>
@endsection
