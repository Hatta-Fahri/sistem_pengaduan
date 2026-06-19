<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriPengaduan extends Model
{
    protected $table = 'kategori_pengaduan';

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // ===================== Relasi =====================

    /**
     * Pengaduan yang masuk dalam kategori ini.
     */
    public function pengaduan()
    {
        return $this->hasMany(Pengaduan::class, 'kategori_id');
    }

    // ===================== Scopes =====================

    /**
     * Hanya kategori yang aktif.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
