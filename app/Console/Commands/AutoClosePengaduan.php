<?php

namespace App\Console\Commands;

use App\Services\PengaduanService;
use Illuminate\Console\Command;

class AutoClosePengaduan extends Command
{
    protected $signature = 'pengaduan:auto-close';

    protected $description = 'Tutup otomatis pengaduan yang sudah menunggu konfirmasi mahasiswa lebih dari batas SLA tanpa respons';

    public function handle(PengaduanService $pengaduanService): int
    {
        $jumlah = $pengaduanService->autoCloseStale();

        $this->info("Auto-close selesai: {$jumlah} pengaduan ditutup otomatis.");

        return self::SUCCESS;
    }
}
