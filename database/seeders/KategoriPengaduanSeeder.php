<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriPengaduanSeeder extends Seeder
{
    /**
     * Seed 7 kategori pengaduan sesuai AGENT.md.
     */
    public function run(): void
    {
        $kategori = [
            [
                'nama_kategori' => 'Layanan Dosen Pengampu Mata Kuliah',
                'deskripsi'     => 'Pengaduan terkait layanan dosen dalam perkuliahan, termasuk kehadiran, materi, dan penilaian.',
                'is_active'     => true,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'nama_kategori' => 'Layanan Dosen Wali Kelas',
                'deskripsi'     => 'Pengaduan terkait bimbingan dan layanan dosen wali kelas mahasiswa.',
                'is_active'     => true,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'nama_kategori' => 'Layanan Program Studi',
                'deskripsi'     => 'Pengaduan terkait kebijakan, kurikulum, dan layanan administrasi program studi.',
                'is_active'     => true,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'nama_kategori' => 'Layanan Laboratorium',
                'deskripsi'     => 'Pengaduan terkait fasilitas, peralatan, dan layanan laboratorium komputer.',
                'is_active'     => true,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'nama_kategori' => 'Layanan Sarana dan Prasarana',
                'deskripsi'     => 'Pengaduan terkait kondisi ruang kelas, fasilitas umum, dan infrastruktur kampus.',
                'is_active'     => true,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'nama_kategori' => 'Layanan Administrasi',
                'deskripsi'     => 'Pengaduan terkait layanan administrasi akademik, surat-menyurat, dan dokumen resmi.',
                'is_active'     => true,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
            [
                'nama_kategori' => 'Lainnya',
                'deskripsi'     => 'Pengaduan lain yang tidak termasuk dalam kategori di atas.',
                'is_active'     => true,
                'created_at'    => now(),
                'updated_at'    => now(),
            ],
        ];

        DB::table('kategori_pengaduan')->insert($kategori);
    }
}
