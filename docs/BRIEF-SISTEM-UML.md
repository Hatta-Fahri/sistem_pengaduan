# Brief Sistem SILPM — Bahan Pembuatan UML

**Sistem Informasi Layanan Pengaduan Mahasiswa (SILPM)** — Politeknik Negeri Medan, Program Studi Manajemen Informatika. Tugas Akhir D3. Dokumen ini berisi seluruh informasi yang diperlukan untuk membuat **Use Case Diagram**, **Activity Diagram**, dan **Class Diagram** secara lengkap dan akurat, diambil langsung dari kode sumber aktual (bukan dari rancangan awal yang mungkin sudah berubah).

Stack: Laravel 13 (PHP 8.4), MySQL, Blade + Tailwind CSS + Alpine.js, Chart.js. Auth berbasis session (Laravel Breeze, dimodifikasi). Email via Laravel Queue (async).

---

## 1. Aktor

| Aktor | Deskripsi |
|---|---|
| **Mahasiswa** | Pengguna terdaftar yang mengajukan dan memantau pengaduan miliknya. Registrasi publik. |
| **Admin** | Pengelola sistem yang memverifikasi, menindaklanjuti, mengelola kategori & pengguna, dan merekap seluruh pengaduan. Dibuat manual (seeder/DB). |
| **Kaprodi** | Kepala Program Studi. Akses **read-only** terhadap seluruh pengaduan & statistik. Dapat mengelola kategori dan menonaktifkan/mengaktifkan akun mahasiswa, **tidak bisa mengubah status pengaduan**. Dibuat manual (seeder/DB). |
| **Sistem** (aktor sekunder/otomatis) | Proses terjadwal & event-driven: kirim notifikasi email via queue, auto-close pengaduan, rate limiting. |

Catatan peran: ada **3 role** di tabel `users` (`mahasiswa`, `admin`, `kaprodi`). Registrasi publik hanya menghasilkan akun `mahasiswa`. Redirect setelah login berbeda per role.

---

## 2. Daftar Use Case

### 2.1 Aktor: Mahasiswa
1. Registrasi Akun *(nama, NIM, kelas, email, password)*
2. Verifikasi Email *(link dikirim otomatis setelah registrasi; bisa kirim ulang)*
3. Login
4. Logout
5. Lihat Dashboard Mahasiswa *(ringkasan statistik status pengaduan milik sendiri + 5 pengaduan terbaru)*
6. Ajukan Pengaduan Baru *(include: Pilih Kategori, isi subjek & detail; extend: Lampirkan Bukti [opsional, jpg/jpeg/png/pdf <=5MB]; extend: Ajukan Secara Anonim [opsional])*
7. Edit Pengaduan *(hanya selagi status = Menunggu Verifikasi; semua field bisa diubah termasuk opsi anonim dan bukti)*
8. Lihat Daftar Pengaduan Milik Sendiri *(filter status, kategori, cari subjek; pagination)*
9. Lihat Detail Pengaduan *(termasuk riwayat status/timeline lengkap: pelaku, catatan, lampiran, timestamp)*
10. Balas Permintaan Informasi Tambahan *(saat status = Membutuhkan Informasi Tambahan; teks balasan wajib + lampiran opsional -> otomatis balik ke Sedang Diproses)*
11. Konfirmasi Pengaduan Selesai *(saat status = Menunggu Konfirmasi Mahasiswa -> status final Selesai Ditangani)*
12. Tolak Konfirmasi Penyelesaian *(saat status = Menunggu Konfirmasi Mahasiswa; alasan wajib -> balik ke Sedang Diproses)*
13. Kelola Profil *(ubah nama & email; NIM & Kelas tidak bisa diubah)*
14. Ubah Password
15. Hapus Akun Sendiri
16. Lihat/Unduh Berkas Bukti Pendukung *(milik sendiri; akses via route terotentikasi)*

### 2.2 Aktor: Admin
1. Login
2. Logout
3. Lihat Dashboard Admin *(statistik ringkas per status [total, menunggu, diproses, butuh info, menunggu konfirmasi, selesai, ditolak, overdue] + 10 pengaduan terbaru + daftar <=5 pengaduan overdue)*
4. Lihat Daftar Seluruh Pengaduan *(filter status, kategori, rentang tanggal created_at, cari nama/NIM/subjek; pencarian nama/NIM mengecualikan pengaduan anonim; pagination 15/halaman)*
5. Lihat Detail Pengaduan *(identitas pelapor disembunyikan jika anonim; riwayat timeline lengkap)*
6. Verifikasi/Update Status Pengaduan *(pilih status baru + catatan [wajib jika Ditolak atau Butuh Info] + lampiran bukti [opsional]; tidak bisa langsung set "Selesai Ditangani"; tidak bisa ubah status final)*
7. Ekspor Laporan ke CSV *(filter: status, kategori, tanggal_dari, tanggal_sampai; kolom identitas anonim ditandai "Anonim"; BOM UTF-8)*
8. Lihat Statistik & Laporan *(filter periode: 7 hari terakhir, bulanan, tahunan, custom; grafik doughnut status, bar kategori, line tren; tabel detail; ekspor PDF)*
9. Kelola Kategori Pengaduan *(Tambah, Edit, Aktifkan/Nonaktifkan, Hapus — hapus permanen hanya jika belum ada pengaduan terkait)*
10. Lihat Daftar Pengguna Mahasiswa *(pagination; tampilkan jumlah pengaduan per user)*
11. Lihat Detail & Statistik Pengaduan per Mahasiswa
12. Nonaktifkan/Aktifkan Kembali Akun Mahasiswa *(toggle is_active; blokir login)*
13. Lihat/Unduh Berkas Bukti Pendukung *(semua pengaduan + lampiran riwayat status)*

### 2.3 Aktor: Kaprodi
1. Login
2. Logout
3. Lihat Dashboard Kaprodi *(statistik seluruh pengaduan per status + 10 pengaduan terbaru + daftar <=5 overdue — semua READ-ONLY)*
4. Lihat Statistik & Laporan *(filter periode identik dengan admin; grafik + tabel + ekspor PDF — READ-ONLY)*
5. Ekspor Laporan ke CSV *(menggunakan fungsi ekspor Admin)*
6. Lihat Detail Pengaduan *(READ-ONLY; tidak ada form update status apapun)*
7. Kelola Kategori Pengaduan *(Tambah, Edit, Aktifkan/Nonaktifkan, Hapus — identik dengan admin)*
8. Lihat Daftar Pengguna Mahasiswa
9. Lihat Detail & Statistik Pengaduan per Mahasiswa
10. Nonaktifkan/Aktifkan Kembali Akun Mahasiswa *(toggle is_active)*
11. Lihat/Unduh Berkas Bukti Pendukung

### 2.4 Aktor: Sistem (otomatis)
1. Kirim Notifikasi Email via Queue (5 jenis):
   - `pengaduan_diterima` ke mahasiswa pelapor saat pengaduan berhasil dibuat
   - `pengaduan_baru_admin` ke semua user role `admin` saat pengaduan baru masuk
   - `status_diperbarui` ke mahasiswa saat admin mengubah status (termasuk auto-close)
   - `konfirmasi_ditolak_admin` ke semua admin saat mahasiswa menolak konfirmasi
   - `balasan_informasi_admin` ke semua admin saat mahasiswa membalas permintaan informasi
2. Tutup Otomatis Pengaduan (Auto-Close) — command `pengaduan:auto-close`, dijadwalkan harian, menutup pengaduan >=3 hari di status "Menunggu Konfirmasi Mahasiswa" tanpa respons -> "Selesai Ditangani" dengan `changed_by = null`
3. Batasi Jumlah Pengaduan per Hari (Rate Limiting) — throttle `pengaduan-submit`; maksimal 5 pengaduan baru per mahasiswa per hari

### 2.5 Relasi antar use case yang relevan
- "Ajukan Pengaduan Baru" **include** "Pilih Kategori"; **extend** oleh "Lampirkan Bukti" dan "Ajukan Secara Anonim" (opsional)
- "Verifikasi/Update Status" **extend** oleh "Lampirkan Bukti Admin" (opsional)
- "Balas Informasi Tambahan" **extend** oleh "Lampirkan Bukti Balasan" (opsional)
- "Login" **include** pengecekan role (redirect berbeda: Mahasiswa /mahasiswa/dashboard, Admin /admin/dashboard, Kaprodi /kaprodi/dashboard) dan pengecekan is_active
- "Ajukan Pengaduan Baru" **extend** oleh "Rate Limit 5/hari" (sistem cek otomatis)

---

## 3. Aturan Bisnis Penting

1. **RBAC**: middleware `role` membatasi rute per peran. Akun nonaktif (`is_active=false`) langsung dipaksa logout pada request apapun.
2. **Verifikasi email**: middleware `verified` ada di beberapa route mahasiswa. User lama di-backfill `email_verified_at` via migration. Admin dan Kaprodi tidak diwajibkan verifikasi email.
3. **Rate limit**: maksimal **5 pengaduan baru/hari/mahasiswa** (throttle `pengaduan-submit`).
4. **Validasi pengaduan**: kategori harus ada di DB; subjek 10-255 karakter; isi 30-5.000 karakter; bukti opsional (jpg/jpeg/png/pdf, maks 5MB); tanggal kejadian tidak boleh di masa depan (`before_or_equal:today`).
5. **Anonimitas (soft anonymity)**: jika `is_anonymous=true`, identitas (nama/NIM/kelas/email) disembunyikan dari SEMUA tampilan admin & kaprodi (daftar, detail, dashboard, email notifikasi, ekspor CSV). Pencarian nama/NIM mengecualikan pengaduan anonim. `user_id` tetap tersimpan untuk notifikasi dan akuntabilitas.
6. **Alur status (state machine)**:
   - Urutan: `menunggu_verifikasi` (awal) -> `sedang_diproses` <-> `membutuhkan_informasi_tambahan` -> `menunggu_konfirmasi_mahasiswa` -> `selesai_ditangani` (FINAL), atau `ditolak` (FINAL) dari status non-final manapun.
   - Admin TIDAK BISA langsung set "Selesai Ditangani" — harus lewat "Menunggu Konfirmasi Mahasiswa".
   - Kaprodi TIDAK BISA mengubah status apapun.
   - Status final (`selesai_ditangani`, `ditolak`) terkunci permanen.
   - Dari "Menunggu Konfirmasi": mahasiswa konfirmasi -> Selesai (final) | mahasiswa tolak (alasan wajib) -> Sedang Diproses + notif admin | tidak ada respons >=3 hari -> auto-close sistem -> Selesai (changed_by=null).
   - Dari "Membutuhkan Informasi": mahasiswa balas (teks wajib + lampiran opsional) -> Sedang Diproses + notif admin.
   - `catatan_admin` WAJIB jika set status ke `ditolak` atau `membutuhkan_informasi_tambahan`.
7. **Audit trail**: setiap perubahan status tercatat di `status_history` (status lama/baru, catatan, lampiran, waktu, pelaku). `changed_by=null` berarti sistem.
8. **Edit pengaduan** hanya selagi `menunggu_verifikasi`. File bukti lama dihapus otomatis jika diganti.
9. **Overdue**: non-final >=3 hari tanpa update `updated_at` ditandai visual di dashboard (hanya label, bukan aksi — kecuali "Menunggu Konfirmasi" yang auto-close).
10. **Berkas bukti** di disk privat (`storage/app/bukti-pengaduan/`), diakses via route terotentikasi `bukti.pengaduan` dan `bukti.riwayat`. Hanya pemilik/admin/kaprodi.
11. **Kategori** tidak bisa dihapus jika masih ada pengaduan terkait (FK restrict); hanya nonaktifkan. Nonaktif tidak muncul di form baru.
12. **Akun mahasiswa** tidak bisa dihapus admin/kaprodi — hanya toggle `is_active`. Mahasiswa bisa hapus akun sendiri via profil.
13. **Email via Laravel Queue** (async, non-blocking) dengan `Mail::to()->queue()`. Setiap pengiriman dicatat ke `email_logs`.

---

## 4. Activity Diagram — Alur Utama

### 4.1 Siklus Hidup Pengaduan
```
[Mulai]
  -> Mahasiswa login (cek role + email verified + is_active)
  -> Mahasiswa isi form pengaduan (kategori, tanggal kejadian, subjek, isi, bukti [opsional], anonim [opsional])
  -> <<decision>> Validasi & rate limit (<=5/hari) lolos?
      - Tidak -> tampilkan error -> kembali ke form
      - Ya  -> Simpan pengaduan (status = Menunggu Verifikasi)
            -> Simpan file bukti ke disk privat (jika ada)
            -> Catat ke status_history (status_lama=null, changed_by=mahasiswa_id)
            -> Queue: email "Diterima" ke mahasiswa
            -> Queue: email "Pengaduan Baru" ke semua admin
  -> Admin/Kaprodi login -> buka daftar pengaduan -> buka detail
  -> <<decision>> User adalah Admin?
      - Kaprodi -> hanya bisa melihat (read-only, tidak ada form update status)
      - Admin -> <<decision>> Admin pilih tindakan
          - Set "Sedang Diproses" (+catatan opsional, +lampiran opsional)
              -> catat history -> Queue: email mahasiswa -> (kembali ke pilih tindakan)
          - Set "Membutuhkan Informasi Tambahan" (+catatan WAJIB, +lampiran opsional)
              -> catat history -> Queue: email mahasiswa
              -> Mahasiswa balas (teks wajib + lampiran opsional)
              -> otomatis pindah ke "Sedang Diproses"
              -> catat history (changed_by=mahasiswa_id)
              -> Queue: email balasan ke semua admin
              -> (kembali ke pilih tindakan)
          - Set "Ditolak" (+catatan WAJIB, +lampiran opsional)
              -> catat history -> Queue: email mahasiswa
              -> [Selesai - status final, terkunci]
          - Set "Menunggu Konfirmasi Mahasiswa" (+catatan opsional, +lampiran opsional)
              -> catat history -> Queue: email mahasiswa (berisi batas 3 hari)
              -> <<decision>> Respons mahasiswa dalam 3 hari?
                  - Mahasiswa konfirmasi selesai
                      -> status "Selesai Ditangani"
                      -> catat history (changed_by=mahasiswa_id)
                      -> [Selesai - final, terkunci]
                  - Mahasiswa tolak (+alasan WAJIB)
                      -> balik "Sedang Diproses"
                      -> catat history (changed_by=mahasiswa_id)
                      -> Queue: email tolak ke semua admin
                      -> (kembali ke pilih tindakan)
                  - Tidak ada respons >=3 hari
                      -> Sistem (command `pengaduan:auto-close`) auto-close
                      -> status "Selesai Ditangani"
                      -> catat history (catatan otomatis, changed_by=null)
                      -> Queue: email status diperbarui ke mahasiswa
                      -> [Selesai - final, terkunci]
[Akhir]
```

### 4.2 Registrasi & Verifikasi Email
```
[Mulai]
  -> Calon mahasiswa isi form registrasi (nama, NIM, kelas, email, password)
  -> <<decision>> Validasi lolos? (NIM unik, email unik, password sesuai kebijakan)
      - Tidak -> tampilkan error -> kembali ke form
      - Ya  -> Buat akun (role=mahasiswa, is_active=true, email_verified_at=null)
            -> Auto-login
            -> Sistem kirim email verifikasi (via queue)
  -> <<decision>> Middleware 'verified' aktif untuk route yang diakses?
      - Ya & belum verifikasi -> redirect ke halaman "Verifikasi Email"
                              -> Mahasiswa klik link verifikasi di email
                              -> Sistem tandai email_verified_at = sekarang
                              -> redirect ke Dashboard Mahasiswa
      - Tidak -> redirect ke Dashboard Mahasiswa langsung
[Akhir]
```

### 4.3 Login & Pemeriksaan Akses
```
[Mulai]
  -> User isi form login (email, password)
  -> <<decision>> Kredensial valid? (maks 5x percobaan, lalu rate-limited oleh Breeze)
      - Tidak -> tampilkan error
      - Ya  -> <<decision>> Role user?
          - Admin   -> redirect /admin/dashboard -> [Akhir]
          - Kaprodi -> redirect /kaprodi/dashboard -> [Akhir]
          - Mahasiswa -> <<decision>> is_active?
              - Tidak (diblokir) -> paksa logout otomatis
                                  -> pesan "akun dinonaktifkan"
                                  -> redirect /login -> [Akhir]
              - Aktif -> redirect /mahasiswa/dashboard -> [Akhir]
              (pemeriksaan is_active juga terjadi pada setiap request via middleware)
[Akhir]
```

---

## 5. Class Diagram — Struktur Entitas

### Entitas: `User`
| Atribut | Tipe |
|---|---|
| id | bigint (PK, auto-increment) |
| name | string |
| nim | string(20), nullable, unique |
| class | string(50), nullable *(kolom DB; tampilkan sebagai "kelas" di diagram)* |
| email | string, unique |
| email_verified_at | timestamp, nullable |
| password | string (bcrypt hashed) |
| role | enum('mahasiswa', 'admin', 'kaprodi') |
| is_active | boolean, default true |
| remember_token | string, nullable |
| created_at, updated_at | timestamp |

**Method**: `isAdmin(): bool`, `isMahasiswa(): bool`, `isKaprodi(): bool`, `isActive(): bool`

**Relasi**:
- `User (1) — (N) Pengaduan` (sebagai pelapor)
- `User (1) — (N) StatusHistory` (sebagai pelaku perubahan, nullable)

---

### Entitas: `Pengaduan`
| Atribut | Tipe |
|---|---|
| id | bigint (PK, auto-increment) |
| user_id | bigint (FK -> User) |
| kategori_id | bigint (FK -> KategoriPengaduan) |
| is_anonymous | boolean, default false |
| tanggal_kejadian | datetime |
| subjek | string(255) |
| isi_pengaduan | text |
| bukti | string, nullable *(path relatif disk privat)* |
| status | string(50) |
| catatan_admin | text, nullable |
| created_at, updated_at | timestamp |

**Konstanta status**:
- `STATUS_MENUNGGU = 'menunggu_verifikasi'`
- `STATUS_DIPROSES = 'sedang_diproses'`
- `STATUS_BUTUH_INFO = 'membutuhkan_informasi_tambahan'`
- `STATUS_MENUNGGU_KONFIRMASI = 'menunggu_konfirmasi_mahasiswa'`
- `STATUS_SELESAI = 'selesai_ditangani'` *(final)*
- `STATUS_DITOLAK = 'ditolak'` *(final)*
- `STATUS_FINAL = [STATUS_SELESAI, STATUS_DITOLAK]`
- `SLA_HARI = 3`

**Method**:
- `isFinal(): bool`
- `getIsOverdueAttribute(): bool` *(accessor)*
- `getBuktiUrlAttribute(): ?string` *(accessor)*
- `getStatusLabelAttribute(): string` *(accessor)*
- `statusLabels(): array` *(static)*
- `statusColors(): array` *(static)*

**Query Scopes**: `scopeByStatus()`, `scopeByKategori()`, `scopeMilikSaya()`, `scopeOverdue()`

**Relasi**:
- `Pengaduan (N) — (1) User`
- `Pengaduan (N) — (1) KategoriPengaduan`
- `Pengaduan (1) — (N) StatusHistory`
- `Pengaduan (1) — (N) EmailLog`

---

### Entitas: `KategoriPengaduan`
| Atribut | Tipe |
|---|---|
| id | bigint (PK, auto-increment) |
| nama_kategori | string(100), unique |
| deskripsi | text, nullable |
| is_active | boolean, default true |
| created_at, updated_at | timestamp |

**Query Scopes**: `scopeActive()`

**Relasi**: `KategoriPengaduan (1) — (N) Pengaduan`

---

### Entitas: `StatusHistory`
| Atribut | Tipe |
|---|---|
| id | bigint (PK, auto-increment) |
| pengaduan_id | bigint (FK -> Pengaduan) |
| status_lama | string(50), nullable *(null = status awal saat dibuat)* |
| status_baru | string(50) |
| catatan | text, nullable |
| bukti | string, nullable *(path file lampiran)* |
| changed_by | bigint (FK -> User), nullable *(null = aksi otomatis sistem)* |
| created_at | timestamp *(immutable, tidak ada updated_at)* |

**Method (accessor)**:
- `getStatusBaruLabelAttribute(): string`
- `getStatusLamaLabelAttribute(): string`
- `getBuktiUrlAttribute(): ?string`

**Relasi**:
- `StatusHistory (N) — (1) Pengaduan`
- `StatusHistory (N) — (1) User` *(nullable)*

---

### Entitas: `EmailLog`
| Atribut | Tipe |
|---|---|
| id | bigint (PK, auto-increment) |
| recipient_email | string |
| subject | string(255) |
| type | string(50) — nilai: `pengaduan_diterima`, `pengaduan_baru_admin`, `status_diperbarui`, `konfirmasi_ditolak_admin`, `balasan_informasi_admin` |
| pengaduan_id | bigint (FK -> Pengaduan), nullable |
| status | enum('sent', 'failed') |
| sent_at | timestamp, nullable |

**Relasi**: `EmailLog (N) — (1) Pengaduan` *(nullable)*

---

### Kelas Layer Servis

**`PengaduanService`** — orkestrasi logika bisnis pengaduan (dipanggil Controller):
- `createPengaduan(array $data, int $userId): Pengaduan` — simpan pengaduan + history + kirim notifikasi, dalam DB transaction
- `updatePengaduan(Pengaduan $pengaduan, array $data): Pengaduan` — update isi + ganti file bukti (hapus lama), dalam DB transaction
- `updateStatus(Pengaduan, string $statusBaru, ?string $catatan, int $adminId, ?UploadedFile $bukti): Pengaduan` — ganti status + history + notifikasi, dalam DB transaction
- `balasInformasiTambahan(Pengaduan, string $balasan, ?UploadedFile $bukti, int $mahasiswaId): Pengaduan` — balik ke Sedang Diproses + history + notif admin
- `konfirmasiSelesai(Pengaduan, int $mahasiswaId): Pengaduan` — set Selesai + history
- `tolakKonfirmasi(Pengaduan, string $alasan, int $mahasiswaId): Pengaduan` — balik ke Sedang Diproses + notif admin
- `autoCloseStale(): int` — batch close pengaduan stale, return jumlah ditutup

**`NotifikasiService`** — kirim email via queue (dipanggil PengaduanService):
- `kirimPengaduanDiterima(Pengaduan): void`
- `kirimPengaduanBaruAdmin(Pengaduan): void` *(ke semua role admin)*
- `kirimStatusDiperbarui(Pengaduan, string $statusLama): void`
- `kirimKonfirmasiDitolakAdmin(Pengaduan, string $alasan): void` *(ke semua admin)*
- `kirimBalasanInformasiAdmin(Pengaduan, string $balasan): void` *(ke semua admin)*
- `catatEmailLog(...): void` *(protected helper, catat ke email_logs)*

---

## 6. Ringkasan Modul/Controller

### Mahasiswa
| Controller | Tanggung Jawab |
|---|---|
| `Mahasiswa\DashboardController` | Dashboard: statistik per status + 5 pengaduan terbaru |
| `Mahasiswa\PengaduanController` | index, create, store, show, edit, update, konfirmasiSelesai, tolakKonfirmasi, balasInformasi |
| `ProfileController` | edit, update (nama+email), destroy (hapus akun sendiri) |

### Admin
| Controller | Tanggung Jawab |
|---|---|
| `Admin\DashboardController` | Dashboard: statistik + 10 terbaru + 5 overdue |
| `Admin\PengaduanController` | index (filter+paginate), show, updateStatus, export (CSV) |
| `Admin\StatistikController` | index (grafik+tabel, filter periode), exportPdf |
| `Admin\KategoriPengaduanController` | index, create, store, edit, update, toggleActive, destroy |
| `Admin\UserController` | index, show (detail+statistik mahasiswa), toggleActive |

### Kaprodi
| Controller | Tanggung Jawab |
|---|---|
| `Kaprodi\DashboardController` | Dashboard: statistik + 10 terbaru + 5 overdue (read-only) |
| `Kaprodi\PengaduanController` | show (read-only, tidak ada updateStatus) |
| `Kaprodi\StatistikController` | index (grafik+tabel, filter periode), exportPdf |
| `Kaprodi\KategoriPengaduanController` | index, create, store, edit, update, toggleActive, destroy |
| `Kaprodi\UserController` | index, show, toggleActive |

### Shared / Umum
| Controller | Tanggung Jawab |
|---|---|
| `BuktiController` | `pengaduan(Pengaduan)` & `riwayat(StatusHistory)` — sajikan file dari disk privat |
| Auth (Breeze) | RegisteredUserController, AuthenticatedSessionController, EmailVerificationController, PasswordResetController, dll |

---

## 7. Rute Penting

| Method | URI | Nama Route | Keterangan |
|---|---|---|---|
| GET | `/` | — | Redirect berdasarkan role, atau ke login |
| GET/POST | `/login`, `/logout` | `login`, `logout` | Auth Breeze |
| GET/POST | `/register` | `register` | Registrasi mahasiswa |
| GET | `/verify-email` | `verification.notice` | Halaman verifikasi email |
| GET | `/verify-email/{id}/{hash}` | `verification.verify` | Klik link verifikasi |
| POST | `/email/verification-notification` | `verification.send` | Kirim ulang email verifikasi |
| GET | `/bukti/pengaduan/{pengaduan}` | `bukti.pengaduan` | Download bukti pengaduan (auth) |
| GET | `/bukti/riwayat/{statusHistory}` | `bukti.riwayat` | Download bukti riwayat (auth) |
| GET | `/mahasiswa/dashboard` | `mahasiswa.dashboard` | Dashboard mahasiswa |
| GET | `/mahasiswa/pengaduan` | `mahasiswa.pengaduan.index` | Riwayat pengaduan |
| GET | `/mahasiswa/pengaduan/buat` | `mahasiswa.pengaduan.create` | Form buat pengaduan |
| POST | `/mahasiswa/pengaduan` | `mahasiswa.pengaduan.store` | Submit pengaduan baru |
| GET | `/mahasiswa/pengaduan/{id}` | `mahasiswa.pengaduan.show` | Detail pengaduan |
| PUT | `/mahasiswa/pengaduan/{id}` | `mahasiswa.pengaduan.update` | Update pengaduan |
| PATCH | `/mahasiswa/pengaduan/{id}/konfirmasi-selesai` | `mahasiswa.pengaduan.konfirmasi-selesai` | Konfirmasi selesai |
| PATCH | `/mahasiswa/pengaduan/{id}/tolak-konfirmasi` | `mahasiswa.pengaduan.tolak-konfirmasi` | Tolak konfirmasi |
| PATCH | `/mahasiswa/pengaduan/{id}/balas-informasi` | `mahasiswa.pengaduan.balas-informasi` | Balas informasi tambahan |
| GET | `/admin/dashboard` | `admin.dashboard` | Dashboard admin |
| GET | `/admin/statistik` | `admin.statistik` | Statistik admin |
| GET | `/admin/statistik/export-pdf` | `admin.statistik.export-pdf` | Ekspor PDF admin |
| GET | `/admin/pengaduan/export` | `admin.pengaduan.export` | Ekspor CSV admin |
| GET | `/admin/pengaduan` | `admin.pengaduan.index` | Daftar semua pengaduan |
| GET | `/admin/pengaduan/{id}` | `admin.pengaduan.show` | Detail pengaduan admin |
| PATCH | `/admin/pengaduan/{id}/status` | `admin.pengaduan.update-status` | Update status oleh admin |
| GET | `/admin/users` | `admin.users.index` | Daftar mahasiswa |
| GET | `/admin/users/{id}` | `admin.users.show` | Detail mahasiswa |
| PATCH | `/admin/users/{id}/toggle-active` | `admin.users.toggle-active` | Blokir/aktifkan akun |
| GET | `/kaprodi/dashboard` | `kaprodi.dashboard` | Dashboard kaprodi |
| GET | `/kaprodi/statistik` | `kaprodi.statistik` | Statistik kaprodi |
| GET | `/kaprodi/statistik/export-pdf` | `kaprodi.statistik.export-pdf` | Ekspor PDF kaprodi |
| GET | `/kaprodi/pengaduan/{id}` | `kaprodi.pengaduan.show` | Detail pengaduan (read-only) |
| GET | `/kaprodi/pengaduan/export` | `kaprodi.pengaduan.export` | Ekspor CSV kaprodi |

---

*Dokumen ini diperbarui berdasarkan pemeriksaan langsung terhadap kode sumber aktual (routes/web.php, Models, Controllers, Services, Migrations, Console Commands) — bukan dari dokumen rancangan awal yang mungkin sudah tidak sinkron.*
