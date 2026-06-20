<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Tutup otomatis pengaduan yang sudah lewat SLA di status menunggu_konfirmasi_mahasiswa.
// Butuh cron `* * * * * php artisan schedule:run` berjalan di server (atau `schedule:work` saat development).
Schedule::command('pengaduan:auto-close')->daily();
