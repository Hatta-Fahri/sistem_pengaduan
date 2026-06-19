<?php

namespace Tests\Feature\Pengaduan;

use App\Models\KategoriPengaduan;
use App\Models\Pengaduan;
use App\Models\User;
use Database\Seeders\KategoriPengaduanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class MahasiswaPengaduanTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(KategoriPengaduanSeeder::class);
    }

    protected function dataPengaduanValid(): array
    {
        return [
            'kategori_id'      => KategoriPengaduan::first()->id,
            'tanggal_kejadian' => now()->subDay()->format('Y-m-d'),
            'subjek'           => 'Keluhan terkait fasilitas laboratorium komputer',
            'isi_pengaduan'    => 'AC di ruang laboratorium komputer lantai 2 tidak berfungsi sejak minggu lalu sehingga ruangan terasa sangat panas.',
        ];
    }

    public function test_mahasiswa_bisa_buat_pengaduan(): void
    {
        Mail::fake();

        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);

        $response = $this->actingAs($mahasiswa)
            ->post(route('mahasiswa.pengaduan.store'), $this->dataPengaduanValid());

        $pengaduan = Pengaduan::first();

        $response->assertRedirect(route('mahasiswa.pengaduan.show', $pengaduan));
        $this->assertDatabaseHas('pengaduan', [
            'user_id' => $mahasiswa->id,
            'status'  => Pengaduan::STATUS_MENUNGGU,
        ]);
    }

    public function test_mahasiswa_tidak_bisa_lihat_pengaduan_mahasiswa_lain(): void
    {
        Mail::fake();

        $mahasiswaA = User::factory()->create(['role' => 'mahasiswa']);
        $mahasiswaB = User::factory()->create(['role' => 'mahasiswa']);

        $this->actingAs($mahasiswaA)
            ->post(route('mahasiswa.pengaduan.store'), $this->dataPengaduanValid());

        $pengaduan = Pengaduan::first();

        $response = $this->actingAs($mahasiswaB)
            ->get(route('mahasiswa.pengaduan.show', $pengaduan));

        $response->assertForbidden();
    }

    public function test_pengaduan_tersimpan_dengan_status_menunggu_verifikasi(): void
    {
        Mail::fake();

        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);

        $this->actingAs($mahasiswa)
            ->post(route('mahasiswa.pengaduan.store'), $this->dataPengaduanValid());

        $this->assertSame(Pengaduan::STATUS_MENUNGGU, Pengaduan::first()->status);
    }

    public function test_status_history_tercatat_saat_pengaduan_dibuat(): void
    {
        Mail::fake();

        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);

        $this->actingAs($mahasiswa)
            ->post(route('mahasiswa.pengaduan.store'), $this->dataPengaduanValid());

        $pengaduan = Pengaduan::first();

        $this->assertDatabaseHas('status_history', [
            'pengaduan_id' => $pengaduan->id,
            'status_lama'  => null,
            'status_baru'  => Pengaduan::STATUS_MENUNGGU,
            'changed_by'   => $mahasiswa->id,
        ]);
    }
}
