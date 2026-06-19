<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pengaduan Diterima — SILPM</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, Helvetica, sans-serif; background-color: #f3f4f6; color: #111827; }
        .wrapper { max-width: 600px; margin: 32px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .header { background: #1d4ed8; padding: 32px 40px; text-align: center; }
        .header h1 { color: #ffffff; font-size: 22px; font-weight: bold; letter-spacing: 0.5px; }
        .header p { color: #bfdbfe; font-size: 13px; margin-top: 6px; }
        .body { padding: 36px 40px; }
        .greeting { font-size: 16px; color: #374151; margin-bottom: 20px; }
        .info-box { background: #eff6ff; border-left: 4px solid #1d4ed8; border-radius: 4px; padding: 16px 20px; margin: 24px 0; }
        .info-box .label { font-size: 11px; font-weight: bold; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 12px; }
        .info-row { display: flex; gap: 8px; margin-bottom: 8px; font-size: 14px; }
        .info-row .key { color: #6b7280; min-width: 140px; flex-shrink: 0; }
        .info-row .val { color: #111827; font-weight: 500; }
        .badge { display: inline-block; background: #fef3c7; color: #92400e; border-radius: 999px; padding: 3px 12px; font-size: 12px; font-weight: 600; }
        .cta { text-align: center; margin: 28px 0; }
        .cta a { background: #1d4ed8; color: #ffffff; text-decoration: none; padding: 12px 28px; border-radius: 6px; font-size: 14px; font-weight: 600; display: inline-block; }
        .note { font-size: 13px; color: #6b7280; line-height: 1.7; margin-top: 20px; }
        .footer { background: #f9fafb; border-top: 1px solid #e5e7eb; padding: 20px 40px; text-align: center; font-size: 12px; color: #9ca3af; line-height: 1.6; }
    </style>
</head>
<body>
<div class="wrapper">
    <!-- Header -->
    <div class="header">
        <h1>SILPM</h1>
        <p>Sistem Informasi Layanan Pengaduan Mahasiswa</p>
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
        <p>&copy; {{ date('Y') }} SILPM — Program Studi Manajemen Informatika</p>
        <p>Politeknik Negeri Medan</p>
        <p style="margin-top:8px; color:#d1d5db;">Email ini dikirim secara otomatis. Mohon tidak membalas email ini.</p>
    </div>
</div>
</body>
</html>
