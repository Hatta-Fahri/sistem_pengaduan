<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Verifikasi Alamat Email — SILPM</title>
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

        /* CTA */
        .cta { text-align: center; margin: 28px 0; }
        .cta a { background: #2b4cba; color: #ffffff; text-decoration: none; padding: 14px 38px; border-radius: 10px; font-size: 15px; font-weight: 700; display: inline-block; letter-spacing: 0.2px; box-shadow: 0 4px 14px rgba(43,76,186,0.35); }

        /* Info box */
        .info-box { background: #f0f4ff; border-left: 3px solid #2b4cba; border-radius: 8px; padding: 14px 18px; margin: 20px 0; }
        .info-box p { font-size: 13px; color: #374151; line-height: 1.75; }

        /* Expiry */
        .expiry { display: flex; align-items: flex-start; gap: 10px; background: #fffbeb; border: 1px solid #fde68a; border-radius: 8px; padding: 12px 16px; margin: 20px 0; }
        .expiry-icon { font-size: 18px; flex-shrink: 0; line-height: 1.3; }
        .expiry p { font-size: 13px; color: #92400e; line-height: 1.65; }

        /* Link fallback */
        .link-fallback { margin-top: 20px; }
        .link-fallback p { font-size: 12px; color: #9ca3af; line-height: 1.6; margin-bottom: 6px; }
        .link-fallback .url { font-size: 11px; color: #6b7280; word-break: break-all; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px; padding: 8px 12px; display: block; margin-top: 4px; }

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
            .cta a { padding: 12px 24px !important; font-size: 14px !important; }
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

    <!-- Body -->
    <div class="body">
        <p class="greeting">
            Halo, <strong>{{ $userName }}</strong>!<br><br>
            Terima kasih telah mendaftar di <strong>SILPM — Politeknik Negeri Medan</strong>.
            Untuk mengaktifkan akun Anda dan mulai menggunakan layanan pengaduan, klik tombol di bawah ini untuk verifikasi alamat email Anda.
        </p>

        <!-- CTA -->
        <div class="cta">
            <a href="{{ $verificationUrl }}">
                ✉&nbsp; Verifikasi Alamat Email Saya
            </a>
        </div>

        <!-- Expiry -->
        <div class="expiry">
            <div class="expiry-icon">⏰</div>
            <p>Tautan verifikasi ini hanya berlaku selama <strong>60 menit</strong>. Jika sudah kedaluwarsa, Anda dapat meminta tautan baru melalui halaman verifikasi email di portal SILPM.</p>
        </div>

        <!-- Info -->
        <div class="info-box">
            <p>Jika Anda tidak merasa mendaftar di SILPM, abaikan saja email ini. Akun tidak akan diaktifkan jika tautan verifikasi tidak diklik.</p>
        </div>

        <!-- Link fallback -->
        <div class="link-fallback">
            <p>Tombol tidak berfungsi? Salin dan tempel tautan berikut ke browser Anda:</p>
            <span class="url">{{ $verificationUrl }}</span>
        </div>
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
