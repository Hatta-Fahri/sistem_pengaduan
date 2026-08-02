<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Atur Ulang Password — SILPM</title>
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
        .cta { text-align: center; margin: 32px 0; }
        .cta a { background: #2b4cba; color: #ffffff; text-decoration: none; padding: 13px 32px; border-radius: 10px; font-size: 14px; font-weight: 700; display: inline-block; box-shadow: 0 4px 14px rgba(43,76,186,0.35); }

        .note { font-size: 13px; color: #6b7280; line-height: 1.75; margin-top: 24px; }

        /* Copy link box */
        .copy-link { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 12px; margin-top: 24px; font-size: 11px; word-break: break-all; color: #6b7280; line-height: 1.5; }

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
            Halo <strong>{{ $userName }}</strong>,<br><br>
            Anda menerima email ini karena kami menerima permintaan atur ulang password (reset password) untuk akun Anda.
        </p>

        <!-- CTA -->
        <div class="cta">
            <a href="{{ $url }}">
                Reset Password
            </a>
        </div>

        <p class="note">
            Tautan reset password ini akan kadaluarsa dalam 60 menit.<br><br>
            Jika Anda tidak merasa meminta reset password, Anda tidak perlu melakukan tindakan apapun.
        </p>

        <div class="copy-link">
            Jika Anda kesulitan menekan tombol "Reset Password", salin dan tempel URL berikut ke browser web Anda:<br>
            <a href="{{ $url }}" style="color: #2b4cba;">{{ $url }}</a>
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
