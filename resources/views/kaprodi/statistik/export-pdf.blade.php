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

        /* ============================================================
           HEADER TETAP — kop surat, muncul di SETIAP halaman
           position:fixed + top:0 adalah cara DomPDF untuk repeating header
           ============================================================ */
        #pdf-header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            padding: 10pt 35pt 0;
            background: #fff;
        }

        /* Tabel kop: [logo 105pt] | [teks center] | [spacer 105pt]
           Kolom logo = spacer → teks tepat center */
        .kop-h-tbl  { width: 100%; border-collapse: collapse; }
        .kop-h-logo { width: 105pt; text-align: center; vertical-align: middle;
                      padding: 0 6pt 6pt 0; }
        .kop-h-logo img { width: 93pt; height: auto; }
        .kop-h-text { text-align: center; vertical-align: middle;
                      padding: 0 4pt 6pt; }
        .kop-h-spacer { width: 105pt; }

        .kop-kemendikti { font-size: 12pt; font-weight: normal;
                          text-transform: uppercase; letter-spacing: 0.2pt; }
        .kop-polmed     { font-size: 17pt; font-weight: bold;
                          text-transform: uppercase; letter-spacing: 0.5pt;
                          margin-top: 1pt; }
        .kop-jurusan    { font-size: 12pt; font-weight: bold;
                          text-transform: uppercase; margin-top: 1pt; }
        .kop-addr       { font-size: 10pt; margin-top: 2pt; }

        /* Garis bawah kop: tebal + tipis */
        .kop-hr-thick { border: none; border-top: 3pt solid #000; margin: 0; }
        .kop-hr-thin  { border: none; border-top: 1pt solid #000;
                        margin: 2pt 0 0; }

        /* ============================================================
           FOOTER TETAP — strip dokumen, muncul di SETIAP halaman
           ============================================================ */
        #pdf-footer {
            position: fixed;
            bottom: 22pt;
            left: 0;
            right: 0;
            padding: 0 57pt;
            background: #fff;
        }
        .footer-strip { width: 100%; border-collapse: collapse; }
        .footer-strip td {
            font-size: 8pt;
            padding: 0;
            /* border dihapus */
        }

        /* ============================================================
           AREA KONTEN — padding atas menyesuaikan tinggi header fixed
           ============================================================ */
        .page-wrap {
            padding: 128pt 57pt 40pt;  /* bottom 40pt: ruang untuk footer strip fixed (bottom:22pt) */
        }

        /* ============================================================
           JUDUL LAPORAN (di dalam konten, di bawah kop)
           ============================================================ */
        .laporan-title {
            text-align: center;
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }
        .laporan-subtitle {
            text-align: center;
            font-size: 10px;
            margin-bottom: 10px;
        }
        .laporan-divider {
            border: none;
            border-top: 1px solid #000;
            margin: 6px 0 10px;
        }

        /* ============================================================
           INFO DOKUMEN
           ============================================================ */
        .doc-info { margin: 0 0 8px; font-size: 10.5px; }
        .doc-info table { width: 100%; }
        .doc-info td { padding: 2px 0; vertical-align: top; }
        .doc-info .lbl   { width: 155px; }
        .doc-info .colon { width: 12px; }
        .doc-info .val   { font-weight: normal; }

        /* ============================================================
           SECTION TITLE
           ============================================================ */
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

        /* ============================================================
           RINGKASAN
           ============================================================ */
        table.summary-tbl { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
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

        /* ============================================================
           DATA TABLE
           ============================================================ */
        table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
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
        table.data-table tbody tr {
            page-break-inside: avoid;  /* baris tidak boleh terpotong antar halaman */
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

        /* ============================================================
           TWO-COLUMN
           ============================================================ */
        .two-col { display: table; width: 100%; border-spacing: 8px; }
        .col      { display: table-cell; width: 50%; vertical-align: top; }

        /* ============================================================
           TANDA TANGAN (akhir dokumen, halaman terakhir)
           ============================================================ */
        .signature-section { margin-top: 20pt; }
        .sig-tbl { width: 100%; border-collapse: collapse; }
        .sig-tbl td { font-size: 11px; vertical-align: top; }
        .sig-left  { width: 55%; }
        .sig-right { width: 45%; text-align: center; }
        .sig-space { height: 52pt; }   /* ruang tanda tangan */

        .page-break { page-break-after: always; }
    </style>
</head>
<body>

{{-- =====================================================================
     HEADER TETAP — kop surat muncul di setiap halaman PDF
     ===================================================================== --}}
<div id="pdf-header">
    <table class="kop-h-tbl">
        <tr>
            <td class="kop-h-logo">
                <img src="{{ public_path('images/logo-polmed-small.png') }}"
                     alt="Logo Politeknik Negeri Medan" />
            </td>
            <td class="kop-h-text">
                <div class="kop-kemendikti">Kementerian Pendidikan Tinggi, Sains, dan Teknologi</div>
                <div class="kop-polmed">Politeknik Negeri Medan</div>
                <div class="kop-jurusan">Jurusan Teknik Komputer &amp; Informatika</div>
                <div class="kop-addr">Jl. Almamater No. 1 Kampus USU, Medan 20155, Indonesia</div>
                <div class="kop-addr">Telp. (061) 8210463, 8211235, Faks: (061) 8215845</div>
                <div class="kop-addr">http://www.polmed.ac.id e-mail: polmed@polmed.ac.id, info@polmed.ac.id</div>
            </td>
            <td class="kop-h-spacer"></td>
        </tr>
    </table>
    <hr class="kop-hr-thick" />
</div>

{{-- =====================================================================
     FOOTER TETAP — strip No. Dokumen di setiap halaman PDF
     ===================================================================== --}}
<div id="pdf-footer">
    <table class="footer-strip">
        <tr>
            <td>No. Dokumen: SILPM/LAP/{{ $tahun }}</td>
            <td style="text-align:center;">Revisi ke: 01</td>
            <td style="text-align:right;">Tanggal Efektif: {{ now()->locale('id')->isoFormat('D MMMM YYYY') }}</td>
        </tr>
    </table>
</div>

{{-- =====================================================================
     KONTEN UTAMA
     ===================================================================== --}}
<div class="page-wrap">

    {{-- Judul laporan di dalam konten --}}
    <div class="laporan-title">Laporan Statistik Pengaduan Mahasiswa</div>
    <div class="laporan-subtitle">
        Sistem Informasi Layanan Pengaduan Mahasiswa &mdash; Tahun {{ $tahun }}
    </div>
    <hr class="laporan-divider" />

    <!-- ===== INFO DOKUMEN ===== -->
    <div class="doc-info">
        <table>
            <tr>
                <td class="lbl">Periode Laporan</td>
                <td class="colon">:</td>
                <td class="val">1 Januari {{ $tahun }} s.d. 31 Desember {{ $tahun }}</td>
                <td style="width:20px;"></td>
                <td class="lbl">Tanggal Cetak</td>
                <td class="colon">:</td>
                <td class="val">{{ now()->locale('id')->isoFormat('D MMMM YYYY') }}, Pukul {{ now()->format('H:i') }} WIB</td>
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
    <div style="page-break-before: always; padding-top: 130pt;">
    <div class="section-title">IV. Daftar Seluruh Pengaduan Tahun {{ $tahun }}</div>
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
    </div>{{-- tutup div page-break-before --}}
    @endif

</div><!-- /.page-wrap -->
</body>
</html>
