<?php

namespace Tests\Feature\Pengaduan;

use App\Models\KategoriPengaduan;
use App\Models\Pengaduan;
use App\Models\StatusHistory;
use App\Models\User;
use Database\Seeders\KategoriPengaduanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LampiranBalasanTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(KategoriPengaduanSeeder::class);
        Storage::fake('local');
    }

    protected function buatPengaduan(User $mahasiswa, string $status): Pengaduan
    {
        return Pengaduan::create([
            'user_id'          => $mahasiswa->id,
            'kategori_id'      => KategoriPengaduan::first()->id,
            'tanggal_kejadian' => now()->subDay(),
            'subjek'           => 'Subjek pengaduan uji coba lampiran',
            'isi_pengaduan'    => 'Isi pengaduan uji coba untuk pengujian lampiran & balasan terstruktur.',
            'status'           => $status,
        ]);
    }

    public function test_admin_bisa_lampirkan_bukti_saat_update_status(): void
    {
        Mail::fake();

        $admin     = User::factory()->create(['role' => 'admin']);
        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
        $pengaduan = $this->buatPengaduan($mahasiswa, Pengaduan::STATUS_MENUNGGU);

        $this->actingAs($admin)->patch(route('admin.pengaduan.update-status', $pengaduan), [
            'status'        => Pengaduan::STATUS_DIPROSES,
            'catatan_admin' => 'Sudah ditindaklanjuti, lihat lampiran.',
            'bukti_admin'   => UploadedFile::fake()->image('bukti-admin.jpg'),
        ]);

        $history = StatusHistory::where('pengaduan_id', $pengaduan->id)->latest('id')->first();
        $this->assertNotNull($history->bukti);
        Storage::disk('local')->assertExists($history->bukti);
    }

    public function test_mahasiswa_bisa_balas_informasi_tambahan(): void
    {
        Mail::fake();

        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
        $pengaduan = $this->buatPengaduan($mahasiswa, Pengaduan::STATUS_BUTUH_INFO);

        $response = $this->actingAs($mahasiswa)->patch(route('mahasiswa.pengaduan.balas-informasi', $pengaduan), [
            'balasan' => 'Berikut tambahan informasi yang diminta admin.',
            'bukti'   => UploadedFile::fake()->image('balasan.jpg'),
        ]);

        $response->assertRedirect(route('mahasiswa.pengaduan.show', $pengaduan));
        $this->assertDatabaseHas('pengaduan', [
            'id'     => $pengaduan->id,
            'status' => Pengaduan::STATUS_DIPROSES,
        ]);

        $history = StatusHistory::where('pengaduan_id', $pengaduan->id)->latest('id')->first();
        $this->assertSame(Pengaduan::STATUS_BUTUH_INFO, $history->status_lama);
        $this->assertSame(Pengaduan::STATUS_DIPROSES, $history->status_baru);
        $this->assertSame('Berikut tambahan informasi yang diminta admin.', $history->catatan);
        $this->assertNotNull($history->bukti);
    }

    public function test_balasan_informasi_wajib_diisi(): void
    {
        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
        $pengaduan = $this->buatPengaduan($mahasiswa, Pengaduan::STATUS_BUTUH_INFO);

        $response = $this->actingAs($mahasiswa)->patch(route('mahasiswa.pengaduan.balas-informasi', $pengaduan), [
            'balasan' => '',
        ]);

        $response->assertSessionHasErrors('balasan');
        $this->assertDatabaseHas('pengaduan', [
            'id'     => $pengaduan->id,
            'status' => Pengaduan::STATUS_BUTUH_INFO,
        ]);
    }

    public function test_mahasiswa_tidak_bisa_balas_jika_status_bukan_butuh_info(): void
    {
        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
        $pengaduan = $this->buatPengaduan($mahasiswa, Pengaduan::STATUS_DIPROSES);

        $response = $this->actingAs($mahasiswa)->patch(route('mahasiswa.pengaduan.balas-informasi', $pengaduan), [
            'balasan' => 'Mencoba membalas padahal status tidak meminta informasi.',
        ]);

        $response->assertForbidden();
    }

    public function test_mahasiswa_tidak_bisa_balas_pengaduan_milik_orang_lain(): void
    {
        $mahasiswaA = User::factory()->create(['role' => 'mahasiswa']);
        $mahasiswaB = User::factory()->create(['role' => 'mahasiswa']);
        $pengaduan  = $this->buatPengaduan($mahasiswaA, Pengaduan::STATUS_BUTUH_INFO);

        $response = $this->actingAs($mahasiswaB)->patch(route('mahasiswa.pengaduan.balas-informasi', $pengaduan), [
            'balasan' => 'Mencoba membalas pengaduan orang lain.',
        ]);

        $response->assertForbidden();
    }
}
