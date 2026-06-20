<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Lampiran bukti yang disertakan admin/mahasiswa pada satu entri riwayat status
     * (misal: foto bukti perbaikan, dokumen balasan klarifikasi).
     */
    public function up(): void
    {
        Schema::table('status_history', function (Blueprint $table) {
            $table->string('bukti')->nullable()->after('catatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('status_history', function (Blueprint $table) {
            $table->dropColumn('bukti');
        });
    }
};
