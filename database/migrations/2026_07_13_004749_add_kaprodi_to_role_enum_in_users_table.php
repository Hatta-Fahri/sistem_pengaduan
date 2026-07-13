<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        /*
         * Raw SQL is utilized here because Laravel's Schema Builder (via Doctrine DBAL) 
         * often encounters inconsistencies when directly mutating ENUM column definitions.
         * Appending 'kaprodi' at the end of the ENUM list ensures existing index mapping 
         * (1 for mahasiswa, 2 for admin) remains intact, preventing data corruption.
         */
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('mahasiswa', 'admin', 'kaprodi') NOT NULL DEFAULT 'mahasiswa'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        /*
         * Reverts the column to its original state to maintain migration idempotency.
         * Note: Before executing a rollback, the application logic must ensure 
         * no user records hold the 'kaprodi' role to avoid SQL truncation errors.
         */
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('mahasiswa', 'admin') NOT NULL DEFAULT 'mahasiswa'");
    }
};