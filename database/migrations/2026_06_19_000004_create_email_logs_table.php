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
        Schema::create('email_logs', function (Blueprint $table) {
            $table->id();
            $table->string('recipient_email');
            $table->string('subject', 255);
            $table->string('type', 50); // pengaduan_diterima, status_diperbarui, pengaduan_baru_admin
            $table->foreignId('pengaduan_id')->nullable()->constrained('pengaduan')->onDelete('set null');
            $table->enum('status', ['sent', 'failed'])->default('sent');
            $table->timestamp('sent_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_logs');
    }
};
