<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    protected $table = 'email_logs';

    // Tidak ada timestamps standar, hanya sent_at
    public $timestamps = false;

    protected $fillable = [
        'recipient_email',
        'subject',
        'type',
        'pengaduan_id',
        'status',
        'sent_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
    ];

    // ===================== Relasi =====================

    /**
     * Pengaduan terkait log email ini.
     */
    public function pengaduan()
    {
        return $this->belongsTo(Pengaduan::class, 'pengaduan_id');
    }
}
