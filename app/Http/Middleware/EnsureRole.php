<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureRole
{
    /**
     * Pastikan user yang mengakses memiliki role yang diizinkan.
     *
     * Penggunaan di route: ->middleware('role:admin') atau ->middleware('role:mahasiswa')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        // Pastikan user sudah login
        if (! $request->user()) {
            return redirect()->route('login');
        }

        // Akun yang dinonaktifkan admin langsung dipaksa logout
        if (! $request->user()->isActive()) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->with('error', 'Akun Anda telah dinonaktifkan.');
        }

        $userRole = $request->user()->role;

        // Cek apakah role user cocok dengan yang diizinkan
        if (! in_array($userRole, $roles)) {
            // Redirect ke dashboard yang sesuai dengan role user
            if ($userRole === 'admin') {
                return redirect()->route('admin.dashboard')
                    ->with('error', 'Anda tidak memiliki izin untuk mengakses halaman tersebut.');
            }

            return redirect()->route('mahasiswa.dashboard')
                ->with('error', 'Anda tidak memiliki izin untuk mengakses halaman tersebut.');
        }

        return $next($request);
    }
}
