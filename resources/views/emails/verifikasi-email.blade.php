<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Verifikasi Alamat Email — SILPM</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, Helvetica, sans-serif; background-color: #f3f4f6; color: #111827; }

        .wrapper { max-width: 600px; margin: 32px auto; background: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 16px rgba(0,0,0,0.10); }

        /* Header biru gradient */
        .header { background: linear-gradient(135deg, #1e40af 0%, #2563eb 60%, #3b82f6 100%); padding: 36px 40px 28px; text-align: center; position: relative; }
        .header::after { content: ''; display: block; position: absolute; bottom: -1px; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, #60a5fa, #ffffff40, #60a5fa); }
        .header-logo { font-size: 26px; font-weight: 900; color: #ffffff; letter-spacing: 2px; margin-bottom: 4px; }
        .header-sub  { font-size: 12px; color: #bfdbfe; letter-spacing: 0.5px; }

        /* Icon email */
        .icon-wrap { text-align: center; padding: 32px 40px 0; }
        .icon-circle { display: inline-flex; align-items: center; justify-content: center; width: 72px; height: 72px; background: #eff6ff; border: 3px solid #bfdbfe; border-radius: 50%; }
        .icon-circle svg { width: 36px; height: 36px; }

        /* Body */
        .body { padding: 24px 40px 36px; }
        .greeting { font-size: 15px; color: #374151; margin-bottom: 20px; line-height: 1.7; }
        .greeting strong { color: #1d4ed8; }

        /* CTA button */
        .cta { text-align: center; margin: 28px 0; }
        .cta a {
            background: linear-gradient(135deg, #1d4ed8, #2563eb);
            color: #ffffff;
            text-decoration: none;
            padding: 14px 36px;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 700;
            display: inline-block;
            letter-spacing: 0.3px;
            box-shadow: 0 4px 12px rgba(37,99,235,0.35);
        }

        /* Info box */
        .info-box { background: #eff6ff; border-left: 4px solid #2563eb; border-radius: 6px; padding: 16px 20px; margin: 24px 0; }
        .info-box p { font-size: 13px; color: #1e40af; line-height: 1.6; }

        /* Link fallback */
        .link-fallback { margin-top: 24px; }
        .link-fallback p { font-size: 12px; color: #9ca3af; line-height: 1.6; margin-bottom: 6px; }
        .link-fallback .url { font-size: 11px; color: #6b7280; word-break: break-all; background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 4px; padding: 8px 12px; display: block; margin-top: 4px; }

        /* Expiry note */
        .expiry { display: flex; align-items: flex-start; gap: 10px; background: #fefce8; border: 1px solid #fde68a; border-radius: 6px; padding: 12px 16px; margin: 20px 0; }
        .expiry-icon { font-size: 18px; flex-shrink: 0; line-height: 1; }
        .expiry p { font-size: 12px; color: #92400e; line-height: 1.6; }

        /* Footer */
        .footer { background: #f9fafb; border-top: 1px solid #e5e7eb; padding: 20px 40px; text-align: center; font-size: 12px; color: #9ca3af; line-height: 1.7; }
        .footer strong { color: #6b7280; }
    </style>
</head>
<body>
<div class="wrapper">

    <!-- Header -->
    <div class="header">
        <div class="header-logo">SILPM</div>
        <div class="header-sub">Sistem Informasi Layanan Pengaduan Mahasiswa</div>
    </div>

    <!-- Icon -->
    <div class="icon-wrap">
        <div class="icon-circle">
            <svg viewBox="0 0 24 24" fill="none" stroke="#2563eb" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
            </svg>
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
            <p>
                Jika Anda tidak merasa mendaftar di SILPM, abaikan saja email ini. Akun tidak akan diaktifkan jika tautan verifikasi tidak diklik.
            </p>
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
        <p style="margin-top:8px; color:#d1d5db;">Email ini dikirim secara otomatis. Mohon tidak membalas email ini.</p>
    </div>

</div>
</body>
</html>
