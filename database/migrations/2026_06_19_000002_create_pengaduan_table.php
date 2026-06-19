<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pengaduan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('kategori_id')->constrained('kategori_pengaduan')->onDelete('restrict');
            $table->dateTime('tanggal_kejadian');
            $table->string('subjek', 255);
            $table->text('isi_pengaduan');
            $table->enum('status', [
                'menunggu_verifikasi',
                'sedang_diproses',
                'membutuhkan_informasi_tambahan',
                'selesai_ditangani',
                'ditolak',
            ])->default('menunggu_verifikasi');
            $table->text('catatan_admin')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaduan');
    }
};
