# Low Level Design (LLD)
## Sistem Verifikasi & Pelaporan Temuan Auditee (SIVERA)

**Disiapkan oleh:** Mhd. Fahri Irfandi Dewantara  
**Proyek:** SIVERA Plant Cianjur  
**Versi:** 1.0  
**Tanggal:** Agustus 2026

---

## 1. Pendahuluan

Dokumen Low Level Design (LLD) ini merincikan spesifikasi tingkat komponen teknis dari sistem SIVERA. Dokumen ini menjadi acuan utama bagi pengembang dalam memahami rancangan basis data (*database schema*), arsitektur *routing*, hierarki kelas (*class hierarchy*), dan alur komponen antarmuka pengguna berbasis Laravel dan Livewire.

---

## 2. Arsitektur Sistem

SIVERA dibangun menggunakan pendekatan **Model-View-Controller (MVC)** yang diperluas dengan **Livewire 4** untuk reaktivitas *frontend*.
- **Backend:** Laravel 13 (PHP 8.4)
- **Frontend State Management:** Livewire 4 + Alpine.js
- **UI Components:** Livewire Flux 2 + Tailwind CSS v4
- **Database:** MySQL 8

---

## 3. Desain Basis Data (Database Schema)

Sistem menggunakan database relasional dengan tabel-tabel utama sebagai berikut. Setiap model Eloquent memanfaatkan relasi yang ketat (*strict mode*).

### 3.1 Tabel `users` (Otorisasi Akses)
Menyimpan kredensial otentikasi (SIVERA & BOS'Q).
- `id` (BIGINT, PK, Auto-increment)
- `name` (VARCHAR, Nullable)
- `nik` (VARCHAR, Nullable, Foreign Key ke `karyawan.nik`)
- `email` (VARCHAR, Nullable)
- `password` (VARCHAR)
- `role` (ENUM: `karyawan`, `qa`, `superadmin`, default: `karyawan`)
- `no_whatsapp` (VARCHAR, Nullable)
- `it_pin` (VARCHAR, Nullable) — Khusus `superadmin`
- `encrypted_password` (VARCHAR, Nullable, **Encrypted**)

### 3.2 Tabel `karyawan` (Master Data Karyawan)
- `nik` (VARCHAR, PK)
- `nama` (VARCHAR)
- `departemen_id` (BIGINT, FK ke `departemen.id`)
- `status_aktif` (BOOLEAN, default: `true`)
- `is_anggota_divisi_manajemen` (BOOLEAN, default: `false`)

### 3.3 Tabel `temuan` (Inti Transaksi)
- `id` (BIGINT, PK, Auto-increment)
- `tanggal_temuan` (DATE)
- `pelapor_id` (BIGINT, FK ke `users.id`)
- `pic_id` (BIGINT, FK ke `users.id`)
- `departemen_id` (BIGINT, FK ke `departemen.id`)
- `sub_area` (VARCHAR)
- `detail_sub_area` (VARCHAR, Nullable)
- `klausul_id` (BIGINT, FK ke `klausul_prp.id`)
- `deskripsi` (TEXT, **Encrypted**)
- `saran` (TEXT, Nullable, **Encrypted**)
- `foto_temuan_path` (VARCHAR, Nullable)
- `status` (ENUM: `open`, `in_progress`, `closed_pending_qa`, `closed_acc`, default: `open`)
- *Index:* `idx_status`, `idx_tanggal`

### 3.4 Tabel `tindak_lanjut`
Berelasi 1-to-1 dengan tabel `temuan`.
- `id` (BIGINT, PK, Auto-increment)
- `temuan_id` (BIGINT, FK ke `temuan.id`)
- `action` (TEXT, Nullable)
- `due_date` (DATE, Nullable)
- `foto_bukti_path` (VARCHAR, Nullable) — Menyimpan teks path file foto atau JSON string dari array foto bukti perbaikan
- `status` (VARCHAR, default: `open`)
- `acc_qa` (BOOLEAN, default: `false`)
- `tanggal_acc` (DATE, Nullable)
- `catatan_qa` (TEXT, Nullable, **Encrypted**)

---

## 4. Desain Kelas dan Komponen (Class Hierarchy)

### 4.1 Models (Eloquent)
Semua Model memiliki proteksi *mass assignment* (`$guarded = ['id']`) dan *Casts* otomatis.
- **`App\Models\Temuan`**: 
  - Relasi: `belongsTo(User::class, 'pelapor_id')`, `belongsTo(User::class, 'pic_id')`, `hasOne(TindakLanjut::class)`.
  - Casts: `['deskripsi' => 'encrypted', 'saran' => 'encrypted']`.
- **`App\Models\TindakLanjut`**:
  - Relasi: `belongsTo(Temuan::class)`.
  - Casts: `['catatan_qa' => 'encrypted', 'foto_bukti_path' => 'json']`.
- **`App\Models\User`**:
  - Relasi: `belongsTo(Karyawan::class, 'nik', 'nik')`.

### 4.2 Livewire Components (Frontend Logic)
Komponen *Single File Components* (SFC) atau Class-based di `App\Livewire\`:
- `FormTemuan`: Mengatur validasi *input* (deskripsi, sub area), upload foto (dengan resizer GD), dan *Event* dispatch.
- `DaftarTemuanPIC`: *Datatables* untuk PIC. Menangani fitur pencarian dan paginasi.
- `DaftarTemuanQA`: Menangani rendering tabel untuk sisi QA dengan filter departemen dan status.
- `DetailTemuan`: *State management* untuk memanipulasi *action*, *due_date*, serta mengunggah array `foto_bukti_path` (maks 3 file).
- `GrafikTemuan`: Memuat *Query aggregasi* data (Count By Status, Count By Klausul) untuk di-*pass* ke Chart.js.

### 4.3 Services (Background Logic)
- `App\Services\EmailNotificationService`: 
  - `sendSiveraNotification($temuan, $type)`
  - Menerapkan mekanisme `Cache::put()` untuk *Rate-Limiting* (Cooldown 2 Jam) terhadap alamat email spesifik untuk mencegah *spam*.

---

## 5. Kontrak Rute dan Keamanan (*Routing & Middleware*)

Seluruh rute SIVERA dilindungi oleh Middleware otentikasi.

| Rute (URI) | Method | Middleware | Controller/Livewire | Fungsi Utama |
|---|---|---|---|---|
| `/login` | `GET`, `POST` | `guest` | Fortify Auth | Login reguler untuk nama/nik. |
| `/admin-SiveraBosQ` | `GET`, `POST` | `guest` | `ItPortalAuthController` | Login 3-lapis khusus `superadmin`. |
| `/beranda` | `GET` | `auth, system_guard:sivera, role:karyawan,qa` | `pages.beranda` | Form Temuan + Daftar Temuan PIC. |
| `/qa/dashboard` | `GET` | `auth, system_guard:sivera, role:qa` | `GrafikTemuan` | Visualisasi statistik Chart.js. |
| `/temuan/{id}` | `GET` | `auth, can:view,temuan` | `DetailTemuan` | Lembar Rincian (NIK diproteksi jadi Nama Dept). |

> **Catatan Keamanan:** Pengguna dengan sesi *Guard* BOS'Q tidak akan bisa mengakses *URI* `/beranda` di SIVERA.

---

## 6. Diagram Alur Transisi Status (State Machine)

Status dari sebuah temuan direpresentasikan oleh kolom `status` pada tabel `temuan` yang berubah secara berurutan sesuai tindakan (*action*) dari aktor.

1. **`open` (Merah):** Dihasilkan (*generate*) otomatis saat Pelapor membuat temuan baru. Email notifikasi dikirim ke PIC dan QA.
2. **`in_progress` (Oranye):** Bergerak secara otomatis ketika PIC berhasil menyimpan isian (*form*) rencana perbaikan (`action`) dan target waktu (`due_date`) pada tabel `tindak_lanjut`.
3. **`closed_pending_qa` (Biru):** Bergerak saat PIC selesai mengunggah minimal 1 foto bukti perbaikan. Email notifikasi (Review) dikirim ke QA.
   - *Pengecualian:* Jika PIC yang ditunjuk masuk ke dalam flag `is_anggota_divisi_manajemen`, status akan *Bypass* langsung menjadi `closed_acc` (*Auto-ACC*).
4. **`closed_acc` (Hijau):** QA menekan tombol **"Setujui (ACC)"** di panel `VerifikasiQA`. Transaksi ditutup secara logis (*read-only*).

---

## 7. Pekerjaan Latar Belakang (*Background Jobs & Scheduler*)

### 7.1 Queue Worker (Email Transaksional)
Pengiriman notifikasi email diproses secara asinkron (*background process*) memanfaatkan antrean database.
- **Konfigurasi:** `QUEUE_CONNECTION=database`
- **Tabel:** `jobs` dan `failed_jobs`
- **Mailables:** `App\Mail\TemuanNotificationMail`

### 7.2 Scheduler Pembersihan Otomatis (*Pruning*)
- **Perintah Artisan:** `php artisan prp:prune-temuan`
- **Tugas:** Menjalankan kueri *Soft Delete* atau *Hard Delete* pada baris tabel `temuan` yang parameter `tanggal_temuan`-nya lebih dari batas `TEMUAN_RETENTION_YEARS` yang di-*set* di `.env`. Ini juga memicu skrip `Storage::delete()` untuk menghapus aset foto lama secara sinkronus agar tidak membebani kapasitas disk server.
