<?php

namespace Tests\Feature\Pengaduan;

use App\Models\KategoriPengaduan;
use App\Models\Pengaduan;
use App\Models\User;
use Database\Seeders\KategoriPengaduanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EditPengaduanTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(KategoriPengaduanSeeder::class);
    }

    protected function buatPengaduan(User $mahasiswa, string $status = Pengaduan::STATUS_MENUNGGU): Pengaduan
    {
        return Pengaduan::create([
            'user_id'          => $mahasiswa->id,
            'kategori_id'      => KategoriPengaduan::first()->id,
            'tanggal_kejadian' => now()->subDay(),
            'subjek'           => 'Subjek pengaduan uji coba edit',
            'isi_pengaduan'    => 'Isi pengaduan uji coba untuk pengujian fitur edit pengaduan.',
            'status'           => $status,
        ]);
    }

    public function test_mahasiswa_bisa_edit_pengaduan_saat_masih_menunggu_verifikasi(): void
    {
        Mail::fake();

        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
        $pengaduan = $this->buatPengaduan($mahasiswa);

        $response = $this->actingAs($mahasiswa)->put(route('mahasiswa.pengaduan.update', $pengaduan), [
            'kategori_id'      => KategoriPengaduan::first()->id,
            'tanggal_kejadian' => now()->subDay()->format('Y-m-d'),
            'subjek'           => 'Subjek pengaduan sudah diperbarui',
            'isi_pengaduan'    => 'Isi pengaduan ini sudah diperbarui oleh mahasiswa sebelum diproses admin.',
            'is_anonymous'     => false,
        ]);

        $response->assertRedirect(route('mahasiswa.pengaduan.show', $pengaduan));
        $this->assertDatabaseHas('pengaduan', [
            'id'     => $pengaduan->id,
            'subjek' => 'Subjek pengaduan sudah diperbarui',
        ]);
    }

    public function test_mahasiswa_tidak_bisa_edit_pengaduan_yang_sudah_diproses(): void
    {
        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
        $pengaduan = $this->buatPengaduan($mahasiswa, Pengaduan::STATUS_DIPROSES);

        $response = $this->actingAs($mahasiswa)->get(route('mahasiswa.pengaduan.edit', $pengaduan));
        $response->assertForbidden();

        $response = $this->actingAs($mahasiswa)->put(route('mahasiswa.pengaduan.update', $pengaduan), [
            'kategori_id'      => KategoriPengaduan::first()->id,
            'tanggal_kejadian' => now()->subDay()->format('Y-m-d'),
            'subjek'           => 'Mencoba mengubah subjek',
            'isi_pengaduan'    => 'Mencoba mengubah isi pengaduan padahal sudah diproses admin.',
            'is_anonymous'     => false,
        ]);
        $response->assertForbidden();

        $this->assertDatabaseHas('pengaduan', [
            'id'     => $pengaduan->id,
            'subjek' => 'Subjek pengaduan uji coba edit',
        ]);
    }

    public function test_mahasiswa_tidak_bisa_edit_pengaduan_milik_orang_lain(): void
    {
        $mahasiswaA = User::factory()->create(['role' => 'mahasiswa']);
        $mahasiswaB = User::factory()->create(['role' => 'mahasiswa']);
        $pengaduan  = $this->buatPengaduan($mahasiswaA);

        $response = $this->actingAs($mahasiswaB)->get(route('mahasiswa.pengaduan.edit', $pengaduan));

        $response->assertForbidden();
    }
}
