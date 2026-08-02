<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Mahasiswa Menyatakan Belum Selesai</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; background-color: #eef2ff; color: #1f2937; -webkit-font-smoothing: antialiased; }
        .wrapper { max-width: 600px; margin: 24px auto; background: #ffffff; border-radius: 16px; overflow: hidden; box-shadow: 0 4px 24px rgba(43,76,186,0.12); }

        /* Header */
        .header { background: linear-gradient(135deg, #1e3a8a 0%, #2b4cba 60%, #3b5fc0 100%); padding: 20px 24px; text-align: center; }
        .header-inner { display: inline-flex; align-items: center; }
        .header-brand { display: flex; align-items: center; }
        .header-logo-img { height: 44px; width: auto; max-width: 160px; object-fit: contain; display: block; margin-right: 14px; flex-shrink: 0; }
        .header-text { border-left: 1px solid rgba(255,255,255,0.35); padding-left: 14px; text-align: left; }
        .header-title { font-size: 15px; font-weight: 700; color: #ffffff; line-height: 1.2; white-space: nowrap; }
        .header-sub { font-size: 11px; color: rgba(255,255,255,0.72); margin-top: 4px; white-space: nowrap; }
        .header-badge { background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25); color: #ffffff; font-size: 11px; font-weight: 600; border-radius: 999px; padding: 3px 12px; white-space: nowrap; }

        /* Alert bar */
        .alert-bar { background: #fef2f2; border-bottom: 1px solid #fecaca; padding: 10px 24px; text-align: center; }
        .alert-bar p { font-size: 13px; color: #991b1b; font-weight: 600; }

        /* Body */
        .body { padding: 28px 32px 36px; }
        .section-label { font-size: 10px; font-weight: 700; color: #6b7280; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 10px; }

        .pengaduan-box { background: #f8faff; border-left: 3px solid #2b4cba; border-right: 3px solid #2b4cba; border-radius: 10px; padding: 4px 18px; margin-bottom: 16px; }
        .info-row { padding: 10px 0; border-bottom: 1px solid #e2e8f0; font-size: 13px; }
        .info-row:last-child { border-bottom: none; }
        .info-row .key { display: block; color: #6b7280; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.03em; margin-bottom: 4px; }
        .info-row .val { display: block; color: #111827; font-weight: 500; word-break: break-word; }

        .alasan-box { background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 14px 18px; margin: 16px 0; }
        .alasan-box .label { font-size: 10px; font-weight: 700; color: #991b1b; text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 8px; }
        .alasan-box p { font-size: 13px; color: #7f1d1d; line-height: 1.7; white-space: pre-wrap; }

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
            .header-badge { display: none !important; }
            .body { padding: 20px 16px 24px !important; }
            .info-row .key { font-size: 10px !important; }
            .footer { padding: 14px 16px !important; }
        }
    </style>
</head>
<body>
<div class="wrapper">
    <!-- Header -->
    <div class="header">
        <div class="header-inner">
            <div class="header-brand">
                <img src="{{ $message->embed(public_path('images/logo-polmed-small.png')) }}"
                     alt="Logo Polmed" class="header-logo-img" height="44">
                <div class="header-text">
                    <div class="header-title">Layanan Pengaduan Mahasiswa</div>
                    <div class="header-sub">Politeknik Negeri Medan</div>
                </div>
            </div>
        </div>
        <div style="text-align:center; margin-top:10px;">
            <span class="header-badge">Panel Admin</span>
        </div>
    </div>

    <!-- Alert bar -->
    <div class="alert-bar">
        <p>⚠ Mahasiswa menyatakan pengaduan ini belum selesai — perlu ditindaklanjuti kembali</p>
    </div>

    <div class="body">
        <div class="pengaduan-box">
            <div class="section-label">Detail Pengaduan</div>
            <div class="info-row">
                <span class="key">Nomor</span>
                <span class="val">#{{ $pengaduan->id }}</span>
            </div>
            <div class="info-row">
                <span class="key">Subjek</span>
                <span class="val"><strong>{{ $pengaduan->subjek }}</strong></span>
            </div>
            <div class="info-row">
                <span class="key">Status Saat Ini</span>
                <span class="val">Sedang Diproses (dibuka kembali)</span>
            </div>
        </div>

        <div class="alasan-box">
            <div class="label">Alasan dari Mahasiswa</div>
            <p>{{ $alasan }}</p>
        </div>

        <div class="cta">
            <a href="{{ config('app.url') }}/admin/pengaduan/{{ $pengaduan->id }}">
                Tindak Lanjuti Pengaduan Ini
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
