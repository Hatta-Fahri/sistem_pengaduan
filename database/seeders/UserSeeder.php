<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Seed akun admin dan mahasiswa dummy untuk testing.
     */
    public function run(): void
    {
        // Akun Admin
        User::create([
            'name'     => 'Administrator',
            'nim'      => null,
            'class'    => null,
            'email'    => 'adminprodimi@gmail.com',
            'password' => Hash::make('password'),
            'role'     => 'admin',
        ]);
    }
}
