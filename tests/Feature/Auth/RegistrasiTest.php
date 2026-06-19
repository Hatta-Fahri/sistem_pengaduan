<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrasiTest extends TestCase
{
    use RefreshDatabase;

    protected function dataRegistrasiValid(array $override = []): array
    {
        return array_merge([
            'name'                  => 'Mahasiswa Baru',
            'nim'                   => '2305099',
            'class'                 => 'MI-4A',
            'email'                 => 'mahasiswabaru@silpm.local',
            'password'              => 'Password123!',
            'password_confirmation' => 'Password123!',
        ], $override);
    }

    public function test_mahasiswa_bisa_registrasi_dengan_data_valid(): void
    {
        $response = $this->post(route('register'), $this->dataRegistrasiValid());

        $response->assertRedirect(route('mahasiswa.dashboard'));
        $this->assertAuthenticated();
        $this->assertDatabaseHas('users', [
            'email' => 'mahasiswabaru@silpm.local',
            'nim'   => '2305099',
            'role'  => 'mahasiswa',
        ]);
    }

    public function test_registrasi_gagal_jika_nim_duplikat(): void
    {
        User::factory()->create(['nim' => '2305099']);

        $response = $this->post(route('register'), $this->dataRegistrasiValid([
            'email' => 'lain@silpm.local',
        ]));

        $response->assertSessionHasErrors('nim');
        $this->assertGuest();
    }

    public function test_registrasi_gagal_jika_email_duplikat(): void
    {
        User::factory()->create(['email' => 'duplikat@silpm.local']);

        $response = $this->post(route('register'), $this->dataRegistrasiValid([
            'nim'   => '2305100',
            'email' => 'duplikat@silpm.local',
        ]));

        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    public function test_registrasi_gagal_jika_field_kosong(): void
    {
        $response = $this->post(route('register'), []);

        $response->assertSessionHasErrors(['name', 'nim', 'class', 'email', 'password']);
        $this->assertGuest();
    }
}
