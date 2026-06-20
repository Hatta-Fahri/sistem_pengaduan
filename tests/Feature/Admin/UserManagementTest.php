<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_bisa_nonaktifkan_akun_mahasiswa(): void
    {
        $admin     = User::factory()->create(['role' => 'admin']);
        $mahasiswa = User::factory()->create(['role' => 'mahasiswa', 'is_active' => true]);

        $response = $this->actingAs($admin)->patch(route('admin.users.toggle-active', $mahasiswa));

        $response->assertRedirect(route('admin.users.index'));
        $this->assertDatabaseHas('users', ['id' => $mahasiswa->id, 'is_active' => false]);
        // Pastikan akun tidak terhapus, hanya dinonaktifkan.
        $this->assertDatabaseHas('users', ['id' => $mahasiswa->id]);
    }

    public function test_admin_bisa_aktifkan_kembali_akun_yang_dinonaktifkan(): void
    {
        $admin     = User::factory()->create(['role' => 'admin']);
        $mahasiswa = User::factory()->create(['role' => 'mahasiswa', 'is_active' => false]);

        $this->actingAs($admin)->patch(route('admin.users.toggle-active', $mahasiswa));

        $this->assertDatabaseHas('users', ['id' => $mahasiswa->id, 'is_active' => true]);
    }

    public function test_akun_yang_dinonaktifkan_tidak_bisa_login(): void
    {
        $mahasiswa = User::factory()->create([
            'role'      => 'mahasiswa',
            'is_active' => false,
            'password'  => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'email'    => $mahasiswa->email,
            'password' => 'password',
        ]);

        // Login berhasil secara kredensial, tapi langsung dipaksa logout oleh EnsureRole
        // begitu mengakses halaman terproteksi berikutnya.
        $followUp = $this->actingAs($mahasiswa)->get(route('mahasiswa.dashboard'));
        $followUp->assertRedirect(route('login'));
        $this->assertGuest();
    }

    public function test_mahasiswa_tidak_bisa_akses_toggle_active(): void
    {
        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
        $target    = User::factory()->create(['role' => 'mahasiswa']);

        $response = $this->actingAs($mahasiswa)->patch(route('admin.users.toggle-active', $target));

        $response->assertRedirect(route('mahasiswa.dashboard'));
    }
}
