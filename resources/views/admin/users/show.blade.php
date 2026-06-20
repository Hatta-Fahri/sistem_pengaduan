@extends('layouts.admin')
@section('title', 'Profil Mahasiswa')
@section('content')

@php
    $badgeClass = [
        'menunggu_verifikasi'           => 'bg-gray-100 text-gray-700',
        'sedang_diproses'               => 'bg-blue-100 text-blue-700',
        'membutuhkan_informasi_tambahan'=> 'bg-yellow-100 text-yellow-700',
        'selesai_ditangani'             => 'bg-green-100 text-green-700',
        'ditolak'                       => 'bg-red-100 text-red-700',
    ];
@endphp

<div class="max-w-5xl mx-auto space-y-6">

    {{-- Breadcrumb --}}
    <nav class="flex text-sm text-gray-400 gap-1 items-center">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-blue-600">Dashboard</a>
        <span>/</span>
        <a href="{{ route('admin.users.index') }}" class="hover:text-blue-600">Kelola Pengguna</a>
        <span>/</span>
        <span class="text-gray-600">{{ $user->name }}</span>
    </nav>

    {{-- ===== Profil Mahasiswa ===== --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
        <div class="flex items-start justify-between gap-4 mb-5">
            <h1 class="text-lg font-bold text-gray-900">{{ $user->name }}</h1>
            @if ($user->isActive())
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-700">
                    Akun Aktif
                </span>
            @else
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-red-100 text-red-700">
                    Akun Nonaktif
                </span>
            @endif
        </div>

        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm">
            <div>
                <p class="text-gray-400 text-xs mb-0.5">NIM</p>
                <p class="font-medium text-gray-700">{{ $user->nim }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs mb-0.5">Kelas</p>
                <p class="font-medium text-gray-700">{{ $user->class }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs mb-0.5">Email</p>
                <p class="font-medium text-gray-700 break-all">{{ $user->email }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs mb-0.5">Tanggal Daftar</p>
                <p class="font-medium text-gray-700">{{ $user->created_at->format('d M Y') }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.users.toggle-active', $user) }}" class="mt-5"
              onsubmit="return confirm('{{ $user->isActive() ? 'Nonaktifkan akun ' . $user->name . '? Mahasiswa ini tidak akan bisa login lagi sampai diaktifkan kembali.' : 'Aktifkan kembali akun ' . $user->name . '?' }}');">
            @csrf
            @method('PATCH')
            @if ($user->isActive())
                <button type="submit"
                        class="px-4 py-2 bg-red-50 hover:bg-red-100 text-red-700 rounded-lg text-sm font-semibold transition">
                    Nonaktifkan Akun (Blokir)
                </button>
            @else
                <button type="submit"
                        class="px-4 py-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-lg text-sm font-semibold transition">
                    Aktifkan Kembali
                </button>
            @endif
        </form>
    </div>

    {{-- ===== Statistik Pengaduan ===== --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        @php
            $statCards = [
                ['label' => 'Total',          'value' => $stats['total']],
                ['label' => 'Menunggu',       'value' => $stats['menunggu_verifikasi']],
                ['label' => 'Diproses',       'value' => $stats['sedang_diproses']],
                ['label' => 'Butuh Info',     'value' => $stats['membutuhkan_informasi_tambahan']],
                ['label' => 'Selesai',        'value' => $stats['selesai_ditangani']],
                ['label' => 'Ditolak',        'value' => $stats['ditolak']],
            ];
        @endphp
        @foreach ($statCards as $card)
        <div class="rounded-xl border border-gray-200 bg-white p-4 text-center shadow-sm">
            <p class="text-2xl font-bold text-gray-800">{{ $card['value'] }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $card['label'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- ===== Riwayat Pengaduan ===== --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-800">Riwayat Pengaduan</h2>
        </div>

        @if ($riwayatPengaduan->isEmpty())
            <div class="px-6 py-10 text-center text-gray-400 text-sm">
                Mahasiswa ini belum pernah mengajukan pengaduan.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 border-b border-gray-100">
                        <tr>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Subjek</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Kategori</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Status</th>
                            <th class="text-left px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Tanggal</th>
                            <th class="text-right px-5 py-3 text-xs font-semibold text-gray-500 uppercase tracking-wide">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach ($riwayatPengaduan as $p)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3.5">
                                <p class="font-medium text-gray-800 line-clamp-1 max-w-xs">{{ $p->subjek }}</p>
                            </td>
                            <td class="px-5 py-3.5 text-gray-500 text-xs whitespace-nowrap">
                                {{ $p->kategori->nama_kategori }}
                            </td>
                            <td class="px-5 py-3.5 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold
                                             {{ $badgeClass[$p->status] ?? 'bg-gray-100 text-gray-700' }}">
                                    {{ $statusLabels[$p->status] ?? $p->status }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-xs text-gray-400 whitespace-nowrap">
                                {{ $p->created_at->format('d M Y') }}
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <a href="{{ route('admin.pengaduan.show', $p) }}"
                                   class="text-blue-600 hover:text-blue-800 text-xs font-semibold">
                                    Kelola →
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
@endsection
