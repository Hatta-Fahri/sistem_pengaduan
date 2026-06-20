# Brief Sistem SILPM — Bahan Pembuatan UML

**Sistem Informasi Layanan Pengaduan Mahasiswa (SILPM)** — Politeknik Negeri Medan, Program Studi Manajemen Informatika. Tugas Akhir D3. Dokumen ini berisi seluruh informasi yang diperlukan untuk membuat **Use Case Diagram**, **Activity Diagram**, dan **Class Diagram** secara lengkap dan akurat, diambil langsung dari kode sumber aktual (bukan dari rancangan awal yang mungkin sudah berubah).

Stack: Laravel 13 (PHP 8.4), MySQL, Blade + Tailwind CSS + Alpine.js, Chart.js. Auth berbasis session (Laravel Breeze, dimodifikasi).

---

## 1. Aktor

| Aktor | Deskripsi |
|---|---|
| **Mahasiswa** | Pengguna terdaftar yang mengajukan dan memantau pengaduan miliknya. |
| **Admin** | Pengelola sistem yang memverifikasi, menindaklanjuti, dan merekap seluruh pengaduan. |
| **Sistem** (aktor sekunder/otomatis) | Proses terjadwal & event-driven: kirim notifikasi email, auto-close pengaduan, rate limiting. |

Catatan peran: hanya ada 2 role di tabel `users` (`mahasiswa`, `admin`). Registrasi publik hanya menghasilkan akun `mahasiswa` — akun admin dibuat manual (seeder/DB), tidak ada use case "Daftar sebagai Admin".

---

## 2. Daftar Use Case

### 2.1 Aktor: Mahasiswa
1. Registrasi Akun
2. Verifikasi Email
3. Login
4. Logout
5. Lihat Dashboard Mahasiswa (ringkasan status pengaduan milik sendiri)
6. Ajukan Pengaduan Baru *(include: Pilih Kategori, Lampirkan Bukti Pendukung [opsional], Ajukan Secara Anonim [opsional])*
7. Edit Pengaduan *(hanya selagi status = Menunggu Verifikasi)*
8. Lihat Daftar Pengaduan Milik Sendiri *(filter status, kategori, cari subjek)*
9. Lihat Detail Pengaduan *(termasuk riwayat status/timeline)*
10. Balas Permintaan Informasi Tambahan *(saat status = Membutuhkan Informasi Tambahan; teks wajib + lampiran opsional)*
11. Konfirmasi Pengaduan Selesai *(saat status = Menunggu Konfirmasi Mahasiswa)*
12. Tolak Konfirmasi Penyelesaian / Nyatakan Belum Selesai *(saat status = Menunggu Konfirmasi Mahasiswa; alasan wajib)*
13. Kelola Profil (ubah nama/password)
14. Hapus Akun Sendiri
15. Lihat/Unduh Berkas Bukti Pendukung *(milik sendiri)*

### 2.2 Aktor: Admin
1. Login
2. Logout
3. Lihat Dashboard Admin (statistik ringkas, daftar pengaduan terbaru, daftar pengaduan terlambat/overdue)
4. Lihat Daftar Seluruh Pengaduan *(filter status, kategori, rentang tanggal, cari nama/NIM/subjek — pencarian nama/NIM otomatis mengecualikan pengaduan anonim)*
5. Lihat Detail Pengaduan *(identitas pelapor disembunyikan jika anonim)*
6. Verifikasi/Update Status Pengaduan *(pilih status baru + catatan [wajib untuk status tertentu] + lampiran bukti [opsional]; tidak bisa langsung set "Selesai"; tidak bisa ubah status final)*
7. Ekspor Laporan Pengaduan ke CSV *(ikut filter yang aktif; kolom identitas anonim ditandai "Anonim")*
8. Lihat Statistik & Laporan *(filter tahun; grafik distribusi status, rekap kategori, tren 12 bulan)*
9. Kelola Kategori Pengaduan *(Tambah, Edit, Aktifkan/Nonaktifkan — tidak ada hapus permanen)*
10. Lihat Daftar Pengguna Mahasiswa *(cari nama/NIM/email, lihat status aktif/diblokir)*
11. Lihat Detail & Statistik Pengaduan per Mahasiswa
12. Nonaktifkan/Aktifkan Kembali Akun Mahasiswa (blokir akses login — bukan hapus data)
13. Lihat/Unduh Berkas Bukti Pendukung *(pengaduan apapun)*

### 2.3 Aktor: Sistem (otomatis)
1. Kirim Notifikasi Email — 5 jenis: Pengaduan Diterima (ke mahasiswa), Pengaduan Baru Masuk (ke semua admin), Status Diperbarui (ke mahasiswa), Mahasiswa Menolak Konfirmasi (ke semua admin), Mahasiswa Membalas Informasi (ke semua admin)
2. Tutup Otomatis Pengaduan (Auto-Close) — dijalankan terjadwal (`pengaduan:auto-close`, harian), menutup pengaduan yang sudah ≥3 hari di status "Menunggu Konfirmasi Mahasiswa" tanpa respons
3. Batasi Jumlah Pengaduan per Hari (Rate Limiting) — maksimal 5 pengaduan baru per mahasiswa per hari; relevan sebagai *extend*/*include* pada use case "Ajukan Pengaduan Baru"

### 2.4 Relasi antar use case yang relevan
- "Ajukan Pengaduan Baru" **include** "Pilih Kategori"; **extend** oleh "Lampirkan Bukti" dan "Ajukan Secara Anonim" (keduanya opsional)
- "Verifikasi/Update Status Pengaduan" **extend** oleh "Lampirkan Bukti" (opsional)
- "Login" **include** pengecekan role (redirect berbeda Mahasiswa vs Admin) dan pengecekan status aktif akun
- "Registrasi Akun" **include** "Verifikasi Email" sebagai langkah wajib sebelum akses penuh

---

## 3. Aturan Bisnis Penting (mempengaruhi decision point di Activity Diagram)

1. **RBAC**: middleware `role` membatasi rute mahasiswa/admin; akun nonaktif (`is_active=false`) langsung dipaksa logout begitu terdeteksi pada request apapun.
2. **Verifikasi email wajib** untuk mahasiswa sebelum mengakses dashboard/fitur apapun (middleware `verified`); akun lama (sebelum fitur ini aktif) dianggap sudah terverifikasi otomatis.
3. **Rate limit**: maksimal **5 pengaduan baru/hari/mahasiswa**.
4. **Validasi pengaduan**: kategori harus aktif; subjek 10–255 karakter; isi 30–5000 karakter; bukti opsional (jpg/jpeg/png/pdf, maks 5MB); tanggal kejadian tidak boleh di masa depan.
5. **Anonimitas (soft anonymity)**: jika `is_anonymous=true`, identitas (nama/NIM/kelas/email) disembunyikan dari SEMUA tampilan admin (daftar, detail, dashboard, email notifikasi ke admin, ekspor CSV) dan dari hasil pencarian nama/NIM oleh admin. `user_id` tetap tersimpan (untuk akuntabilitas & supaya notifikasi email tetap terkirim ke mahasiswa ybs) — bukan anonim murni tanpa akun.
6. **Alur status (state machine)**:
   - Status: `menunggu_verifikasi` (awal) → `sedang_diproses` ⇄ `membutuhkan_informasi_tambahan` → `menunggu_konfirmasi_mahasiswa` → `selesai_ditangani` **(final)**, atau `ditolak` **(final)** dari status non-final manapun.
   - Admin **tidak bisa** langsung set status "Selesai" — hanya bisa membawa ke "Menunggu Konfirmasi Mahasiswa".
   - Status final (`selesai_ditangani`, `ditolak`) **terkunci permanen**, tidak dapat diubah oleh siapapun.
   - Dari "Menunggu Konfirmasi Mahasiswa": mahasiswa konfirmasi → Selesai (final) | mahasiswa tolak (alasan wajib) → balik "Sedang Diproses" | tidak ada respons 3 hari → **auto-close oleh sistem** → Selesai (final, `changed_by=null`).
   - Dari "Membutuhkan Informasi Tambahan": mahasiswa balas (teks wajib + lampiran opsional) → otomatis balik ke "Sedang Diproses".
   - `catatan_admin` wajib diisi jika admin set status ke `ditolak` atau `membutuhkan_informasi_tambahan`.
7. **Audit trail**: setiap perubahan status (siapa pun pelakunya — mahasiswa, admin, atau sistem) tercatat di `status_history` (status lama→baru, catatan, lampiran opsional, waktu, pelaku).
8. **Edit pengaduan** oleh mahasiswa hanya bisa selagi status masih `menunggu_verifikasi`.
9. **Overdue/terlambat**: pengaduan non-final yang ≥3 hari tanpa perubahan status ditandai visual di dashboard admin (hanya visibilitas, bukan aksi otomatis — kecuali status "Menunggu Konfirmasi Mahasiswa" yang memang auto-close).
10. **Berkas bukti** disimpan di disk privat server, hanya diakses lewat route terotentikasi (pemilik pengaduan atau admin saja).
11. **Kategori** tidak bisa dihapus permanen (dibatasi foreign key) — hanya dinonaktifkan; kategori nonaktif tidak muncul di form pengaduan baru tapi tetap valid untuk data lama.
12. **Akun mahasiswa** tidak bisa dihapus permanen — hanya dinonaktifkan ("diblokir").

---

## 4. Activity Diagram — 3 Alur Utama

### 4.1 Siklus Hidup Pengaduan (alur paling penting)
```
[Mulai]
  → Mahasiswa login
  → Mahasiswa isi form pengaduan (kategori, tanggal kejadian, subjek, isi,
     bukti [opsional], anonim [opsional])
  → <<decision>> Validasi & rate limit (≤5/hari) lolos?
      − Tidak → tampilkan error → kembali ke form
      − Ya  → Simpan pengaduan (status = Menunggu Verifikasi)
            → Catat ke status_history
            → Sistem kirim email: "Diterima" (ke mahasiswa) + "Pengaduan Baru" (ke semua admin)
  → Admin login → buka daftar pengaduan → buka detail
  → <<decision>> Admin pilih tindakan
      − Set "Sedang Diproses"
          → catat history → email mahasiswa → (kembali ke "Admin pilih tindakan")
      − Set "Membutuhkan Informasi Tambahan" (+catatan wajib, +lampiran opsional)
          → catat history → email mahasiswa
          → Mahasiswa balas (teks wajib + lampiran opsional)
          → otomatis pindah "Sedang Diproses" → email admin
          → (kembali ke "Admin pilih tindakan")
      − Set "Ditolak" (+catatan wajib)
          → catat history → email mahasiswa → [Selesai — status final, terkunci]
      − Set "Menunggu Konfirmasi Mahasiswa" (+lampiran opsional)
          → catat history → email mahasiswa (berisi deadline 3 hari)
          → <<decision>> Respons mahasiswa dalam 3 hari?
              − Mahasiswa konfirmasi selesai
                  → status "Selesai Ditangani" → [Selesai — final, terkunci]
              − Mahasiswa tolak (+alasan wajib)
                  → balik "Sedang Diproses" → email admin → (kembali ke "Admin pilih tindakan")
              − Tidak ada respons >3 hari
                  → Sistem (job terjadwal) auto-close
                  → status "Selesai Ditangani" (catatan otomatis, pelaku = Sistem)
                  → email mahasiswa → [Selesai — final, terkunci]
[Akhir]
```

### 4.2 Registrasi & Verifikasi Email
```
[Mulai]
  → Calon mahasiswa isi form registrasi (nama, NIM, kelas, email, password)
  → <<decision>> Validasi lolos? (NIM unik, email unik, password sesuai kebijakan)
      − Tidak → tampilkan error → kembali ke form
      − Ya  → Buat akun (role=mahasiswa, is_active=true, email_verified_at=null)
            → Sistem kirim email verifikasi
            → Auto-login
  → <<decision>> Akun sudah verifikasi email?
      − Belum → redirect ke halaman "Verifikasi Email" (bisa kirim ulang)
              → Mahasiswa klik link verifikasi di email
              → Sistem tandai email_verified_at = sekarang
              → redirect ke Dashboard Mahasiswa
      − Sudah → redirect ke Dashboard Mahasiswa
[Akhir]
```

### 4.3 Login & Pemeriksaan Akses (RBAC + status aktif akun)
```
[Mulai]
  → User isi form login (email, password)
  → <<decision>> Kredensial valid? (maks 5x percobaan, lalu rate-limited)
      − Tidak → tampilkan error
      − Ya  → <<decision>> Role user?
          − Admin → redirect Dashboard Admin → [Akhir]
          − Mahasiswa → <<decision>> Email terverifikasi?
              − Belum → redirect halaman Verifikasi Email → [Akhir]
              − Sudah → <<decision>> Akun aktif (is_active)?
                  − Tidak (diblokir) → paksa logout otomatis
                                      → pesan "akun dinonaktifkan"
                                      → redirect Login → [Akhir]
                  − Aktif → redirect Dashboard Mahasiswa → [Akhir]
```

---

## 5. Class Diagram — Struktur Entitas

### Entitas: `User`
| Atribut | Tipe |
|---|---|
| id | bigint (PK) |
| name | string |
| nim | string(20), nullable, unique |
| class | string(50), nullable *(nama kolom DB; representasikan sebagai "kelas" di diagram bila perlu hindari reserved word)* |
| email | string, unique |
| email_verified_at | timestamp, nullable |
| password | string (hashed) |
| role | enum('mahasiswa','admin') |
| is_active | boolean, default true |
| created_at, updated_at | timestamp |

**Method**: `isAdmin()`, `isMahasiswa()`, `isActive()`
**Relasi**: `User (1) — (N) Pengaduan` (sebagai pelapor) · `User (1) — (N) StatusHistory` (sebagai pelaku perubahan, nullable)

### Entitas: `Pengaduan`
| Atribut | Tipe |
|---|---|
| id | bigint (PK) |
| user_id | bigint (FK → User) |
| kategori_id | bigint (FK → KategoriPengaduan) |
| is_anonymous | boolean, default false |
| tanggal_kejadian | datetime |
| subjek | string(255) |
| isi_pengaduan | text |
| bukti | string, nullable (path file) |
| status | string(50) |
| catatan_admin | text, nullable |
| created_at, updated_at | timestamp |

**Konstanta status**: `menunggu_verifikasi`, `sedang_diproses`, `membutuhkan_informasi_tambahan`, `menunggu_konfirmasi_mahasiswa`, `selesai_ditangani`, `ditolak` (2 terakhir = final). SLA = 3 hari.
**Method**: `isFinal()`, `isOverdue()` *(accessor)*, `getBuktiUrlAttribute()`, `statusLabels()` *(static)*, `statusColors()` *(static)*
**Relasi**: `Pengaduan (N) — (1) User` · `Pengaduan (N) — (1) KategoriPengaduan` · `Pengaduan (1) — (N) StatusHistory` · `Pengaduan (1) — (N) EmailLog`

### Entitas: `KategoriPengaduan`
| Atribut | Tipe |
|---|---|
| id | bigint (PK) |
| nama_kategori | string(100), unique |
| deskripsi | text, nullable |
| is_active | boolean, default true |
| created_at, updated_at | timestamp |

**Relasi**: `KategoriPengaduan (1) — (N) Pengaduan`

### Entitas: `StatusHistory`
| Atribut | Tipe |
|---|---|
| id | bigint (PK) |
| pengaduan_id | bigint (FK → Pengaduan) |
| status_lama | string(50), nullable |
| status_baru | string(50) |
| catatan | text, nullable |
| bukti | string, nullable (path file lampiran) |
| changed_by | bigint (FK → User), nullable *(null = aksi otomatis sistem)* |
| created_at | timestamp *(immutable, tidak ada updated_at)* |

**Method**: `getStatusBaruLabelAttribute()`, `getStatusLamaLabelAttribute()`, `getBuktiUrlAttribute()`
**Relasi**: `StatusHistory (N) — (1) Pengaduan` · `StatusHistory (N) — (1) User` *(nullable)*

### Entitas: `EmailLog`
| Atribut | Tipe |
|---|---|
| id | bigint (PK) |
| recipient_email | string |
| subject | string(255) |
| type | string(50) — salah satu: `pengaduan_diterima`, `pengaduan_baru_admin`, `status_diperbarui`, `konfirmasi_ditolak_admin`, `balasan_informasi_admin` |
| pengaduan_id | bigint (FK → Pengaduan), nullable |
| status | enum('sent','failed') |
| sent_at | timestamp, nullable |

**Relasi**: `EmailLog (N) — (1) Pengaduan` *(nullable)*

### Kelas Layer Servis (opsional, jika Class Diagram mencakup arsitektur, bukan murni entitas data)
- **PengaduanService**: `createPengaduan()`, `updatePengaduan()`, `updateStatus()`, `balasInformasiTambahan()`, `konfirmasiSelesai()`, `tolakKonfirmasi()`, `autoCloseStale()`
- **NotifikasiService**: `kirimPengaduanDiterima()`, `kirimPengaduanBaruAdmin()`, `kirimStatusDiperbarui()`, `kirimKonfirmasiDitolakAdmin()`, `kirimBalasanInformasiAdmin()`

Kedua service ini dipanggil oleh Controller (`Mahasiswa\PengaduanController`, `Admin\PengaduanController`) — relevan jika ingin menggambarkan dependency antar layer (Controller → Service → Model) di Class Diagram arsitektural, terpisah dari Class Diagram domain/entitas murni di atas.

---

## 6. Ringkasan Modul/Controller (referensi tambahan)

| Controller | Tanggung jawab |
|---|---|
| `Auth\RegisteredUserController`, `AuthenticatedSessionController`, dll (Breeze) | Registrasi, login/logout, verifikasi email, reset password |
| `Mahasiswa\DashboardController` | Dashboard ringkasan mahasiswa |
| `Mahasiswa\PengaduanController` | CRUD pengaduan milik sendiri, konfirmasi/tolak/balas-informasi |
| `Admin\DashboardController` | Dashboard ringkasan admin + daftar overdue |
| `Admin\PengaduanController` | Daftar/detail seluruh pengaduan, update status, ekspor CSV |
| `Admin\StatistikController` | Rekap & grafik statistik (filter tahun) |
| `Admin\KategoriPengaduanController` | CRUD kategori pengaduan |
| `Admin\UserController` | Daftar/detail mahasiswa, blokir/aktifkan akun |
| `BuktiController` | Sajikan file bukti pendukung (terotentikasi) |
| `ProfileController` | Kelola profil mahasiswa |

---

*Dokumen ini dihasilkan dari pemeriksaan langsung terhadap kode sumber (routes, models, controllers, services, migrations) pada tanggal pembuatan brief — bukan dari dokumen rancangan awal yang mungkin sudah tidak sinkron dengan implementasi aktual.*
