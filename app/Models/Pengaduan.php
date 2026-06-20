<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengaduan extends Model
{
    protected $table = 'pengaduan';

    protected $fillable = [
        'user_id',
        'is_anonymous',
        'kategori_id',
        'tanggal_kejadian',
        'subjek',
        'isi_pengaduan',
        'bukti',
        'status',
        'catatan_admin',
    ];

    protected $casts = [
        'tanggal_kejadian' => 'datetime',
        'is_anonymous'     => 'boolean',
    ];

    // ===================== Konstanta Status =====================

    const STATUS_MENUNGGU              = 'menunggu_verifikasi';
    const STATUS_DIPROSES               = 'sedang_diproses';
    const STATUS_BUTUH_INFO             = 'membutuhkan_informasi_tambahan';
    const STATUS_MENUNGGU_KONFIRMASI    = 'menunggu_konfirmasi_mahasiswa';
    const STATUS_SELESAI                = 'selesai_ditangani';
    const STATUS_DITOLAK                = 'ditolak';

    /**
     * Status yang sudah final — tidak dapat diubah lagi oleh siapapun.
     */
    const STATUS_FINAL = [self::STATUS_SELESAI, self::STATUS_DITOLAK];

    /**
     * Jumlah hari batas SLA: dipakai untuk auto-close di status menunggu konfirmasi
     * mahasiswa, dan untuk menandai pengaduan "terlambat" di status non-final lainnya.
     */
    const SLA_HARI = 3;

    /**
     * Daftar label status yang ramah pengguna.
     */
    public static function statusLabels(): array
    {
        return [
            self::STATUS_MENUNGGU           => 'Menunggu Verifikasi',
            self::STATUS_DIPROSES            => 'Sedang Diproses',
            self::STATUS_BUTUH_INFO          => 'Membutuhkan Informasi Tambahan',
            self::STATUS_MENUNGGU_KONFIRMASI => 'Menunggu Konfirmasi Mahasiswa',
            self::STATUS_SELESAI             => 'Selesai Ditangani',
            self::STATUS_DITOLAK             => 'Ditolak',
        ];
    }

    /**
     * Warna badge Tailwind CSS per status.
     */
    public static function statusColors(): array
    {
        return [
            self::STATUS_MENUNGGU           => 'yellow',
            self::STATUS_DIPROSES            => 'blue',
            self::STATUS_BUTUH_INFO          => 'orange',
            self::STATUS_MENUNGGU_KONFIRMASI => 'cyan',
            self::STATUS_SELESAI             => 'green',
            self::STATUS_DITOLAK             => 'red',
        ];
    }

    /**
     * Ambil label status saat ini.
     */
    public function getStatusLabelAttribute(): string
    {
        return self::statusLabels()[$this->status] ?? $this->status;
    }

    /**
     * Status final tidak dapat diubah lagi (lihat PengaduanService::updateStatus).
     */
    public function isFinal(): bool
    {
        return in_array($this->status, self::STATUS_FINAL, true);
    }

    /**
     * True jika sudah melewati SLA_HARI hari tanpa perubahan status, dan belum final.
     * Dipakai untuk menandai pengaduan yang "terlantar" di dashboard admin.
     */
    public function getIsOverdueAttribute(): bool
    {
        return ! $this->isFinal() && $this->updated_at->lte(now()->subDays(self::SLA_HARI));
    }

    /**
     * URL untuk mengakses file bukti pendukung (null jika tidak ada lampiran).
     * Disajikan lewat route terotentikasi (bukan disk publik) — hanya pemilik & admin yang bisa akses.
     */
    public function getBuktiUrlAttribute(): ?string
    {
        return $this->bukti ? route('bukti.pengaduan', $this) : null;
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

    /**
     * Pengaduan non-final yang sudah melewati SLA_HARI hari tanpa perubahan status.
     */
    public function scopeOverdue($query)
    {
        return $query->whereNotIn('status', self::STATUS_FINAL)
            ->where('updated_at', '<=', now()->subDays(self::SLA_HARI));
    }
}
