<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * changed_by nullable agar perubahan status otomatis oleh sistem (auto-close)
     * bisa tercatat tanpa mengatasnamakan admin/mahasiswa yang tidak benar-benar bertindak.
     */
    public function up(): void
    {
        Schema::table('status_history', function (Blueprint $table) {
            $table->foreignId('changed_by')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('status_history', function (Blueprint $table) {
            $table->foreignId('changed_by')->nullable(false)->change();
        });
    }
};
