<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusHistory extends Model
{
    protected $table = 'status_history';

    // Hanya ada created_at, tidak ada updated_at (audit log)
    const UPDATED_AT = null;

    protected $fillable = [
        'pengaduan_id',
        'status_lama',
        'status_baru',
        'catatan',
        'bukti',
        'changed_by',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // ===================== Relasi =====================

    /**
     * Pengaduan terkait.
     */
    public function pengaduan()
    {
        return $this->belongsTo(Pengaduan::class, 'pengaduan_id');
    }

    /**
     * User yang melakukan perubahan status.
     */
    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    // ===================== Helpers =====================

    /**
     * Ambil label status baru yang ramah pengguna.
     */
    public function getStatusBaruLabelAttribute(): string
    {
        return Pengaduan::statusLabels()[$this->status_baru] ?? $this->status_baru;
    }

    /**
     * Ambil label status lama yang ramah pengguna.
     */
    public function getStatusLamaLabelAttribute(): string
    {
        return $this->status_lama
            ? (Pengaduan::statusLabels()[$this->status_lama] ?? $this->status_lama)
            : '—';
    }

    /**
     * URL untuk mengakses file bukti yang dilampirkan pada entri riwayat ini (null jika tidak ada).
     */
    public function getBuktiUrlAttribute(): ?string
    {
        return $this->bukti ? route('bukti.riwayat', $this) : null;
    }
}
