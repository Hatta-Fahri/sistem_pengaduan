<?php

namespace Tests\Feature\Admin;

use App\Models\KategoriPengaduan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class KategoriPengaduanTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_bisa_lihat_daftar_kategori(): void
    {
        $admin    = User::factory()->create(['role' => 'admin']);
        $kategori = KategoriPengaduan::create(['nama_kategori' => 'Layanan Akademik', 'is_active' => true]);

        $response = $this->actingAs($admin)->get(route('admin.kategori.index'));

        $response->assertOk();
        $response->assertSee($kategori->nama_kategori);
    }

    public function test_admin_bisa_tambah_kategori_baru(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.kategori.store'), [
            'nama_kategori' => 'Layanan Perpustakaan',
            'deskripsi'     => 'Pengaduan terkait layanan perpustakaan kampus.',
        ]);

        $response->assertRedirect(route('admin.kategori.index'));
        $this->assertDatabaseHas('kategori_pengaduan', [
            'nama_kategori' => 'Layanan Perpustakaan',
            'is_active'     => true,
        ]);
    }

    public function test_nama_kategori_wajib_unik(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        KategoriPengaduan::create(['nama_kategori' => 'Layanan Akademik', 'is_active' => true]);

        $response = $this->actingAs($admin)->post(route('admin.kategori.store'), [
            'nama_kategori' => 'Layanan Akademik',
        ]);

        $response->assertSessionHasErrors('nama_kategori');
    }

    public function test_admin_bisa_edit_kategori(): void
    {
        $admin    = User::factory()->create(['role' => 'admin']);
        $kategori = KategoriPengaduan::create(['nama_kategori' => 'Layanan Lama', 'is_active' => true]);

        $response = $this->actingAs($admin)->put(route('admin.kategori.update', $kategori), [
            'nama_kategori' => 'Layanan Baru',
            'deskripsi'     => 'Deskripsi diperbarui.',
        ]);

        $response->assertRedirect(route('admin.kategori.index'));
        $this->assertDatabaseHas('kategori_pengaduan', [
            'id'            => $kategori->id,
            'nama_kategori' => 'Layanan Baru',
        ]);
    }

    public function test_admin_bisa_nonaktifkan_dan_aktifkan_kembali_kategori(): void
    {
        $admin    = User::factory()->create(['role' => 'admin']);
        $kategori = KategoriPengaduan::create(['nama_kategori' => 'Layanan Akademik', 'is_active' => true]);

        $this->actingAs($admin)->patch(route('admin.kategori.toggle-active', $kategori));
        $this->assertDatabaseHas('kategori_pengaduan', ['id' => $kategori->id, 'is_active' => false]);

        $this->actingAs($admin)->patch(route('admin.kategori.toggle-active', $kategori));
        $this->assertDatabaseHas('kategori_pengaduan', ['id' => $kategori->id, 'is_active' => true]);
    }

    public function test_mahasiswa_tidak_bisa_akses_kelola_kategori(): void
    {
        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);

        $response = $this->actingAs($mahasiswa)->get(route('admin.kategori.index'));

        $response->assertRedirect(route('mahasiswa.dashboard'));
    }

    public function test_kategori_nonaktif_tidak_muncul_di_form_pengaduan_mahasiswa(): void
    {
        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
        KategoriPengaduan::create(['nama_kategori' => 'Kategori Aktif', 'is_active' => true]);
        KategoriPengaduan::create(['nama_kategori' => 'Kategori Nonaktif', 'is_active' => false]);

        $response = $this->actingAs($mahasiswa)->get(route('mahasiswa.pengaduan.create'));

        $response->assertSee('Kategori Aktif');
        $response->assertDontSee('Kategori Nonaktif');
    }
}
