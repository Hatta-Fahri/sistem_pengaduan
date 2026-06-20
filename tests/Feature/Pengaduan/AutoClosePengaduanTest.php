<?php

namespace Tests\Feature\Pengaduan;

use App\Models\KategoriPengaduan;
use App\Models\Pengaduan;
use App\Models\User;
use Database\Seeders\KategoriPengaduanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AutoClosePengaduanTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(KategoriPengaduanSeeder::class);
    }

    protected function buatPengaduan(User $mahasiswa, string $status): Pengaduan
    {
        return Pengaduan::create([
            'user_id'          => $mahasiswa->id,
            'kategori_id'      => KategoriPengaduan::first()->id,
            'tanggal_kejadian' => now()->subDay(),
            'subjek'           => 'Subjek pengaduan uji coba auto-close',
            'isi_pengaduan'    => 'Isi pengaduan uji coba untuk pengujian auto-close.',
            'status'           => $status,
        ]);
    }

    public function test_auto_close_menutup_pengaduan_yang_sudah_lewat_sla(): void
    {
        Mail::fake();

        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
        $pengaduan = $this->buatPengaduan($mahasiswa, Pengaduan::STATUS_MENUNGGU_KONFIRMASI);
        $pengaduan->timestamps = false;
        $pengaduan->updated_at = now()->subDays(Pengaduan::SLA_HARI + 1);
        $pengaduan->save();

        $this->artisan('pengaduan:auto-close')->assertExitCode(0);

        $this->assertDatabaseHas('pengaduan', [
            'id'     => $pengaduan->id,
            'status' => Pengaduan::STATUS_SELESAI,
        ]);
        $this->assertDatabaseHas('status_history', [
            'pengaduan_id' => $pengaduan->id,
            'status_lama'  => Pengaduan::STATUS_MENUNGGU_KONFIRMASI,
            'status_baru'  => Pengaduan::STATUS_SELESAI,
            'changed_by'   => null,
        ]);
    }

    public function test_auto_close_tidak_menutup_pengaduan_yang_belum_lewat_sla(): void
    {
        Mail::fake();

        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
        $pengaduan = $this->buatPengaduan($mahasiswa, Pengaduan::STATUS_MENUNGGU_KONFIRMASI);

        $this->artisan('pengaduan:auto-close')->assertExitCode(0);

        $this->assertDatabaseHas('pengaduan', [
            'id'     => $pengaduan->id,
            'status' => Pengaduan::STATUS_MENUNGGU_KONFIRMASI,
        ]);
    }

    public function test_auto_close_tidak_menyentuh_status_lain(): void
    {
        Mail::fake();

        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
        $pengaduan = $this->buatPengaduan($mahasiswa, Pengaduan::STATUS_DIPROSES);
        $pengaduan->timestamps = false;
        $pengaduan->updated_at = now()->subDays(Pengaduan::SLA_HARI + 5);
        $pengaduan->save();

        $this->artisan('pengaduan:auto-close')->assertExitCode(0);

        $this->assertDatabaseHas('pengaduan', [
            'id'     => $pengaduan->id,
            'status' => Pengaduan::STATUS_DIPROSES,
        ]);
    }
}
