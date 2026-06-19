# SILPM — Sistem Informasi Layanan Pengaduan Mahasiswa

Aplikasi web untuk pengelolaan pengaduan mahasiswa di Program Studi Manajemen
Informatika, Politeknik Negeri Medan. Mahasiswa dapat mengajukan dan memantau
pengaduan, sementara admin dapat memverifikasi, memproses, dan merekap seluruh
pengaduan yang masuk.

## Fitur Utama

- Autentikasi & RBAC dua role (`mahasiswa`, `admin`)
- Pengajuan, filter, pencarian, dan riwayat status pengaduan
- Notifikasi email otomatis via Laravel Queue (diterima, status diperbarui, pengaduan baru ke admin)
- Dashboard statistik untuk mahasiswa dan admin
- Statistik & rekap (grafik Chart.js) serta ekspor laporan ke CSV
- Manajemen akun mahasiswa (nonaktifkan akun)

## Requirements

- PHP >= 8.2
- Composer
- MySQL
- Node.js (opsional, hanya untuk build asset frontend)

## Instalasi

```bash
git clone <url-repo-ini>
cd silpm

composer install

cp .env.example .env
php artisan key:generate
```

Buka file `.env` dan sesuaikan konfigurasi database:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=silpm
DB_USERNAME=root
DB_PASSWORD=
```

Jalankan migrasi beserta seeder data awal (kategori pengaduan + akun default):

```bash
php artisan migrate --seed
```

Jalankan server pengembangan:

```bash
php artisan serve
```

Aplikasi dapat diakses di `http://localhost:8000`.

## Menjalankan Queue Worker

Notifikasi email dikirim secara asynchronous melalui Laravel Queue
(`QUEUE_CONNECTION=database`). Jalankan worker di terminal terpisah agar email
benar-benar terkirim:

```bash
php artisan queue:work
```

## Menjalankan Test

```bash
php artisan test --testdox
```

## Akun Default (Seeder)

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@silpm.local | Admin123! |
| Mahasiswa (dummy) | mahasiswa1@silpm.local | Test123! |
| Mahasiswa (dummy) | mahasiswa2@silpm.local | Test123! |
| Mahasiswa (dummy) | mahasiswa3@silpm.local | Test123! |

## Struktur Folder Singkat

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/        # Login, registrasi, verifikasi email
│   │   ├── Mahasiswa/    # Dashboard & pengaduan mahasiswa
│   │   └── Admin/        # Dashboard, pengaduan, statistik, kelola pengguna
│   ├── Middleware/
│   │   └── EnsureRole.php
│   └── Requests/         # Form Request (validasi server-side)
├── Models/                # User, Pengaduan, KategoriPengaduan, StatusHistory, EmailLog
├── Services/              # PengaduanService, NotifikasiService (business logic)
└── Mail/                  # Mailable notifikasi email

resources/views/
├── layouts/               # Layout mahasiswa & admin
├── auth/
├── mahasiswa/             # Dashboard & pengaduan mahasiswa
├── admin/                 # Dashboard, pengaduan, statistik, kelola pengguna
├── emails/                # Template email notifikasi
└── errors/                # Halaman error custom (403, 404)

database/
├── migrations/
├── seeders/
└── factories/

tests/
└── Feature/               # Test registrasi, pengaduan mahasiswa, pengaduan admin
```
