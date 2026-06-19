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
            'name'     => 'Administrator SILPM',
            'nim'      => null,
            'class'    => null,
            'email'    => 'admin@silpm.local',
            'password' => Hash::make('Admin123!'),
            'role'     => 'admin',
        ]);

        // Mahasiswa dummy 1
        User::create([
            'name'     => 'Budi Santoso',
            'nim'      => '2305001',
            'class'    => 'MI-4A',  
            'email'    => 'mahasiswa1@silpm.local',
            'password' => Hash::make('Test123!'),
            'role'     => 'mahasiswa',
        ]);

        // Mahasiswa dummy 2
        User::create([
            'name'     => 'Sari Dewi',
            'nim'      => '2305002',
            'class'    => 'MI-4B',
            'email'    => 'mahasiswa2@silpm.local',
            'password' => Hash::make('Test123!'),
            'role'     => 'mahasiswa',
        ]);

        // Mahasiswa dummy 3
        User::create([
            'name'     => 'Ahmad Fauzi',
            'nim'      => '2305003',
            'class'    => 'MI-4A',
            'email'    => 'mahasiswa3@silpm.local',
            'password' => Hash::make('Test123!'),
            'role'     => 'mahasiswa',
        ]);
    }
}
