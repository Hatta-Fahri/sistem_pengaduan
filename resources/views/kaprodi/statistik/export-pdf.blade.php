<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Statistik Pengaduan - {{ $teksPeriode }}</title>
    <style>
        html, body { margin: 0; padding: 0; }

        div, p, h1, h2, h3, h4, h5, h6,
        table, tr, td, th, thead, tbody, tfoot,
        ul, ol, li, span, img, a {
            margin: 0; padding: 0; box-sizing: border-box;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11px;
            color: #000;
            background: #fff;
        }

        /* ============================================================
           HEADER TETAP
           ============================================================ */
        #pdf-header {
            position: fixed;
            top: 0; left: 0; right: 0;
            padding: 10pt 35pt 0;
            background: #fff;
        }

        .kop-h-tbl    { width: 100%; border-collapse: collapse; }
        .kop-h-logo   { width: 105pt; text-align: center; vertical-align: middle; padding: 0 6pt 6pt 0; }
        .kop-h-logo img { width: 93pt; height: auto; }
        .kop-h-text   { text-align: center; vertical-align: middle; padding: 0 4pt 6pt; }
        .kop-h-spacer { width: 105pt; }

        .kop-kemendikti { font-size: 13pt; font-weight: normal; text-transform: uppercase; letter-spacing: 0.2pt; }
        .kop-polmed     { font-size: 13pt; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5pt; margin-top: 1pt; }
        .kop-jurusan    { font-size: 10pt; font-weight: bold; text-transform: uppercase; margin-top: 1pt; }
        .kop-addr       { font-size: 9.5pt; font-weight: bold; margin-top: 2pt; }

        .kop-hr-thick { border: none; border-top: 3pt solid #000; margin: 0; }
        .kop-hr-thin  { border: none; border-top: 1pt solid #000; margin: 2pt 0 0; }

        /* ============================================================
           FOOTER TETAP
           ============================================================ */
        #pdf-footer {
            position: fixed;
            bottom: 22pt; left: 0; right: 0;
            padding: 0 57pt;
            background: #fff;
        }
        .footer-strip { width: 100%; border-collapse: collapse; }
        .footer-strip td { font-size: 8pt; padding: 0; }

        /* ============================================================
           HALAMAN 1
           ============================================================ */
        .page-wrap { padding: 128pt 57pt 50pt; }

        /* ============================================================
           HALAMAN 2, 3, … (TOP-LEVEL, di LUAR page-wrap)
           ============================================================ */
        .page-cont {
            page-break-before: always;
            padding: 128pt 57pt 50pt;
        }

        /* ============================================================
           JUDUL LAPORAN
           ============================================================ */
        .laporan-title   { text-align: center; font-size: 13px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 2px; }
        .laporan-subtitle{ text-align: center; font-size: 10px; margin-bottom: 10px; }
        .laporan-divider { border: none; border-top: 1px solid #000; margin: 6px 0 10px; }

        /* ============================================================
           INFO DOKUMEN
           ============================================================ */
        .doc-info        { margin: 0 0 8px; font-size: 10.5px; }
        .doc-info table  { width: 100%; }
        .doc-info td     { padding: 2px 0; vertical-align: top; }
        .doc-info .lbl   { width: 155px; }
        .doc-info .colon { width: 12px; }
        .doc-info .val   { font-weight: normal; }

        /* ============================================================
           SECTION TITLE
           ============================================================ */
        .section-title {
            font-size: 11px; font-weight: bold; text-transform: uppercase;
            letter-spacing: 0.5px; border-bottom: 1px solid #000;
            padding-bottom: 3px; margin-bottom: 6px; margin-top: 12px;
        }

        /* ============================================================
           RINGKASAN
           ============================================================ */
        table.summary-tbl { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        table.summary-tbl th { border: 1px solid #000; padding: 4px 8px; text-align: center; font-size: 10px; font-weight: bold; background: #fff; text-transform: uppercase; }
        table.summary-tbl td { border: 1px solid #000; padding: 5px 8px; text-align: center; font-size: 13px; font-weight: bold; }
        table.summary-tbl td.small-val { font-size: 10px; }

        /* ============================================================
           DATA TABLE
           ============================================================ */
        table.data-table { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        table.data-table thead th { border: 1px solid #000; padding: 5px 5px; text-align: center; font-size: 9.5px; font-weight: bold; background: #fff; text-transform: uppercase; }
        table.data-table tbody td { border: 1px solid #000; padding: 4px 5px; font-size: 9.5px; text-align: center; vertical-align: middle; }
        table.data-table tbody tr { page-break-inside: avoid; }
        table.data-table tbody td.td-left { text-align: left; }
        table.data-table tfoot td { border: 1px solid #000; padding: 4px 5px; font-size: 9.5px; font-weight: bold; text-align: center; background: #fff; }

        /* ============================================================
           TWO-COLUMN
           ============================================================ */
        .two-col { display: table; width: 100%; border-spacing: 8px; }
        .col      { display: table-cell; width: 50%; vertical-align: top; }

        /* ============================================================
           TANDA TANGAN
           ============================================================ */
        .ttd-wrap  { margin-top: 24pt; width: 100%; text-align: right; padding-right: 30pt; }
        .ttd-inner { display: inline-block; text-align: left; font-family: 'Times New Roman', Times, serif; font-size: 9pt; font-weight: normal; }

        .page-break { page-break-after: always; }
    </style>
</head>
<body>

{{-- ======================================================================
     HEADER TETAP
     ====================================================================== --}}
<div id="pdf-header">
    <table class="kop-h-tbl">
        <tr>
            <td class="kop-h-logo">
                <img src="{{ public_path('images/logo-polmed-small.png') }}" alt="Logo Politeknik Negeri Medan" />
            </td>
            <td class="kop-h-text">
                <div class="kop-kemendikti">Kementerian Pendidikan Tinggi, Sains, dan Teknologi</div>
                <div class="kop-polmed">Politeknik Negeri Medan</div>
                <div class="kop-jurusan">Jurusan Teknik Komputer &amp; Informatika</div>
                <div class="kop-addr">Jl. Almamater No. 1 Kampus USU, Medan 20155, Indonesia</div>
                <div class="kop-addr">Telp. (061) 8210463, 8211235, Faks: (061) 8215845</div>
                <div class="kop-addr" style="white-space:nowrap;font-size:8.5pt;">http://www.polmed.ac.id e-mail: polmed@polmed.ac.id, info@polmed.ac.id</div>
            </td>
            <td class="kop-h-spacer"></td>
        </tr>
    </table>
    <hr class="kop-hr-thick" />
</div>

{{-- ======================================================================
     FOOTER TETAP
     ====================================================================== --}}
<div id="pdf-footer">
    <table class="footer-strip">
        <tr>
            <td>No. Dokumen: SILPM/LAP/{{ $startDate->format('Ymd') }}-{{ $endDate->format('Ymd') }}</td>
            <td style="text-align:center;">Revisi ke: 01</td>
            <td style="text-align:right;">Tanggal Efektif: {{ now()->locale('id')->isoFormat('D MMMM YYYY') }}</td>
        </tr>
    </table>
</div>

@php
    $FIRST_ROWS    = 6;
    $NEXT_ROWS     = 22;

    $firstChunk    = $pengaduanList->take($FIRST_ROWS);
    $nextChunks    = $pengaduanList->skip($FIRST_ROWS)->chunk($NEXT_ROWS);
    $hasNextChunks = $nextChunks->isNotEmpty();
    $totalRows     = $pengaduanList->count();
    $rowCounter    = 0;
@endphp

{{-- ======================================================================
     HALAMAN 1
     ====================================================================== --}}
<div class="page-wrap">

    <div class="laporan-title">Laporan Statistik Pengaduan Mahasiswa</div>
    <div class="laporan-subtitle">Sistem Informasi Layanan Pengaduan Mahasiswa &mdash; {{ $teksPeriode }}</div>
    <hr class="laporan-divider" />

    <div class="doc-info">
        <table>
            <tr>
                <td class="lbl">Periode Laporan</td><td class="colon">:</td><td class="val">{{ $teksPeriode }}</td>
                <td style="width:20px;"></td>
                <td class="lbl">Tanggal Cetak</td><td class="colon">:</td>
                <td class="val">{{ now()->locale('id')->isoFormat('D MMMM YYYY') }}, Pukul {{ now()->format('H:i') }} WIB</td>
            </tr>
            <tr>
                <td class="lbl">Total Pengaduan Masuk</td><td class="colon">:</td><td class="val">{{ $totalPengaduan }} pengaduan</td>
                <td></td>
                <td class="lbl">Kategori Terbanyak</td><td class="colon">:</td>
                <td class="val">{{ $kategoriTerbanyak?->nama_kategori ?? '—' }} ({{ $kategoriTerbanyak?->pengaduan_count ?? 0 }} laporan)</td>
            </tr>
            <tr>
                <td class="lbl">Rata-rata Waktu Selesai</td><td class="colon">:</td><td class="val">{{ $rataRataJam }} jam per pengaduan</td>
                <td></td><td></td><td></td><td></td>
            </tr>
        </table>
    </div>

    <div class="section-title">I. Ringkasan Data</div>
    <table class="summary-tbl">
        <thead><tr>
            <th>Total Pengaduan Masuk</th><th>Rata-rata Waktu Penyelesaian</th><th>Kategori Terbanyak</th>
        </tr></thead>
        <tbody><tr>
            <td>{{ $totalPengaduan }}<br><span style="font-size:9px;font-weight:normal;">laporan</span></td>
            <td>{{ $rataRataJam }}<br><span style="font-size:9px;font-weight:normal;">jam</span></td>
            <td class="small-val">{{ $kategoriTerbanyak?->nama_kategori ?? '—' }}<br>
                <span style="font-size:9px;font-weight:normal;">({{ $kategoriTerbanyak?->pengaduan_count ?? 0 }} laporan)</span></td>
        </tr></tbody>
    </table>

    <div class="section-title">II. Rekap Pengaduan</div>
    <div class="two-col">
        <div class="col" style="padding-right:4px;">
            <div style="font-size:10.5px;font-weight:bold;margin-bottom:5px;">A. Berdasarkan Status</div>
            <table class="data-table">
                <thead><tr><th>Status Pengaduan</th><th style="width:22%">Jumlah</th><th style="width:16%">%</th></tr></thead>
                <tbody>
                    @foreach($perStatus as $key => $jumlah)
                    <tr>
                        <td class="td-left">{{ $statusLabels[$key] ?? $key }}</td>
                        <td>{{ $jumlah }}</td>
                        <td>{{ $totalPengaduan > 0 ? round(($jumlah/$totalPengaduan)*100,1) : 0 }}%</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot><tr><td class="td-left">Total</td><td>{{ $totalPengaduan }}</td><td>100%</td></tr></tfoot>
            </table>
        </div>
        <div class="col" style="padding-left:4px;">
            <div style="font-size:10.5px;font-weight:bold;margin-bottom:5px;">B. Berdasarkan Kategori</div>
            <table class="data-table">
                <thead><tr><th>Kategori Pengaduan</th><th style="width:22%">Jumlah</th><th style="width:16%">%</th></tr></thead>
                <tbody>
                    @foreach($perKategori as $kat)
                    <tr>
                        <td class="td-left">{{ $kat->nama_kategori }}</td>
                        <td>{{ $kat->pengaduan_count }}</td>
                        <td>{{ $totalPengaduan > 0 ? round(($kat->pengaduan_count/$totalPengaduan)*100,1) : 0 }}%</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot><tr><td class="td-left">Total</td><td>{{ $totalPengaduan }}</td><td>100%</td></tr></tfoot>
            </table>
        </div>
    </div>

    <div class="section-title">III. Tren Pengaduan &mdash; 12 Bulan Terakhir</div>
    @php
        $pdfTrendLabels = []; $pdfTrendData = [];
        for ($i = 11; $i >= 0; $i--) {
            $br = now()->subMonths($i);
            $pdfTrendLabels[] = strtoupper($br->locale('en')->isoFormat('MMM YYYY'));
            $pdfTrendData[]   = \App\Models\Pengaduan::whereYear('created_at',$br->year)
                                    ->whereMonth('created_at',$br->month)->count();
        }
    @endphp
    <table class="data-table">
        <thead><tr>
            @foreach($pdfTrendLabels as $label)<th style="font-size:8px;">{{ $label }}</th>@endforeach
        </tr></thead>
        <tbody><tr>
            @foreach($pdfTrendData as $jml)<td style="font-weight:bold;">{{ $jml }}</td>@endforeach
        </tr></tbody>
    </table>

    @if($pengaduanList->isNotEmpty())
    <div class="section-title">IV. Daftar Seluruh Pengaduan &mdash; {{ $teksPeriode }}</div>
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
            @foreach($firstChunk as $p)
            @php $rowCounter++ @endphp
            <tr>
                <td>{{ $rowCounter }}</td>
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
        @if(! $hasNextChunks)
        <tfoot>
            <tr><td colspan="10" class="td-left">Total: {{ $totalRows }} pengaduan &mdash; {{ $teksPeriode }}</td></tr>
        </tfoot>
        @endif
    </table>
    @endif

    @if(! $hasNextChunks)
    <div class="ttd-wrap">
        <div class="ttd-inner">
            <div>Medan, {{ now()->locale('id')->isoFormat('D MMMM YYYY') }}</div>
            <div>Koordinator Program Studi</div>
            <div style="margin:6pt 0;text-align:center;">
                <img src="{{ public_path('images/tanda-tangan_kaprodimi.png') }}"
                     alt="Tanda Tangan Kaprodi"
                     style="height:45pt;width:auto;display:block;margin:0 auto;" />
            </div>
            <div>Bister Purba, S.Kom., M.Kom.</div>
            <div>NIP. 19910103 202203 1 008</div>
        </div>
    </div>
    @endif

</div>{{-- /.page-wrap --}}

{{-- ======================================================================
     HALAMAN 2, 3, … — di LUAR page-wrap (TOP-LEVEL)
     ====================================================================== --}}
@if($hasNextChunks)
@foreach($nextChunks as $chunk)
<div class="page-cont">
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
            @foreach($chunk as $p)
            @php $rowCounter++ @endphp
            <tr>
                <td>{{ $rowCounter }}</td>
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
        @if($loop->last)
        <tfoot>
            <tr><td colspan="10" class="td-left">Total: {{ $totalRows }} pengaduan &mdash; {{ $teksPeriode }}</td></tr>
        </tfoot>
        @endif
    </table>

    @if($loop->last)
    <div class="ttd-wrap">
        <div class="ttd-inner">
            <div>Medan, {{ now()->locale('id')->isoFormat('D MMMM YYYY') }}</div>
            <div>Koordinator Program Studi</div>
            <div style="margin:6pt 0;text-align:center;">
                <img src="{{ public_path('images/tanda-tangan_kaprodimi.png') }}"
                     alt="Tanda Tangan Kaprodi"
                     style="height:45pt;width:auto;display:block;margin:0 auto;" />
            </div>
            <div>Bister Purba, S.Kom., M.Kom.</div>
            <div>NIP. 19910103 202203 1 008</div>
        </div>
    </div>
    @endif
</div>{{-- /.page-cont --}}
@endforeach
@endif

</body>
</html>
