<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Tandai semua akun yang sudah ada sebagai terverifikasi sebelum verifikasi
     * email diaktifkan (MustVerifyEmail). Tanpa ini, akun seed dengan domain
     * palsu (mahasiswa1-3@silpm.local) akan terkunci permanen karena tidak
     * pernah bisa menerima email verifikasi sungguhan.
     */
    public function up(): void
    {
        DB::table('users')
            ->whereNull('email_verified_at')
            ->update(['email_verified_at' => now()]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak ada cara aman untuk membedakan mana yang sebelumnya null —
        // tidak perlu rollback data, hanya hindari error jika migration di-rollback.
    }
};
