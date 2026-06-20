<?php

namespace Tests\Feature\Pengaduan;

use App\Models\KategoriPengaduan;
use App\Models\Pengaduan;
use App\Models\User;
use Database\Seeders\KategoriPengaduanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class KonfirmasiPenyelesaianTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(KategoriPengaduanSeeder::class);
    }

    protected function buatPengaduanMenungguKonfirmasi(User $mahasiswa): Pengaduan
    {
        return Pengaduan::create([
            'user_id'          => $mahasiswa->id,
            'kategori_id'      => KategoriPengaduan::first()->id,
            'tanggal_kejadian' => now()->subDay(),
            'subjek'           => 'Subjek pengaduan uji coba konfirmasi',
            'isi_pengaduan'    => 'Isi pengaduan uji coba untuk pengujian alur konfirmasi penyelesaian.',
            'status'           => Pengaduan::STATUS_MENUNGGU_KONFIRMASI,
        ]);
    }

    public function test_mahasiswa_bisa_konfirmasi_selesai(): void
    {
        Mail::fake();

        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
        $pengaduan = $this->buatPengaduanMenungguKonfirmasi($mahasiswa);

        $response = $this->actingAs($mahasiswa)->patch(
            route('mahasiswa.pengaduan.konfirmasi-selesai', $pengaduan)
        );

        $response->assertRedirect(route('mahasiswa.pengaduan.show', $pengaduan));
        $this->assertDatabaseHas('pengaduan', [
            'id'     => $pengaduan->id,
            'status' => Pengaduan::STATUS_SELESAI,
        ]);
        $this->assertDatabaseHas('status_history', [
            'pengaduan_id' => $pengaduan->id,
            'status_lama'  => Pengaduan::STATUS_MENUNGGU_KONFIRMASI,
            'status_baru'  => Pengaduan::STATUS_SELESAI,
            'changed_by'   => $mahasiswa->id,
        ]);
    }

    public function test_mahasiswa_bisa_tolak_konfirmasi_dengan_alasan(): void
    {
        Mail::fake();

        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
        $admin     = User::factory()->create(['role' => 'admin']);
        $pengaduan = $this->buatPengaduanMenungguKonfirmasi($mahasiswa);

        $response = $this->actingAs($mahasiswa)->patch(
            route('mahasiswa.pengaduan.tolak-konfirmasi', $pengaduan),
            ['alasan' => 'Masalah AC masih belum diperbaiki sampai sekarang.']
        );

        $response->assertRedirect(route('mahasiswa.pengaduan.show', $pengaduan));
        $this->assertDatabaseHas('pengaduan', [
            'id'     => $pengaduan->id,
            'status' => Pengaduan::STATUS_DIPROSES,
        ]);
        $this->assertDatabaseHas('status_history', [
            'pengaduan_id' => $pengaduan->id,
            'status_lama'  => Pengaduan::STATUS_MENUNGGU_KONFIRMASI,
            'status_baru'  => Pengaduan::STATUS_DIPROSES,
            'catatan'      => 'Masalah AC masih belum diperbaiki sampai sekarang.',
            'changed_by'   => $mahasiswa->id,
        ]);
    }

    public function test_tolak_konfirmasi_wajib_ada_alasan(): void
    {
        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
        $pengaduan = $this->buatPengaduanMenungguKonfirmasi($mahasiswa);

        $response = $this->actingAs($mahasiswa)->patch(
            route('mahasiswa.pengaduan.tolak-konfirmasi', $pengaduan),
            ['alasan' => '']
        );

        $response->assertSessionHasErrors('alasan');
        $this->assertDatabaseHas('pengaduan', [
            'id'     => $pengaduan->id,
            'status' => Pengaduan::STATUS_MENUNGGU_KONFIRMASI,
        ]);
    }

    public function test_mahasiswa_tidak_bisa_konfirmasi_pengaduan_milik_orang_lain(): void
    {
        $mahasiswaA = User::factory()->create(['role' => 'mahasiswa']);
        $mahasiswaB = User::factory()->create(['role' => 'mahasiswa']);
        $pengaduan  = $this->buatPengaduanMenungguKonfirmasi($mahasiswaA);

        $response = $this->actingAs($mahasiswaB)->patch(
            route('mahasiswa.pengaduan.konfirmasi-selesai', $pengaduan)
        );

        $response->assertForbidden();
    }

    public function test_mahasiswa_tidak_bisa_konfirmasi_jika_status_belum_menunggu_konfirmasi(): void
    {
        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
        $pengaduan = $this->buatPengaduanMenungguKonfirmasi($mahasiswa);
        $pengaduan->update(['status' => Pengaduan::STATUS_DIPROSES]);

        $response = $this->actingAs($mahasiswa)->patch(
            route('mahasiswa.pengaduan.konfirmasi-selesai', $pengaduan)
        );

        $response->assertForbidden();
    }
}
