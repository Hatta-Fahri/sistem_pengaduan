<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
    protected $table = 'pengaduan';

    protected $fillable = [
        'user_id',
        'kategori_id',
        'tanggal_kejadian',
        'subjek',
        'isi_pengaduan',
        'status',
        'catatan_admin',
    ];

    protected $casts = [
        'tanggal_kejadian' => 'datetime',
    ];

    // ===================== Konstanta Status =====================

    const STATUS_MENUNGGU              = 'menunggu_verifikasi';
    const STATUS_DIPROSES              = 'sedang_diproses';
    const STATUS_BUTUH_INFO            = 'membutuhkan_informasi_tambahan';
    const STATUS_SELESAI               = 'selesai_ditangani';
    const STATUS_DITOLAK               = 'ditolak';

    /**
     * Daftar label status yang ramah pengguna.
     */
    public static function statusLabels(): array
    {
        return [
            self::STATUS_MENUNGGU   => 'Menunggu Verifikasi',
            self::STATUS_DIPROSES   => 'Sedang Diproses',
            self::STATUS_BUTUH_INFO => 'Membutuhkan Informasi Tambahan',
            self::STATUS_SELESAI    => 'Selesai Ditangani',
            self::STATUS_DITOLAK    => 'Ditolak',
        ];
    }

    /**
     * Warna badge Tailwind CSS per status.
     */
    public static function statusColors(): array
    {
        return [
            self::STATUS_MENUNGGU   => 'yellow',
            self::STATUS_DIPROSES   => 'blue',
            self::STATUS_BUTUH_INFO => 'orange',
            self::STATUS_SELESAI    => 'green',
            self::STATUS_DITOLAK    => 'red',
        ];
    }

    /**
     * Ambil label status saat ini.
     */
    public function getStatusLabelAttribute(): string
    {
        return self::statusLabels()[$this->status] ?? $this->status;
    }

    // ===================== Relasi =====================

    /**
     * Mahasiswa pelapor.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Kategori pengaduan.
     */
    public function kategori()
    {
        return $this->belongsTo(KategoriPengaduan::class, 'kategori_id');
    }

    /**
     * Riwayat perubahan status.
     */
    public function statusHistory()
    {
        return $this->hasMany(StatusHistory::class, 'pengaduan_id')->orderBy('created_at', 'desc');
    }

    /**
     * Log email terkait pengaduan ini.
     */
    public function emailLogs()
    {
        return $this->hasMany(EmailLog::class, 'pengaduan_id');
    }

    // ===================== Scopes =====================

    /**
     * Filter berdasarkan status.
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Filter berdasarkan kategori.
     */
    public function scopeByKategori($query, int $kategoriId)
    {
        return $query->where('kategori_id', $kategoriId);
    }

    /**
     * Hanya milik mahasiswa yang sedang login.
     */
    public function scopeMilikSaya($query)
    {
        return $query->where('user_id', auth()->id());
    }
}
