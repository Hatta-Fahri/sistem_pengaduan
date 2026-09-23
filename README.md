# SILPM — Sistem Informasi Layanan Pengaduan Mahasiswa

Aplikasi web berbasis **Laravel 13** untuk pengelolaan pengaduan mahasiswa di
Program Studi Manajemen Informatika, Politeknik Negeri Medan.

Mahasiswa dapat mengajukan pengaduan (termasuk secara anonim), memantau progres,
dan berinteraksi dalam alur penyelesaian. Admin memverifikasi, memproses, serta
merekap seluruh pengaduan. Kaprodi memiliki akses *read-only* untuk monitoring
dan pelaporan.

## Fitur Utama

### Umum
- Autentikasi berbasis **Laravel Breeze** (login, registrasi, verifikasi email, lupa password)
- **RBAC 3 role** — `mahasiswa`, `admin`, `kaprodi` — ditangani middleware `EnsureRole`
- Akun yang dinonaktifkan admin otomatis dipaksa logout
- Manajemen profil pengguna (edit profil, ubah password, hapus akun)

### Mahasiswa
- Pengajuan pengaduan baru dengan kategori, tanggal kejadian, subjek, isi, dan lampiran bukti
- Opsi **pengaduan anonim** — identitas pelapor disembunyikan dari admin
- Edit pengaduan selama masih berstatus *Menunggu Verifikasi*
- Riwayat status pengaduan dengan timeline lengkap
- **Konfirmasi selesai** atau **tolak konfirmasi** saat admin meminta konfirmasi penyelesaian
- **Balas informasi tambahan** jika admin meminta klarifikasi (teks + lampiran)
- Filter & pencarian pengaduan (status, kategori, subjek)
- Dashboard statistik pribadi
- Rate limiting pada pengajuan pengaduan (`throttle:pengaduan-submit`)

### Admin
- Dashboard dengan ringkasan statistik dan indikator pengaduan *overdue* (SLA 3 hari)
- Daftar seluruh pengaduan dari semua mahasiswa dengan filter lanjutan (status, kategori, rentang tanggal, pencarian nama/NIM/subjek)
- Pencarian nama/NIM dikecualikan untuk pengaduan anonim (perlindungan privasi)
- Update status pengaduan dengan catatan admin dan lampiran bukti opsional
- **6 status pengaduan**: Menunggu Verifikasi → Sedang Diproses → Membutuhkan Informasi Tambahan → Menunggu Konfirmasi Mahasiswa → Selesai Ditangani / Ditolak
- Halaman **Statistik & Rekap** dengan grafik Chart.js (per kategori, per status, tren) dan filter periode (mingguan, bulanan, tahunan, custom)
- Ekspor laporan ke **CSV** (native PHP, UTF-8 BOM untuk Excel)
- Ekspor laporan statistik ke **PDF** (via `barryvdh/laravel-dompdf`)
- **Kelola pengguna mahasiswa** — lihat profil, statistik per mahasiswa, aktifkan/nonaktifkan akun
- **Kelola kategori pengaduan** — CRUD lengkap dengan toggle aktif/nonaktif

### Kaprodi
- Dashboard statistik (*read-only*)
- Lihat detail pengaduan beserta riwayat status (tidak bisa mengubah status)
- Halaman **Statistik & Rekap** dengan grafik dan ekspor PDF
- Kelola pengguna mahasiswa (lihat, aktifkan/nonaktifkan)
- Kelola kategori pengaduan (CRUD + toggle aktif)
- Ekspor laporan pengaduan ke CSV

### Notifikasi Email
- Dikirim secara **asynchronous** melalui Laravel Queue (`QUEUE_CONNECTION=database`)
- **5 jenis notifikasi email**:
  - Pengaduan diterima (ke mahasiswa)
  - Pengaduan baru masuk (ke semua admin)
  - Status diperbarui (ke mahasiswa)
  - Mahasiswa menolak konfirmasi penyelesaian (ke semua admin)
  - Mahasiswa membalas permintaan informasi tambahan (ke semua admin)
- Pencatatan log pengiriman email di tabel `email_logs`

### Otomatisasi
- **Auto-close** pengaduan yang sudah melewati SLA 3 hari di status *Menunggu Konfirmasi Mahasiswa* tanpa respons — ditutup otomatis oleh scheduled command `pengaduan:auto-close` (dijalankan harian)

## Tech Stack

| Komponen | Teknologi |
|----------|-----------|
| Backend | PHP >= 8.3, Laravel 13 |
| Frontend | Blade, Tailwind CSS 3, Alpine.js |
| Build Tool | Vite 8 |
| Database | MySQL |
| Email | Laravel Mail + Queue (SMTP / log) |
| PDF Export | barryvdh/laravel-dompdf |
| Auth Scaffold | Laravel Breeze |
| Testing | PHPUnit 12 |

## Instalasi

```bash
git clone https://github.com/Hatta-Fahri/sistem_pengaduan.git
cd sistem_pengaduan

composer install
npm install
```

Salin file environment dan generate application key:

```bash
cp .env.example .env
php artisan key:generate
```

### Konfigurasi Database

Buka file `.env` dan sesuaikan konfigurasi database. Contoh untuk MySQL:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=silpm
DB_USERNAME=root
DB_PASSWORD=
```

> **Catatan:** Default `.env.example` menggunakan SQLite. Untuk MySQL, uncomment baris `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` dan ubah `DB_CONNECTION=mysql`.

### Migrasi & Seeder

Jalankan migrasi beserta seeder data awal (kategori pengaduan + akun default):

```bash
php artisan migrate --seed
```

### Build Asset Frontend

```bash
npm run build
```

## Menjalankan Aplikasi (Development)

Cara paling mudah — menjalankan semua service sekaligus via Composer script:

```bash
composer dev
```

Script ini menjalankan 4 proses secara paralel:
1. `php artisan serve` — server Laravel
2. `php artisan queue:listen` — queue worker untuk email
3. `php artisan pail` — log viewer real-time
4. `npm run dev` — Vite dev server (hot reload)

Atau jalankan masing-masing secara manual di terminal terpisah:

```bash
php artisan serve          # Server di http://localhost:8000
php artisan queue:work     # Queue worker (email)
npm run dev                # Vite dev server (hot reload)
```

Aplikasi dapat diakses di `http://localhost:8000`.

## Konfigurasi Email

Secara default `MAIL_MAILER=log` — email tidak benar-benar terkirim, hanya
ditulis ke `storage/logs/laravel.log` (cukup untuk development). Untuk menerima
email sungguhan, isi blok `MAIL_*` di `.env` dengan salah satu:

- **Gmail SMTP** — `MAIL_MAILER=smtp`, `MAIL_HOST=smtp.gmail.com`, `MAIL_PORT=587`,
  `MAIL_USERNAME` = alamat Gmail, `MAIL_PASSWORD` = [App Password](https://myaccount.google.com/apppasswords)
  (bukan password akun biasa), `MAIL_FROM_ADDRESS` harus sama dengan `MAIL_USERNAME`.
- **Mailtrap sandbox** — `MAIL_MAILER=smtp`, `MAIL_HOST=sandbox.smtp.mailtrap.io`,
  `MAIL_PORT=2525`, kredensial dari dashboard [mailtrap.io](https://mailtrap.io)
  (email tertangkap di inbox Mailtrap, bukan ke email tujuan asli — cocok untuk testing).

> Jangan commit `.env` — kredensial email selalu lewat file lokal, bukan `.env.example`.

## Scheduled Command (Auto-Close)

Pengaduan yang sudah 3 hari di status *Menunggu Konfirmasi Mahasiswa* tanpa
respons akan ditutup otomatis oleh command `pengaduan:auto-close`. Untuk
mengaktifkan scheduler:

**Production (cron):**
```bash
* * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
```

**Development:**
```bash
php artisan schedule:work
```

## Menjalankan Test

```bash
php artisan test --testdox
```

Atau via Composer script:

```bash
composer test
```

## Kategori Pengaduan (Seeder)

7 kategori yang di-seed secara default:

1. Layanan Dosen Pengampu Mata Kuliah
2. Layanan Dosen Wali Kelas
3. Layanan Program Studi
4. Layanan Laboratorium
5. Layanan Sarana dan Prasarana
6. Layanan Administrasi
7. Lainnya

> Admin dan Kaprodi dapat menambah, mengedit, mengaktifkan/menonaktifkan, dan menghapus kategori melalui menu Kelola Kategori.

## Alur Status Pengaduan

```
┌──────────────────────┐
│ Menunggu Verifikasi  │ ← Pengaduan baru diajukan
└──────────┬───────────┘
           │ Admin verifikasi
           ▼
┌──────────────────────┐     ┌────────────┐
│   Sedang Diproses    │────▶│  Ditolak   │
└──────────┬───────────┘     └────────────┘
           │
     ┌─────┴─────┐
     ▼           ▼
┌──────────┐  ┌───────────────────────────────┐
│ Membutuh-│  │ Menunggu Konfirmasi Mahasiswa │
│ kan Info │  └───────────────┬───────────────┘
│ Tambahan │                  │
└────┬─────┘           ┌──────┴──────┐
     │                 ▼             ▼
     │          ┌────────────┐  ┌──────────┐
     │          │  Selesai   │  │  Tolak   │
     │          │ Ditangani  │  │Konfirmasi│
     │          └────────────┘  └────┬─────┘
     │                               │
     └──── Mahasiswa balas ──────────▶ Sedang Diproses
```

- **Menunggu Verifikasi** → Admin memverifikasi dan menentukan tindak lanjut
- **Sedang Diproses** → Admin sedang menangani pengaduan
- **Membutuhkan Informasi Tambahan** → Admin meminta klarifikasi; mahasiswa membalas → kembali ke *Sedang Diproses*
- **Menunggu Konfirmasi Mahasiswa** → Admin menyatakan sudah ditangani; menunggu konfirmasi mahasiswa
- **Selesai Ditangani** → Mahasiswa mengonfirmasi, atau auto-close setelah 3 hari tanpa respons
- **Ditolak** → Pengaduan tidak dapat diproses

## Struktur Folder Singkat

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/            # Login, registrasi, verifikasi email, reset password
│   │   ├── Mahasiswa/       # Dashboard & pengaduan mahasiswa
│   │   ├── Admin/           # Dashboard, pengaduan, statistik, kelola pengguna, kelola kategori
│   │   └── Kaprodi/         # Dashboard, pengaduan (read-only), statistik, kelola pengguna, kelola kategori
│   ├── Middleware/
│   │   └── EnsureRole.php   # RBAC middleware (role:admin, role:mahasiswa, role:kaprodi)
│   └── Requests/            # Form Request (validasi server-side)
├── Models/                  # User, Pengaduan, KategoriPengaduan, StatusHistory, EmailLog
├── Services/
│   ├── PengaduanService.php # Business logic pengaduan (CRUD, update status, auto-close)
│   └── NotifikasiService.php # Dispatch email notifikasi via queue + logging
└── Mail/                    # Mailable: PengaduanDiterima, PengaduanBaruAdmin, StatusDiperbarui,
                             #           KonfirmasiDitolakAdmin, BalasanInformasiAdmin,
                             #           ResetPasswordEmail, VerifikasiEmail

resources/views/
├── layouts/                 # Layout mahasiswa, admin, & kaprodi
├── auth/                    # Halaman login, register, verifikasi, reset password
├── mahasiswa/               # Dashboard & pengaduan mahasiswa
├── admin/                   # Dashboard, pengaduan, statistik, kelola pengguna, kelola kategori
├── kaprodi/                 # Dashboard, pengaduan, statistik, kelola pengguna, kelola kategori
├── emails/                  # Template email notifikasi
├── components/              # Blade components
├── profile/                 # Halaman edit profil
└── errors/                  # Halaman error custom (403, 404)

database/
├── migrations/              # 15 migrasi (users, pengaduan, status_history, email_logs, kategori, dll.)
├── seeders/                 # UserSeeder, KategoriPengaduanSeeder, PengaduanDummySeeder
└── factories/               # Factory untuk testing

tests/
└── Feature/                 # Test: Auth, Pengaduan (mahasiswa & admin), Profile
```
