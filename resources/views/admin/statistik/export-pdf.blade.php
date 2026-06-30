<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Statistik Pengaduan {{ $tahun }}</title>
    <style>
        html, body {
            margin: 0;
            padding: 0;
        }

        /* Reset elemen dalam */
        div, p, h1, h2, h3, h4, h5, h6,
        table, tr, td, th, thead, tbody, tfoot,
        ul, ol, li, span, img, a {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11px;
            color: #000;
            background: #fff;
        }

        /* Wrapper sebagai pengganti margin halaman */
        .page-wrap {
            padding: 28pt 57pt;   /* atas/bawah 1cm=28pt | kiri/kanan 2cm=57pt */
        }

        /* ===== KOP SURAT ===== */
        .kop-outer {
            border: 2px solid #000;
            border-radius: 3px;
            overflow: hidden;
        }

        /*
         * Trik 3-kolom DomPDF:
         *   [logo 86px] [teks center flex] [spacer 86px]
         * Lebar logo = lebar spacer → teks tepat di tengah halaman.
         * Tidak ada border antar kolom → tampak sebagai satu blok menyatu.
         */
        table.kop-tbl {
            width: 100%;
            border-collapse: collapse;
            background: #fff;
        }
        table.kop-tbl td {
            padding: 10px 8px;
            vertical-align: middle;
        }
        .kop-col-logo {
            width: 86px;
            text-align: center;
        }
        .kop-col-text {
            text-align: center;
        }
        .kop-col-spacer {
            width: 86px;
        }
        .kop-col-logo img {
            width: 78px;
            height: auto;
        }
        .kop-line1 {
            font-size: 19px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            color: #000;
            line-height: 1.2;
        }
        .kop-line2 {
            font-size: 13.5px;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 3px;
            color: #000;
        }
        .kop-line3 {
            font-size: 13.5px;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 1px;
            color: #000;
        }
        .kop-divider {
            border-top: 2px solid #000;
            background: #fff;
            color: #000;
            text-align: center;
            padding: 5px 0 4px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }
        .kop-sub-divider {
            border-top: 1px solid #000;
            background: #fff;
            text-align: center;
            padding: 3px 0;
            font-size: 9.5px;
            color: #000;
        }

        /* ===== INFO DOKUMEN ===== */
        .doc-info {
            margin: 10px 0 8px;
            font-size: 10.5px;
        }
        .doc-info table { width: 100%; }
        .doc-info td { padding: 2px 0; vertical-align: top; }
        .doc-info .lbl   { width: 155px; }
        .doc-info .colon { width: 12px; }
        .doc-info .val   { font-weight: normal; }

        /* ===== SECTION TITLE ===== */
        .section-title {
            font-size: 11px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-bottom: 1px solid #000;
            padding-bottom: 3px;
            margin-bottom: 6px;
            margin-top: 12px;
        }

        /* ===== RINGKASAN ===== */
        table.summary-tbl {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        table.summary-tbl th {
            border: 1px solid #000;
            padding: 4px 8px;
            text-align: center;
            font-size: 10px;
            font-weight: bold;
            background: #fff;
            text-transform: uppercase;
        }
        table.summary-tbl td {
            border: 1px solid #000;
            padding: 5px 8px;
            text-align: center;
            font-size: 13px;
            font-weight: bold;
        }
        table.summary-tbl td.small-val { font-size: 10px; }

        /* ===== DATA TABLE ===== */
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        table.data-table thead th {
            border: 1px solid #000;
            padding: 5px 5px;
            text-align: center;
            font-size: 9.5px;
            font-weight: bold;
            background: #fff;
            text-transform: uppercase;
        }
        table.data-table tbody td {
            border: 1px solid #000;
            padding: 4px 5px;
            font-size: 9.5px;
            text-align: center;
            vertical-align: middle;
        }
        table.data-table tbody td.td-left { text-align: left; }
        table.data-table tfoot td {
            border: 1px solid #000;
            padding: 4px 5px;
            font-size: 9.5px;
            font-weight: bold;
            text-align: center;
            background: #fff;
        }

        /* ===== TWO-COLUMN ===== */
        .two-col { display: table; width: 100%; border-spacing: 8px; }
        .col      { display: table-cell; width: 50%; vertical-align: top; }

        /* ===== FOOTER ===== */
        .footer {
            margin-top: 14px;
            border-top: 1px solid #000;
            padding-top: 5px;
            display: table;
            width: 100%;
            font-size: 9px;
        }
        .footer-left  { display: table-cell; }
        .footer-right { display: table-cell; text-align: right; }

        .page-break { page-break-after: always; }
    </style>
</head>
<body>
<div class="page-wrap">

    <!-- ===== KOP SURAT ===== -->
    <div class="kop-outer">

        {{-- Tabel 3-kolom: logo | teks | spacer (lebar logo = spacer → teks tepat center) --}}
        <table class="kop-tbl">
            <tr>
                <td class="kop-col-logo">
                    <img src="{{ public_path('images/logo-polmed-small.png') }}"
                         alt="Logo Politeknik Negeri Medan" />
                </td>
                <td class="kop-col-text">
                    <div class="kop-line1">Politeknik Negeri Medan</div>
                    <div class="kop-line2">Jurusan Teknik Komputer &amp; Informatika</div>
                    <div class="kop-line3">Program Studi Manajemen Informatika</div>
                </td>
                <td class="kop-col-spacer"></td>
            </tr>
        </table>

        {{-- Garis navy + judul laporan --}}
        <div class="kop-divider">
            Laporan Statistik Pengaduan Mahasiswa
        </div>

        {{-- Sub-judul sistem --}}
        <div class="kop-sub-divider">
            Sistem Informasi Layanan Pengaduan Mahasiswa &mdash; Tahun {{ $tahun }}
        </div>

    </div>

    <!-- ===== INFO DOKUMEN ===== -->
    <div class="doc-info" style="margin-top:10px;">
        <table>
            <tr>
                <td class="lbl">Periode Laporan</td>
                <td class="colon">:</td>
                <td class="val">1 Januari {{ $tahun }} s.d. 31 Desember {{ $tahun }}</td>
                <td style="width:20px;"></td>
                <td class="lbl">Tanggal Cetak</td>
                <td class="colon">:</td>
                <td class="val">{{ now()->isoFormat('D MMMM YYYY') }}, Pukul {{ now()->format('H:i') }} WIB</td>
            </tr>
            <tr>
                <td class="lbl">Total Pengaduan Masuk</td>
                <td class="colon">:</td>
                <td class="val">{{ $totalPengaduan }} pengaduan</td>
                <td></td>
                <td class="lbl">Kategori Terbanyak</td>
                <td class="colon">:</td>
                <td class="val">{{ $kategoriTerbanyak?->nama_kategori ?? '—' }} ({{ $kategoriTerbanyak?->pengaduan_count ?? 0 }} laporan)</td>
            </tr>
            <tr>
                <td class="lbl">Rata-rata Waktu Selesai</td>
                <td class="colon">:</td>
                <td class="val">{{ $rataRataJam }} jam per pengaduan</td>
                <td></td><td></td><td></td><td></td>
            </tr>
        </table>
    </div>

    <!-- ===== RINGKASAN ANGKA ===== -->
    <div class="section-title">I. Ringkasan Data</div>
    <table class="summary-tbl">
        <thead>
            <tr>
                <th>Total Pengaduan Masuk</th>
                <th>Rata-rata Waktu Penyelesaian</th>
                <th>Kategori Terbanyak</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $totalPengaduan }}<br><span style="font-size:9px;font-weight:normal;">laporan</span></td>
                <td>{{ $rataRataJam }}<br><span style="font-size:9px;font-weight:normal;">jam</span></td>
                <td class="small-val">
                    {{ $kategoriTerbanyak?->nama_kategori ?? '—' }}<br>
                    <span style="font-size:9px;font-weight:normal;">({{ $kategoriTerbanyak?->pengaduan_count ?? 0 }} laporan)</span>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- ===== REKAP PER STATUS & PER KATEGORI ===== -->
    <div class="section-title">II. Rekap Pengaduan</div>
    <div class="two-col">
        <div class="col" style="padding-right:4px;">
            <div style="font-size:10.5px;font-weight:bold;margin-bottom:5px;">A. Berdasarkan Status</div>
            <table class="data-table">
                <thead><tr>
                    <th>Status Pengaduan</th>
                    <th style="width:22%">Jumlah</th>
                    <th style="width:16%">%</th>
                </tr></thead>
                <tbody>
                    @foreach($perStatus as $key => $jumlah)
                    <tr>
                        <td class="td-left">{{ $statusLabels[$key] ?? $key }}</td>
                        <td>{{ $jumlah }}</td>
                        <td>{{ $totalPengaduan > 0 ? round(($jumlah/$totalPengaduan)*100,1) : 0 }}%</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot><tr>
                    <td class="td-left">Total</td>
                    <td>{{ $totalPengaduan }}</td>
                    <td>100%</td>
                </tr></tfoot>
            </table>
        </div>

        <div class="col" style="padding-left:4px;">
            <div style="font-size:10.5px;font-weight:bold;margin-bottom:5px;">B. Berdasarkan Kategori</div>
            <table class="data-table">
                <thead><tr>
                    <th>Kategori Pengaduan</th>
                    <th style="width:22%">Jumlah</th>
                    <th style="width:16%">%</th>
                </tr></thead>
                <tbody>
                    @foreach($perKategori as $kat)
                    <tr>
                        <td class="td-left">{{ $kat->nama_kategori }}</td>
                        <td>{{ $kat->pengaduan_count }}</td>
                        <td>{{ $totalPengaduan > 0 ? round(($kat->pengaduan_count/$totalPengaduan)*100,1) : 0 }}%</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot><tr>
                    <td class="td-left">Total</td>
                    <td>{{ $totalPengaduan }}</td>
                    <td>100%</td>
                </tr></tfoot>
            </table>
        </div>
    </div>

    <!-- ===== TREN BULANAN ===== -->
    <div class="section-title">III. Tren Pengaduan &mdash; 12 Bulan Terakhir</div>
    <table class="data-table">
        <thead><tr>
            @foreach($trendLabels as $label)
            <th style="font-size:8px;">{{ $label }}</th>
            @endforeach
        </tr></thead>
        <tbody><tr>
            @foreach($trendData as $jumlah)
            <td style="font-weight:bold;">{{ $jumlah }}</td>
            @endforeach
        </tr></tbody>
    </table>

    <!-- ===== DAFTAR PENGADUAN ===== -->
    @if($pengaduanList->isNotEmpty())
    <div class="section-title" style="margin-top:14px;">IV. Daftar Seluruh Pengaduan Tahun {{ $tahun }}</div>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width:3%">No</th>
                <th style="width:12%">Pelapor</th>
                <th style="width:8%">NIM</th>
                <th style="width:6%">Kelas</th>
                <th style="width:12%">Kategori</th>
                <th style="width:19%">Subjek</th>
                <th style="width:8%">Tgl Dibuat</th>
                <th style="width:14%">Status</th>
                <th style="width:11%">Tgl Selesai</th>
                <th style="width:5%">Anonim</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pengaduanList as $i => $p)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td class="td-left">{{ $p->is_anonymous ? 'Anonim' : $p->user->name }}</td>
                <td>{{ $p->is_anonymous ? '—' : $p->user->nim }}</td>
                <td>{{ $p->is_anonymous ? '—' : $p->user->class }}</td>
                <td class="td-left">{{ $p->kategori->nama_kategori }}</td>
                <td class="td-left">{{ $p->subjek }}</td>
                <td>{{ $p->created_at->format('d/m/Y') }}</td>
                <td class="td-left" style="font-size:8.5px;">{{ $statusLabels[$p->status] ?? $p->status }}</td>
                <td>{{ $p->status === 'selesai_ditangani' ? $p->updated_at->format('d/m/Y H:i') : '—' }}</td>
                <td>{{ $p->is_anonymous ? 'Ya' : 'Tidak' }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="10" class="td-left">Total: {{ $pengaduanList->count() }} pengaduan pada Tahun {{ $tahun }}</td>
            </tr>
        </tfoot>
    </table>
    @endif

    <!-- ===== FOOTER ===== -->
    <div class="footer">
        <div class="footer-left">
            Dokumen ini dicetak secara otomatis oleh sistem &mdash; Politeknik Negeri Medan
        </div>
        <div class="footer-right">
            {{ now()->format('d/m/Y H:i') }} WIB
        </div>
    </div>

</div><!-- /.page-wrap -->
</body>
</html>
