<?php

namespace Database\Seeders;

use App\Models\Pengaduan;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PengaduanDummySeeder extends Seeder
{
    public function run(): void
    {
        // ─── User mahasiswa dummy ───────────────────────────────────────────
        $mahasiswa = User::firstOrCreate(
            ['email' => 'rommygnwan@gmail.com'],
            [
                'name'              => 'Rommy Gunawan',
                'nim'               => '2305102130',
                'class'             => 'MI-6E',
                'password'          => bcrypt('123456789'),
                'role'              => 'mahasiswa',
                'email_verified_at' => now(),
            ]
        );

        // ─── Ambil ID kategori dari DB ──────────────────────────────────────
        // Kategori (sesuai KategoriPengaduanSeeder, sudah di-seed sebelumnya):
        // 1 = Layanan Dosen Pengampu Mata Kuliah
        // 2 = Layanan Dosen Wali Kelas
        // 3 = Layanan Program Studi
        // 4 = Layanan Laboratorium
        // 5 = Layanan Sarana dan Prasarana
        // 6 = Layanan Administrasi
        // 7 = Lainnya
        $kategori = \App\Models\KategoriPengaduan::pluck('id', 'nama_kategori');

        $base   = now()->subDays(28); // mulai dari ~4 minggu lalu
        $userId = $mahasiswa->id;

        // ─── 10 pengaduan dummy ─────────────────────────────────────────────
        $pengaduanData = [
            [
                'kategori'         => 'Layanan Dosen Pengampu Mata Kuliah',
                'tanggal_hari_ke'  => 0,
                'subjek'           => 'Dosen Tidak Hadir Tanpa Pemberitahuan di Kelas N-203',
                'isi'              => 'Dosen pengampu mata kuliah Basis Data tidak hadir pada perkuliahan pukul 08.00 di Ruang N-203 tanpa pemberitahuan sebelumnya. Mahasiswa menunggu lebih dari 1 jam. Ini sudah terjadi dua kali dalam bulan ini.',
                'status'           => 'selesai_ditangani',
                'is_anonymous'     => false,
            ],
            [
                'kategori'         => 'Layanan Laboratorium',
                'tanggal_hari_ke'  => 3,
                'subjek'           => 'Komputer di Lab U-204 Sering Hang saat Praktikum',
                'isi'              => 'Sebanyak 8 unit komputer di Lab Komputer U-204 mengalami masalah hang dan restart sendiri secara acak saat praktikum pemrograman berlangsung. Data kerja mahasiswa hilang sebelum sempat disimpan.',
                'status'           => 'sedang_diproses',
                'is_anonymous'     => false,
            ],
            [
                'kategori'         => 'Layanan Sarana dan Prasarana',
                'tanggal_hari_ke'  => 5,
                'subjek'           => 'AC Ruang Kelas N-109 Rusak Total',
                'isi'              => 'AC di Ruang Kelas N-109 sudah tidak berfungsi sejak dua minggu lalu. Suhu ruangan sangat tinggi sehingga mengganggu konsentrasi belajar, terutama pada siang hari.',
                'status'           => 'menunggu_verifikasi',
                'is_anonymous'     => false,
            ],
            [
                'kategori'         => 'Layanan Administrasi',
                'tanggal_hari_ke'  => 7,
                'subjek'           => 'Nilai UTS Semester Lalu Belum Diinput ke Portal',
                'isi'              => 'Nilai UTS mata kuliah Pemrograman Web semester lalu belum diinput ke portal akademik hingga saat ini. Hal ini berdampak pada perhitungan IPK dan proses pengisian KRS untuk semester berikutnya.',
                'status'           => 'membutuhkan_informasi_tambahan',
                'is_anonymous'     => false,
            ],
            [
                'kategori'         => 'Layanan Dosen Wali Kelas',
                'tanggal_hari_ke'  => 10,
                'subjek'           => 'Dosen Wali Tidak Responsif untuk Bimbingan Akademik',
                'isi'              => 'Sudah lebih dari dua minggu mencoba menghubungi dosen wali via WhatsApp dan email untuk keperluan persetujuan KRS dan bimbingan akademik, namun tidak ada respons sama sekali.',
                'status'           => 'sedang_diproses',
                'is_anonymous'     => false,
            ],
            [
                'kategori'         => 'Layanan Program Studi',
                'tanggal_hari_ke'  => 13,
                'subjek'           => 'Perubahan Kurikulum Tidak Disosialisasikan ke Mahasiswa',
                'isi'              => 'Program studi melakukan perubahan kurikulum yang berdampak pada mahasiswa angkatan 2023 tanpa adanya sosialisasi resmi. Mahasiswa baru mengetahui perubahan ini secara tidak resmi dan bingung dengan mata kuliah yang harus diambil.',
                'status'           => 'menunggu_verifikasi',
                'is_anonymous'     => true,
            ],
            [
                'kategori'         => 'Layanan Dosen Pengampu Mata Kuliah',
                'tanggal_hari_ke'  => 16,
                'subjek'           => 'Soal UAS Tidak Sesuai Materi yang Diajarkan di N-210',
                'isi'              => 'Soal Ujian Akhir Semester mata kuliah Sistem Operasi di Ruang N-210 tidak sesuai dengan materi yang diajarkan selama semester. Banyak soal yang berasal dari topik yang tidak pernah dibahas di kelas.',
                'status'           => 'selesai_ditangani',
                'is_anonymous'     => false,
            ],
            [
                'kategori'         => 'Layanan Laboratorium',
                'tanggal_hari_ke'  => 18,
                'subjek'           => 'Software Cisco Packet Tracer Belum Terinstal di Lab U-304',
                'isi'              => 'Software Cisco Packet Tracer yang wajib digunakan dalam praktikum Jaringan Komputer belum terinstal di Lab U-304 hingga pertemuan ke-4. Mahasiswa terpaksa menggunakan laptop pribadi yang spesifikasinya tidak memadai.',
                'status'           => 'selesai_ditangani',
                'is_anonymous'     => false,
            ],
            [
                'kategori'         => 'Layanan Sarana dan Prasarana',
                'tanggal_hari_ke'  => 21,
                'subjek'           => 'Proyektor Ruang N-209 Tidak Berfungsi Selama 3 Minggu',
                'isi'              => 'Proyektor di Ruang Kelas N-209 sudah rusak dan tidak berfungsi selama lebih dari tiga minggu. Dosen terpaksa mengajar tanpa alat bantu visual sehingga proses pembelajaran menjadi kurang efektif.',
                'status'           => 'sedang_diproses',
                'is_anonymous'     => false,
            ],
            [
                'kategori'         => 'Lainnya',
                'tanggal_hari_ke'  => 25,
                'subjek'           => 'Kursi dan Meja Rusak di Ruang Kelas N-203',
                'isi'              => 'Beberapa kursi dan meja di Ruang Kelas N-203 dalam kondisi rusak, goyah, dan berpotensi membahayakan mahasiswa. Laporan ke petugas sudah dilakukan namun belum ada tindak lanjut hingga lebih dari dua minggu.',
                'status'           => 'menunggu_verifikasi',
                'is_anonymous'     => false,
            ],
        ];

        foreach ($pengaduanData as $item) {
            $tgl = $base->copy()->addDays($item['tanggal_hari_ke'])->setTime(
                rand(7, 15), rand(0, 59)
            );

            $kategoriId = $kategori[$item['kategori']] ?? 7; // fallback ke "Lainnya"

            Pengaduan::create([
                'user_id'          => $userId,
                'is_anonymous'     => $item['is_anonymous'],
                'kategori_id'      => $kategoriId,
                'tanggal_kejadian' => $tgl,
                'subjek'           => $item['subjek'],
                'isi_pengaduan'    => $item['isi'],
                'status'           => $item['status'],
                'catatan_admin'    => null,
                'created_at'       => $tgl,
                'updated_at'       => $tgl,
            ]);
        }

        $this->command->info('✅ 10 pengaduan dummy + 1 user mahasiswa berhasil di-seed.');
    }
}
