<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Status Pengaduan Diperbarui</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; background-color: #eef2ff; color: #1f2937; -webkit-font-smoothing: antialiased; }
        .wrapper { max-width: 600px; margin: 24px auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(43,76,186,0.12); }

        /* Header */
        .header { background: linear-gradient(135deg, #1e3a8a 0%, #2b4cba 60%, #3b5fc0 100%); padding: 20px 24px; text-align: center; }
        .header-inner { display: inline-flex; align-items: center; }
        .header-logo-img { height: 44px; width: auto; max-width: 160px; object-fit: contain; display: block; margin-right: 14px; flex-shrink: 0; }
        .header-text { border-left: 1px solid rgba(255,255,255,0.35); padding-left: 14px; text-align: left; }
        .header-title { font-size: 15px; font-weight: 700; color: #ffffff; line-height: 1.2; white-space: nowrap; }
        .header-sub { font-size: 11px; color: rgba(255,255,255,0.72); margin-top: 4px; white-space: nowrap; }

        /* Body */
        .body { padding: 28px 32px 36px; }
        .greeting { font-size: 15px; color: #374151; line-height: 1.8; margin-bottom: 24px; }
        .greeting strong { color: #2b4cba; }

        /* Status change */
        .status-change { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; background: #f8faff; border: 1px solid #e0e7ff; border-radius: 10px; padding: 14px 18px; margin: 16px 0; }
        .badge { display: inline-block; border-radius: 999px; padding: 4px 14px; font-size: 12px; font-weight: 600; white-space: nowrap; }
        .badge-gray   { background: #f3f4f6; color: #374151; }
        .badge-blue   { background: #dbeafe; color: #1e40af; }
        .badge-yellow { background: #fef3c7; color: #92400e; }
        .badge-cyan   { background: #cffafe; color: #155e75; }
        .badge-green  { background: #d1fae5; color: #065f46; }
        .badge-red    { background: #fee2e2; color: #991b1b; }
        .arrow { color: #9ca3af; font-size: 16px; font-weight: bold; flex-shrink: 0; }

        /* Info box */
        .info-box { background: #f8faff; border-left: 3px solid #2b4cba; border-right: 3px solid #2b4cba; border-radius: 10px; padding: 4px 18px; margin: 16px 0; }
        .info-box .label { font-size: 10px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.08em; padding-bottom: 8px; border-bottom: 1px solid #e2e8f0; margin-bottom: 0; }
        .info-row { padding: 10px 0; border-bottom: 1px solid #e2e8f0; font-size: 13px; }
        .info-row:last-child { border-bottom: none; }
        .info-row .key { display: block; color: #6b7280; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.03em; margin-bottom: 4px; }
        .info-row .val { display: block; color: #111827; font-weight: 500; word-break: break-word; }

        /* Catatan */
        .catatan-box { background: #fffbeb; border: 1px solid #fde68a; border-radius: 8px; padding: 14px 18px; margin: 16px 0; }
        .catatan-box .label { font-size: 10px; font-weight: 700; color: #92400e; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 8px; }
        .catatan-box p { font-size: 13px; color: #78350f; line-height: 1.7; white-space: pre-wrap; }

        /* Deadline */
        .deadline-box { background: #ecfeff; border: 1px solid #a5f3fc; border-radius: 8px; padding: 14px 18px; margin: 16px 0; }
        .deadline-box p { font-size: 13px; color: #155e75; line-height: 1.7; }

        /* CTA */
        .cta { text-align: center; margin: 24px 0; }
        .cta a { background: #2b4cba; color: #ffffff; text-decoration: none; padding: 13px 32px; border-radius: 10px; font-size: 14px; font-weight: 700; display: inline-block; box-shadow: 0 4px 14px rgba(43,76,186,0.35); }

        /* Footer */
        .footer { background: #f8faff; border-top: 1px solid #e5e7eb; padding: 18px 32px; text-align: center; font-size: 11px; color: #9ca3af; line-height: 1.9; }
        .footer strong { color: #6b7280; }

        @media only screen and (max-width: 480px) {
            .wrapper { margin: 0 !important; border-radius: 0 !important; }
            .header { padding: 14px 16px !important; }
            .header-logo-img { height: 34px !important; width: auto !important; max-width: 120px !important; margin-right: 10px !important; }
            .header-title { font-size: 12px !important; }
            .header-sub { font-size: 10px !important; }
            .body { padding: 20px 16px 24px !important; }
            .info-row .key { font-size: 10px !important; }
            .status-change { gap: 6px !important; }
            .footer { padding: 14px 16px !important; }
        }
    </style>
</head>
<body>
<div class="wrapper">
    <!-- Header -->
    <div class="header">
        <div class="header-inner">
            <img src="{{ $message->embed(public_path('images/logo-polmed-small.png')) }}"
                 alt="Logo Polmed" class="header-logo-img" height="44">
            <div class="header-text">
                <div class="header-title">Layanan Pengaduan Mahasiswa</div>
                <div class="header-sub">Politeknik Negeri Medan</div>
            </div>
        </div>
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
                'cyan'   => 'badge-cyan',
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

        <!-- Pengingat batas waktu konfirmasi -->
        @if ($pengaduan->status === \App\Models\Pengaduan::STATUS_MENUNGGU_KONFIRMASI)
        <div class="deadline-box">
            <p><strong>Mohon konfirmasi dalam {{ \App\Models\Pengaduan::SLA_HARI }} hari.</strong> Admin menandai pengaduan ini selesai ditangani. Silakan login dan konfirmasi di halaman detail pengaduan — jika tidak ada respons dalam {{ \App\Models\Pengaduan::SLA_HARI }} hari, pengaduan akan otomatis ditutup.</p>
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
        <p style="margin-top:6px; color:#d1d5db; font-size:10px;">Email ini dikirim secara otomatis. Mohon tidak membalas email ini.</p>
    </div>
</div>
</body>
</html>
