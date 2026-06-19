<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Urutan penting: kategori harus ada sebelum pengaduan bisa dibuat
        $this->call([
            KategoriPengaduanSeeder::class,
            UserSeeder::class,
        ]);
    }
}
