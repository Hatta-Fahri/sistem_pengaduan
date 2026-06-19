<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pengaduan Baru Masuk — SILPM Admin</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, Helvetica, sans-serif; background-color: #f3f4f6; color: #111827; }
        .wrapper { max-width: 600px; margin: 32px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 1px 3px rgba(0,0,0,0.1); }
        .header { background: #111827; padding: 32px 40px; text-align: center; }
        .header h1 { color: #ffffff; font-size: 22px; font-weight: bold; }
        .header p { color: #9ca3af; font-size: 13px; margin-top: 6px; }
        .alert-bar { background: #fef3c7; border-bottom: 1px solid #fde68a; padding: 12px 40px; }
        .alert-bar p { font-size: 13px; color: #92400e; font-weight: 600; text-align: center; }
        .body { padding: 36px 40px; }
        .section-label { font-size: 11px; font-weight: bold; color: #6b7280; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 12px; }
        .pelapor-box { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px 20px; margin-bottom: 20px; }
        .info-row { display: flex; gap: 8px; margin-bottom: 8px; font-size: 14px; }
        .info-row .key { color: #6b7280; min-width: 120px; flex-shrink: 0; }
        .info-row .val { color: #111827; font-weight: 500; }
        .pengaduan-box { background: #eff6ff; border-left: 4px solid #1d4ed8; border-radius: 4px; padding: 16px 20px; margin-bottom: 20px; }
        .badge-gray { display: inline-block; background: #f3f4f6; color: #374151; border-radius: 999px; padding: 3px 12px; font-size: 12px; font-weight: 600; }
        .isi-preview { background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 6px; padding: 14px 16px; font-size: 13px; color: #4b5563; line-height: 1.6; margin-top: 16px; max-height: 120px; overflow: hidden; }
        .cta { text-align: center; margin: 28px 0; }
        .cta a { background: #111827; color: #ffffff; text-decoration: none; padding: 12px 28px; border-radius: 6px; font-size: 14px; font-weight: 600; display: inline-block; }
        .footer { background: #f9fafb; border-top: 1px solid #e5e7eb; padding: 20px 40px; text-align: center; font-size: 12px; color: #9ca3af; line-height: 1.6; }
    </style>
</head>
<body>
<div class="wrapper">
    <div class="header">
        <h1>SILPM — Panel Admin</h1>
        <p>Sistem Informasi Layanan Pengaduan Mahasiswa</p>
    </div>

    <div class="alert-bar">
        <p>⚡ Pengaduan baru masuk dan membutuhkan verifikasi Anda</p>
    </div>

    <div class="body">
        <!-- Data Pelapor -->
        <div class="pelapor-box">
            <div class="section-label">Identitas Pelapor</div>
            <div class="info-row">
                <span class="key">Nama</span>
                <span class="val">{{ $pengaduan->user->name }}</span>
            </div>
            <div class="info-row">
                <span class="key">NIM</span>
                <span class="val">{{ $pengaduan->user->nim }}</span>
            </div>
            <div class="info-row">
                <span class="key">Kelas</span>
                <span class="val">{{ $pengaduan->user->class }}</span>
            </div>
            <div class="info-row">
                <span class="key">Email</span>
                <span class="val">{{ $pengaduan->user->email }}</span>
            </div>
        </div>

        <!-- Detail Pengaduan -->
        <div class="pengaduan-box">
            <div class="section-label">Detail Pengaduan</div>
            <div class="info-row">
                <span class="key">Nomor</span>
                <span class="val">#{{ $pengaduan->id }}</span>
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
                <span class="key">Status</span>
                <span class="val"><span class="badge-gray">Menunggu Verifikasi</span></span>
            </div>
            <div class="info-row">
                <span class="key">Subjek</span>
                <span class="val"><strong>{{ $pengaduan->subjek }}</strong></span>
            </div>
        </div>

        <!-- Preview Isi -->
        <div class="section-label" style="margin-top:16px;">Preview Isi Pengaduan</div>
        <div class="isi-preview">
            {{ \Str::limit($pengaduan->isi_pengaduan, 300) }}
        </div>

        <!-- CTA -->
        <div class="cta">
            <a href="{{ config('app.url') }}/admin/pengaduan/{{ $pengaduan->id }}">
                Kelola Pengaduan Ini
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
