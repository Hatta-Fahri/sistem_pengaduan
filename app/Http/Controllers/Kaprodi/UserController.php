<?php

namespace App\Http\Controllers\Kaprodi;

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

        return view('kaprodi.users.index', compact('users'));
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

        return view('kaprodi.users.show', compact('user', 'stats', 'riwayatPengaduan', 'statusLabels'));
    }

    /**
     * Nonaktifkan akun mahasiswa (diblokir, tidak bisa login lagi) atau aktifkan
     * kembali — tidak ada penghapusan data permanen sama sekali.
     */
    public function toggleActive(User $user): RedirectResponse
    {
        abort_unless($user->isMahasiswa(), 404);

        $user->update(['is_active' => ! $user->is_active]);

        $pesan = $user->is_active
            ? 'Akun mahasiswa "' . $user->name . '" berhasil diaktifkan kembali.'
            : 'Akun mahasiswa "' . $user->name . '" berhasil dinonaktifkan. Mahasiswa ini tidak akan bisa login lagi sampai diaktifkan kembali.';

        return redirect()->back()->with('success', $pesan);
    }
}
