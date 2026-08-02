<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pengaduan Diterima — SILPM</title>
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

        /* Success banner */
        .success-bar { background: #f0fdf4; border-bottom: 1px solid #bbf7d0; padding: 10px 24px; text-align: center; }
        .success-bar p { font-size: 13px; color: #065f46; font-weight: 600; }

        /* Body */
        .body { padding: 28px 32px 36px; }
        .greeting { font-size: 15px; color: #374151; line-height: 1.8; margin-bottom: 24px; }
        .greeting strong { color: #2b4cba; }

        /* Info box */
        .info-box { background: #f0f4ff; border-left: 3px solid #2b4cba; border-radius: 8px; padding: 14px 18px; margin: 20px 0; }
        .info-box .label { font-size: 10px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 10px; }
        .info-row { display: flex; gap: 8px; margin-bottom: 7px; font-size: 13px; align-items: flex-start; }
        .info-row .key { color: #6b7280; min-width: 130px; flex-shrink: 0; }
        .info-row .val { color: #111827; font-weight: 500; word-break: break-word; }

        /* Badge */
        .badge { display: inline-block; background: #fef3c7; color: #92400e; border-radius: 999px; padding: 3px 12px; font-size: 11px; font-weight: 600; }

        /* CTA */
        .cta { text-align: center; margin: 24px 0; }
        .cta a { background: #2b4cba; color: #ffffff; text-decoration: none; padding: 13px 32px; border-radius: 10px; font-size: 14px; font-weight: 700; display: inline-block; box-shadow: 0 4px 14px rgba(43,76,186,0.35); }

        .note { font-size: 13px; color: #6b7280; line-height: 1.75; margin-top: 20px; }

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
            .info-row { flex-direction: column !important; gap: 2px !important; }
            .info-row .key { min-width: unset !important; font-weight: 600; }
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

    <!-- Success bar -->
    <div class="success-bar">
        <p>✅ Pengaduan Anda telah berhasil diterima</p>
    </div>

    <!-- Body -->
    <div class="body">
        <p class="greeting">
            Yth. <strong>{{ $pengaduan->user->name }}</strong>,<br><br>
            Pengaduan Anda telah berhasil kami terima. Tim kami akan segera memverifikasi dan memproses pengaduan ini.
        </p>

        <!-- Ringkasan Pengaduan -->
        <div class="info-box">
            <div class="label">Ringkasan Pengaduan</div>
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
            <div class="info-row">
                <span class="key">Tanggal Kejadian</span>
                <span class="val">{{ $pengaduan->tanggal_kejadian->format('d M Y, H:i') }}</span>
            </div>
            <div class="info-row">
                <span class="key">Tanggal Pengajuan</span>
                <span class="val">{{ $pengaduan->created_at->format('d M Y, H:i') }}</span>
            </div>
            <div class="info-row">
                <span class="key">Status Saat Ini</span>
                <span class="val"><span class="badge">Menunggu Verifikasi</span></span>
            </div>
        </div>

        <!-- CTA -->
        <div class="cta">
            <a href="{{ config('app.url') }}/mahasiswa/pengaduan/{{ $pengaduan->id }}">
                Pantau Status Pengaduan
            </a>
        </div>

        <p class="note">
            Anda dapat memantau perkembangan pengaduan ini melalui portal SILPM kapan saja.<br>
            Jika ada pertanyaan, silakan hubungi Program Studi Manajemen Informatika.
        </p>
    </div>

    <!-- Footer -->
    <div class="footer">
        <p>&copy; {{ date('Y') }} <strong>SILPM</strong> — Program Studi Manajemen Informatika</p>
        <p>Politeknik Negeri Medan</p>
        <p style="margin-top:6px; color:#d1d5db; font-size:10px;">Email ini dikirim secara otomatis. Mohon tidak membalas email ini.</p>
    </div>
</div>
</body>
</html>
