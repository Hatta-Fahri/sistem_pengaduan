<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Tampilkan halaman registrasi mahasiswa.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Proses registrasi mahasiswa baru.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'nim'      => ['required', 'string', 'max:20', 'unique:users,nim'],
            'class'    => ['required', 'string', 'max:50'],
            'email'    => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ], [
            'name.required'     => 'Nama lengkap wajib diisi.',
            'nim.required'      => 'NIM wajib diisi.',
            'nim.unique'        => 'NIM sudah terdaftar. Silakan hubungi admin jika terjadi kesalahan.',
            'class.required'    => 'Kelas wajib diisi.',
            'email.required'    => 'Alamat email wajib diisi.',
            'email.unique'      => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.confirmed'=> 'Konfirmasi password tidak cocok.',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'nim'      => $request->nim,
            'class'    => $request->class,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'role'     => 'mahasiswa', // Registrasi publik hanya untuk mahasiswa
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Redirect ke halaman verifikasi email setelah mendaftar
        return redirect()->route('verification.notice');
    }
}
