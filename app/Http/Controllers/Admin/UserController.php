<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * Daftar seluruh mahasiswa terdaftar.
     */
    public function index(Request $request): View
    {
        $users = User::where('role', 'mahasiswa')
            ->withCount('pengaduan')
            ->orderBy('name')
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Tampilkan profil mahasiswa beserta statistik dan riwayat pengaduannya.
     */
    public function show(User $user): View
    {
        abort_unless($user->isMahasiswa(), 404);

        $statusLabels = Pengaduan::statusLabels();

        $stats = [
            'total' => $user->pengaduan()->count(),
        ];
        foreach ($statusLabels as $key => $label) {
            $stats[$key] = $user->pengaduan()->byStatus($key)->count();
        }

        $riwayatPengaduan = $user->pengaduan()
            ->with('kategori')
            ->orderByDesc('created_at')
            ->get();

        return view('admin.users.show', compact('user', 'stats', 'riwayatPengaduan', 'statusLabels'));
    }

    /**
     * Nonaktifkan akun mahasiswa — bukan hapus permanen.
     */
    public function destroy(User $user): RedirectResponse
    {
        abort_unless($user->isMahasiswa(), 404);

        $user->update(['is_active' => false]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Akun mahasiswa "' . $user->name . '" berhasil dinonaktifkan.');
    }
}
