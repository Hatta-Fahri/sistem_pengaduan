<?php

namespace Tests\Feature\Pengaduan;

use App\Mail\StatusDiperbarui;
use App\Models\KategoriPengaduan;
use App\Models\Pengaduan;
use App\Models\User;
use Database\Seeders\KategoriPengaduanSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class AdminPengaduanTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(KategoriPengaduanSeeder::class);
    }

    protected function buatPengaduan(User $mahasiswa): Pengaduan
    {
        return Pengaduan::create([
            'user_id'          => $mahasiswa->id,
            'kategori_id'      => KategoriPengaduan::first()->id,
            'tanggal_kejadian' => now()->subDay(),
            'subjek'           => 'Subjek pengaduan uji coba admin',
            'isi_pengaduan'    => 'Isi pengaduan uji coba untuk pengujian fitur admin pada sistem SILPM.',
            'status'           => Pengaduan::STATUS_MENUNGGU,
        ]);
    }

    public function test_admin_bisa_lihat_semua_pengaduan(): void
    {
        $admin      = User::factory()->create(['role' => 'admin']);
        $mahasiswaA = User::factory()->create(['role' => 'mahasiswa']);
        $mahasiswaB = User::factory()->create(['role' => 'mahasiswa']);

        $pengaduanA = $this->buatPengaduan($mahasiswaA);
        $pengaduanB = $this->buatPengaduan($mahasiswaB);

        $response = $this->actingAs($admin)->get(route('admin.pengaduan.index'));

        $response->assertOk();
        $response->assertSee($pengaduanA->subjek);
        $response->assertSee($pengaduanB->subjek);
    }

    public function test_admin_bisa_update_status_pengaduan(): void
    {
        Mail::fake();

        $admin     = User::factory()->create(['role' => 'admin']);
        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
        $pengaduan = $this->buatPengaduan($mahasiswa);

        $response = $this->actingAs($admin)->patch(
            route('admin.pengaduan.update-status', $pengaduan),
            ['status' => Pengaduan::STATUS_DIPROSES]
        );

        $response->assertRedirect(route('admin.pengaduan.show', $pengaduan));
        $this->assertDatabaseHas('pengaduan', [
            'id'     => $pengaduan->id,
            'status' => Pengaduan::STATUS_DIPROSES,
        ]);
    }

    public function test_update_status_mencatat_status_history(): void
    {
        Mail::fake();

        $admin     = User::factory()->create(['role' => 'admin']);
        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
        $pengaduan = $this->buatPengaduan($mahasiswa);

        $this->actingAs($admin)->patch(
            route('admin.pengaduan.update-status', $pengaduan),
            ['status' => Pengaduan::STATUS_DIPROSES, 'catatan_admin' => 'Sedang ditindaklanjuti.']
        );

        $this->assertDatabaseHas('status_history', [
            'pengaduan_id' => $pengaduan->id,
            'status_lama'  => Pengaduan::STATUS_MENUNGGU,
            'status_baru'  => Pengaduan::STATUS_DIPROSES,
            'changed_by'   => $admin->id,
        ]);
    }

    public function test_update_status_ditolak_wajib_ada_catatan(): void
    {
        $admin     = User::factory()->create(['role' => 'admin']);
        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
        $pengaduan = $this->buatPengaduan($mahasiswa);

        $response = $this->actingAs($admin)->patch(
            route('admin.pengaduan.update-status', $pengaduan),
            ['status' => Pengaduan::STATUS_DITOLAK]
        );

        $response->assertSessionHasErrors('catatan_admin');
        $this->assertDatabaseHas('pengaduan', [
            'id'     => $pengaduan->id,
            'status' => Pengaduan::STATUS_MENUNGGU,
        ]);
    }

    public function test_email_terkirim_saat_status_diperbarui(): void
    {
        Mail::fake();

        $admin     = User::factory()->create(['role' => 'admin']);
        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
        $pengaduan = $this->buatPengaduan($mahasiswa);

        $this->actingAs($admin)->patch(
            route('admin.pengaduan.update-status', $pengaduan),
            ['status' => Pengaduan::STATUS_SELESAI, 'catatan_admin' => 'Selesai ditangani.']
        );

        Mail::assertQueued(StatusDiperbarui::class);
    }
}
