<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Atribut yang dapat diisi secara massal.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'nim',
        'class',
        'email',
        'password',
        'role',
        'is_active',
    ];

    /**
     * Atribut yang disembunyikan saat serialisasi.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Tipe cast atribut.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    // ===================== RBAC Helpers =====================

    /**
     * Cek apakah user adalah admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Cek apakah user adalah mahasiswa.
     */
    public function isMahasiswa(): bool
    {
        return $this->role === 'mahasiswa';
    }

    /**
     * Cek apakah user adalah kaprodi.
     * 
     * Encapsulating the role check prevents hardcoding string comparisons 
     * throughout the application controllers, adhering to DRY principles.
     */
    public function isKaprodi(): bool
    {
        return $this->role === 'kaprodi';
    }

    /**
     * Cek apakah akun masih aktif (belum dinonaktifkan admin).
     */
    public function isActive(): bool
    {
        return (bool) $this->is_active;
    }

    // ===================== Relasi =====================

    /**
     * Pengaduan yang dimiliki oleh user ini (mahasiswa).
     */
    public function pengaduan()
    {
        return $this->hasMany(Pengaduan::class, 'user_id');
    }

    /**
     * Riwayat perubahan status yang dilakukan oleh user ini (admin/kaprodi).
     */
    public function statusHistoryChanges()
    {
        return $this->hasMany(StatusHistory::class, 'changed_by');
    }
}