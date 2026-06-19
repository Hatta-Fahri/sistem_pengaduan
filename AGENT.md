# SILPM — Sistem Informasi Layanan Pengaduan Mahasiswa
> Politeknik Negeri Medan · Program Studi Manajemen Informatika

---

## Stack
- **Backend:** Laravel 11, arsitektur MVC + Service Layer Pattern
- **Frontend:** Blade Template Engine + Alpine.js
- **Database:** MySQL
- **Auth:** Laravel Breeze + RBAC custom (dua role: `mahasiswa`, `admin`)
- **Email:** Laravel Mail + SMTP (Mailtrap untuk dev)
- **UI:** Tailwind CSS
- **PHP:** >= 8.2

---

## Struktur Peran (RBAC)
| Role | Deskripsi |
|------|-----------|
| `mahasiswa` | Pelapor — hanya akses data miliknya sendiri |
| `admin` | Pengelola — akses penuh seluruh pengaduan |

---

## Skema Database Utama

```
users              → id, name, nim, class, email, password, role, timestamps
pengaduan          → id, user_id (FK), kategori_id (FK), tanggal_kejadian,
                     subjek, isi_pengaduan, status, catatan_admin, timestamps
kategori_pengaduan → id, nama_kategori, deskripsi, is_active, timestamps
status_history     → id, pengaduan_id (FK), status_lama, status_baru,
                     catatan, changed_by (FK users), created_at
email_logs         → id, recipient_email, subject, type, pengaduan_id (FK),
                     status (sent/failed), sent_at
```

---

## Status Alur Pengaduan

```
Menunggu Verifikasi → Sedang Diproses → Selesai Ditangani
                   ↘ Membutuhkan Informasi Tambahan → (kembali ke Sedang Diproses)
                   ↘ Ditolak
```

Nilai enum status yang valid:
- `menunggu_verifikasi`
- `sedang_diproses`
- `membutuhkan_informasi_tambahan`
- `selesai_ditangani`
- `ditolak`

---

## Kategori Pengaduan (7)

1. Layanan Dosen Pengampu Mata Kuliah
2. Layanan Dosen Wali Kelas
3. Layanan Program Studi
4. Layanan Laboratorium
5. Layanan Sarana dan Prasarana
6. Layanan Administrasi
7. Lainnya

---

## Aturan Wajib

- Seluruh form wajib validasi di sisi server menggunakan Laravel Form Request
- Isolasi data RBAC wajib diterapkan di layer Controller DAN query — jangan hanya di UI
- Setiap perubahan status pengaduan wajib mencatat ke tabel `status_history`
- Setiap perubahan status wajib trigger notifikasi email via Laravel Mail + Queue
- Password disimpan dengan bcrypt via Laravel `Hash::make()`
- Protect semua route dari CSRF, XSS, dan SQL Injection menggunakan mekanisme bawaan Laravel
- Gunakan Laravel Queue untuk pengiriman email agar tidak blocking response
- Semua migration harus bisa di-rollback bersih dengan `php artisan migrate:rollback`
- Mahasiswa hanya bisa melihat dan mengelola pengaduan miliknya sendiri
- Admin tidak bisa mengubah isi pengaduan, hanya bisa mengubah status dan catatan_admin

---

## Konvensi Kode

- Ikuti standar PSR-12 untuk PHP
- Gunakan Service Layer untuk logika bisnis — jangan taruh di Controller langsung
- Nama class, method, dan variabel menggunakan bahasa Inggris
- Komentar dan seluruh string UI, label, notifikasi menggunakan bahasa Indonesia
- Gunakan Laravel Resource untuk transformasi data jika ada API response
- Gunakan Laravel Policy untuk otorisasi akses per resource

---

## Struktur Folder yang Diharapkan

```
app/
├── Http/
│   ├── Controllers/
│   │   ├── Auth/
│   │   ├── Mahasiswa/
│   │   └── Admin/
│   ├── Middleware/
│   │   └── EnsureRole.php
│   └── Requests/
│       ├── StorePengaduanRequest.php
│       └── UpdateStatusRequest.php
├── Models/
│   ├── User.php
│   ├── Pengaduan.php
│   ├── KategoriPengaduan.php
│   ├── StatusHistory.php
│   └── EmailLog.php
├── Services/
│   ├── PengaduanService.php
│   └── NotifikasiService.php
└── Mail/
    ├── PengaduanDiterima.php
    ├── StatusDiperbarui.php
    └── PengaduanBaruAdmin.php

resources/views/
├── layouts/
│   ├── mahasiswa.blade.php
│   └── admin.blade.php
├── auth/
├── mahasiswa/
│   ├── dashboard.blade.php
│   ├── pengaduan/
│   │   ├── create.blade.php
│   │   ├── index.blade.php
│   │   └── show.blade.php
└── admin/
    ├── dashboard.blade.php
    └── pengaduan/
        ├── index.blade.php
        └── show.blade.php
```

---

## Roadmap Fase

| Fase | Waktu | Lingkup |
|------|-------|---------|
| Fase 1 | Minggu 1–2 | Auth, form pengaduan, manajemen status, RBAC dasar |
| Fase 2 | Minggu 3–4 | Notifikasi email, dashboard, riwayat pengaduan, filter & pencarian |
| Fase 3 | Minggu 5–6 | Statistik & rekap, ekspor laporan, unit test, UAT, dokumentasi |

---

## Akun Default (Seeder)

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@silpm.local | Admin123! |
| Mahasiswa (dummy) | mahasiswa1@silpm.local | Test123! |
| Mahasiswa (dummy) | mahasiswa2@silpm.local | Test123! |
