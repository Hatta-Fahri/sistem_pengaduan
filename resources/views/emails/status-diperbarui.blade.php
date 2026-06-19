<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Status Pengaduan Diperbarui — SILPM</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, Helvetica, sans-serif; background-color: #f3f4f6; color: #111827; }
        .wrapper { max-width: 600px; margin: 32px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .header { background: #1d4ed8; padding: 32px 40px; text-align: center; }
        .header h1 { color: #ffffff; font-size: 22px; font-weight: bold; }
        .header p { color: #bfdbfe; font-size: 13px; margin-top: 6px; }
        .body { padding: 36px 40px; }
        .greeting { font-size: 15px; color: #374151; line-height: 1.7; margin-bottom: 24px; }
        .status-change { display: flex; align-items: center; gap: 12px; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px 20px; margin: 20px 0; }
        .badge { display: inline-block; border-radius: 999px; padding: 4px 14px; font-size: 12px; font-weight: 600; }
        .badge-gray   { background: #f3f4f6; color: #374151; }
        .badge-blue   { background: #dbeafe; color: #1e40af; }
        .badge-yellow { background: #fef3c7; color: #92400e; }
        .badge-green  { background: #d1fae5; color: #065f46; }
        .badge-red    { background: #fee2e2; color: #991b1b; }
        .arrow { color: #9ca3af; font-size: 18px; font-weight: bold; }
        .info-box { background: #eff6ff; border-left: 4px solid #1d4ed8; border-radius: 4px; padding: 16px 20px; margin: 20px 0; }
        .info-box .label { font-size: 11px; font-weight: bold; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 12px; }
        .info-row { display: flex; gap: 8px; margin-bottom: 8px; font-size: 14px; }
        .info-row .key { color: #6b7280; min-width: 140px; flex-shrink: 0; }
        .info-row .val { color: #111827; font-weight: 500; }
        .catatan-box { background: #fefce8; border: 1px solid #fde68a; border-radius: 6px; padding: 14px 18px; margin: 20px 0; }
        .catatan-box .label { font-size: 11px; font-weight: bold; color: #92400e; margin-bottom: 8px; }
        .catatan-box p { font-size: 14px; color: #78350f; line-height: 1.6; white-space: pre-wrap; }
        .cta { text-align: center; margin: 28px 0; }
        .cta a { background: #1d4ed8; color: #ffffff; text-decoration: none; padding: 12px 28px; border-radius: 6px; font-size: 14px; font-weight: 600; display: inline-block; }
        .footer { background: #f9fafb; border-top: 1px solid #e5e7eb; padding: 20px 40px; text-align: center; font-size: 12px; color: #9ca3af; line-height: 1.6; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <h1>SILPM</h1>
        <p>Sistem Informasi Layanan Pengaduan Mahasiswa</p>
    </div>

    <div class="body">
        <p class="greeting">
            Yth. <strong>{{ $pengaduan->user->name }}</strong>,<br><br>
            Status pengaduan Anda telah diperbarui oleh tim admin. Berikut informasi terbaru mengenai pengaduan Anda.
        </p>

        <!-- Perubahan Status -->
        @php
            $statusLabels = \App\Models\Pengaduan::statusLabels();
            $statusColors = \App\Models\Pengaduan::statusColors();
            $colorMap = [
                'gray'   => 'badge-gray',
                'blue'   => 'badge-blue',
                'yellow' => 'badge-yellow',
                'orange' => 'badge-yellow',
                'green'  => 'badge-green',
                'red'    => 'badge-red',
            ];
            $lamaClass = $colorMap[$statusColors[$statusLama] ?? 'gray'] ?? 'badge-gray';
            $baruClass  = $colorMap[$statusColors[$pengaduan->status] ?? 'gray'] ?? 'badge-gray';
        @endphp

        <div class="status-change">
            <span class="badge {{ $lamaClass }}">{{ $statusLabels[$statusLama] ?? $statusLama }}</span>
            <span class="arrow">→</span>
            <span class="badge {{ $baruClass }}">{{ $statusLabels[$pengaduan->status] ?? $pengaduan->status }}</span>
        </div>

        <!-- Info Pengaduan -->
        <div class="info-box">
            <div class="label">Detail Pengaduan</div>
            <div class="info-row">
                <span class="key">Nomor Pengaduan</span>
                <span class="val">#{{ $pengaduan->id }}</span>
            </div>
            <div class="info-row">
                <span class="key">Subjek</span>
                <span class="val">{{ $pengaduan->subjek }}</span>
            </div>
            <div class="info-row">
                <span class="key">Kategori</span>
                <span class="val">{{ $pengaduan->kategori->nama_kategori }}</span>
            </div>
        </div>

        <!-- Catatan Admin (jika ada) -->
        @if ($pengaduan->catatan_admin)
        <div class="catatan-box">
            <div class="label">Catatan dari Admin</div>
            <p>{{ $pengaduan->catatan_admin }}</p>
        </div>
        @endif

        <!-- CTA -->
        <div class="cta">
            <a href="{{ config('app.url') }}/mahasiswa/pengaduan/{{ $pengaduan->id }}">
                Lihat Detail Pengaduan
            </a>
        </div>
    </div>

    <div class="footer">
        <p>&copy; {{ date('Y') }} SILPM — Program Studi Manajemen Informatika</p>
        <p>Politeknik Negeri Medan</p>
        <p style="margin-top:8px; color:#d1d5db;">Email ini dikirim secara otomatis. Mohon tidak membalas email ini.</p>
    </div>
</div>
</body>
</html>
