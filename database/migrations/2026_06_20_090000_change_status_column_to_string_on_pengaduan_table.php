<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Ubah kolom status dari enum DB menjadi string bebas.
     * Status baru "menunggu_konfirmasi_mahasiswa" perlu ditambahkan, dan validasi
     * nilai yang diizinkan sudah dijaga di level aplikasi (Rule::in di FormRequest),
     * jadi tidak perlu lagi menambah/menghapus value enum di DB setiap kali alur status berubah.
     */
    public function up(): void
    {
        Schema::table('pengaduan', function (Blueprint $table) {
            $table->string('status', 50)->default('menunggu_verifikasi')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengaduan', function (Blueprint $table) {
            $table->enum('status', [
                'menunggu_verifikasi',
                'sedang_diproses',
                'membutuhkan_informasi_tambahan',
                'selesai_ditangani',
                'ditolak',
            ])->default('menunggu_verifikasi')->change();
        });
    }
};
