<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Seed akun admin, kaprodi, dan mahasiswa dummy untuk testing.
     */
    public function run(): void
    {
        // Akun Admin — gunakan firstOrCreate agar tidak error jika sudah ada
        User::firstOrCreate(
            ['email' => 'adminprodimi@gmail.com'],
            [
                'name'     => 'Administrator MI',
                'nim'      => null,
                'class'    => null,
                'password' => Hash::make('password'),
                'role'     => 'admin',
            ]
        );

        // Akun Kaprodi
        User::firstOrCreate(
            ['email' => 'kaprodimipolmed@gmail.com'],
            [
                'name'     => 'Kaprodi MI',
                'nim'      => null,
                'class'    => null,
                'password' => Hash::make('password'),
                'role'     => 'kaprodi',
            ]
        );
    }
}
