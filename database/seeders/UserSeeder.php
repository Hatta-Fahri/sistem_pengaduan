<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Seed akun admin dan kaprodi.
     *
     * Email & password dibaca dari .env agar kredensial asli tidak
     * ter-commit ke repository publik. Lihat .env.example untuk
     * daftar variabel yang perlu diisi.
     */
    public function run(): void
    {
        // Akun Admin — gunakan firstOrCreate agar tidak error jika sudah ada
        User::firstOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@silpm.local')],
            [
                'name'     => env('ADMIN_NAME', 'Administrator MI'),
                'nim'      => null,
                'class'    => null,
                'password' => Hash::make(env('ADMIN_PASSWORD', 'password')),
                'role'     => 'admin',
            ]
        );

        // Akun Kaprodi
        User::firstOrCreate(
            ['email' => env('KAPRODI_EMAIL', 'kaprodi@silpm.local')],
            [
                'name'     => env('KAPRODI_NAME', 'Kaprodi MI'),
                'nim'      => null,
                'class'    => null,
                'password' => Hash::make(env('KAPRODI_PASSWORD', 'password')),
                'role'     => 'kaprodi',
            ]
        );
    }
}
