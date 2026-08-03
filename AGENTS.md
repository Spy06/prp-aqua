# AGENTS.md — SIVERA (Sistem Verifikasi & Pelaporan Temuan Auditee)

> Dokumentasi teknis internal dan Spesifikasi Kebutuhan Perangkat Lunak (Software Requirements Specification - SRS) untuk AI Agent agar dapat melakukan debugging, feature implementation, maintenance, dan audit sistem dengan efisien di proyek SIVERA.

---

## 1. Deskripsi Proyek

**SIVERA (Sistem Verifikasi & Pelaporan Temuan Auditee)** adalah aplikasi berbasis web untuk digitalisasi, pengawasan, dan tindak lanjut **Temuan Audit Internal & Program Prasyarat Kepatuhan (PRP)** di lingkungan manufaktur/industri. Aplikasi ini mengotomatisasi alur kerja pelaporan ketidaksesuaian (*non-conformity*), penunjukan Person in Charge (PIC), pelaksanaan perbaikan, hingga verifikasi penutupan temuan oleh tim Quality Assurance (QA).

### Tujuan Utama
- Digitalisasi pelaporan temuan audit internal & kepatuhan klausul PRP secara *real-time*.
- Alur kerja bertingkat 4-stage (*Open* → *In Progress* → *Closed Pending QA* → *Closed ACC*).
- Autentikasi fleksibel berbasis **Nama Karyawan ATAU NIK (Nomor Induk Karyawan)** dan password.
- Integrasi notifikasi otomatis via **Email API (Resend / Corporate SMTP)** ke alamat email PIC, Pelapor, dan Auditor QA saat ada pembaruan status temuan.
- Otomatisasi penutupan temuan (*Auto-ACC*) ketika PIC mengunggah bukti perbaikan lengkap.
- Dashboard analytics visualisasi kepatuhan interaktif dengan *Berry Stat Cards* & *Chart.js*.
- Cetak dokumen resmi Rekap Temuan Audit dan Lembar Detail Temuan dalam format PDF.
- Keamanan tingkat tinggi berbasis enkripsi data (*application-level encryption*) untuk catatan sensitif dan proteksi portal khusus IT Super Admin.

### Stack Teknologi
| Layer | Teknologi & Tools |
|-------|-------------------|
| Backend Framework | Laravel 13 (PHP 8.3) |
| Frontend Interaktif | Livewire 4 + Volt (Single File Components) |
| Admin & Portal Auth | Custom IT Admin Secret Portal (`/it-admin-portal`) + Fortify Auth |
| Styling & Theme | Tailwind CSS v4 (CSS-first config via `@import`) + Custom Zinc/Indigo Theme |
| Charts & Visualization | Chart.js 4 + `chartjs-plugin-datalabels` + Custom Shadcn Replica SVG Donut |
| Build Tool | Vite 8 |
| Database Default | MySQL 8 / MariaDB (Database aktif: `swp_online`) |
| PDF Generator | `barryvdh/laravel-dompdf` |
| Email Gateway | Resend Email API / Corporate SMTP Integration (`App\Services\EmailNotificationService`) |
| Date Handling | `CarbonImmutable` (strict default via `Date::use()`) |
| Enkripsi | Native Laravel Application Encryption (`encrypted` Eloquent casts) |
| Dev Tools | Larastan (PHPStan Level 5), Pest 4, Laravel Pint |

---

## 2. Fitur yang Sudah Tersedia

### 2.1. Dual Authentication & Isolated Portal Access
1. **Regular User Authentication (Karyawan & QA Auditor)**
   - Login menggunakan **Nama Karyawan ATAU NIK (Nomor Induk Karyawan)** dan password di `/login`.
   - Menggunakan relasi `User::belongsTo(Karyawan::class, 'nik', 'nik')`.
   - Memiliki 2 role utama di SIVERA: `karyawan` (Pelapor / PIC) dan `qa` (Auditor QA).
   - Pengarahan otomatis berdasarkan role pasca-login:
     - `karyawan` → diarahkan ke Beranda Pelapor (`/beranda`).
     - `qa` → diarahkan ke Dashboard Analytics QA (`/qa/dashboard`).
   - Pendaftaran publik dinonaktifkan (`/register` mengembalikan 404). Akun dibuat dan dikelola secara tertutup oleh Super Admin.

2. **IT Super Admin Secret Portal Access (`/it-admin-portal`)**
   - Rute login rahasia khusus Tim IT/Administrator: `/it-admin-portal`.
   - Autentikasi 3 lapis: Nama/NIK + Password + Kode PIN Khusus IT (`2026`).
   - Rute `/login` biasa secara eksplisit **menolak** login untuk akun ber-role `superadmin`.
   - Super Admin terisolasi 100% dari alur operasi audit (tidak ada menu lapor temuan / audit), hanya berfokus pada **Master Data Akun User** (`/qa/master/akun`) dengan akses edit penuh (NIK, Nama, Departemen, Role, WA, dan Reset Password).

### 2.2. Pelaporan Temuan Audit Internal (Lapor Temuan)
- Form pelaporan di Beranda SIVERA dengan masukan:
  - **Tanggal Temuan**: Auto-default tanggal hari ini.
  - **Departemen & Sub Area**: Pilihan lokasi kejadian temuan (beserta `detail_sub_area` spesifik).
  - **Klausul PRP**: Kategori standar mutu/keamanan pangan yang dilanggar.
  - **Penunjukan PIC**: Dropdown dinamis daftar karyawan yang terdaftar di sistem.
  - **Deskripsi Temuan & Saran**: Teks deskripsi detail kejadian dan usulan perbaikan (otomatis di-encrypt di database).
  - **Upload Foto Temuan**: Lampiran gambar bukti temuan (dukungan kompresi otomatis GD Library JPG/PNG).

### 2.3. Alur Verifikasi & Tindak Lanjut 4-Tier (Workflow Engine)
Temuan bergerak dalam 4 status utama:
1. **`open`**: Temuan baru dilaporkan, menunggu respon/aksi dari PIC yang ditunjuk. Warna status: **Merah** (`#c62828`).
2. **`in_progress`**: PIC telah mengisi rencana aksi perbaikan (*action*) dan target tanggal penyelesaian (*due_date*). Warna status: **Kuning/Kuning-Kecoklatan** (`#f57c00`).
3. **`closed_pending_qa`**: PIC telah mengunggah foto bukti perbaikan (*foto_bukti_path*, mendukung hingga 3 foto sekaligus). Warna status: **Biru** (`#1976d2`).
4. **`closed_acc`**: Temuan dinyatakan selesai dan disetujui (*ACC*) oleh QA. Warna status: **Hijau** (`#2e7d32`).

### 2.4. Integrasi Notifikasi WhatsApp (Twilio API)
- Pengiriman pesan WhatsApp otomatis pada setiap perubahan status penting:
  - **Notifikasi Baru ke PIC**: Dikirim saat temuan baru dibuat dan menunjuk karyawan sebagai PIC.
  - **Notifikasi Rencana Aksi ke Pelapor/QA**: Dikirim saat PIC memperbarui target tanggal perbaikan.
  - **Notifikasi Bukti di-Upload ke QA**: Dikirim saat PIC mengunggah bukti perbaikan.
  - **Notifikasi Penutupan (Closed ACC) ke PIC & Pelapor**: Dikirim saat temuan disetujui/ditutup oleh QA.
- Menggunakan service custom `App\Services\WhatsAppService` dengan penanganan *error fallback* yang aman.

### 2.5. Detail Temuan Audit (Proteksi NIK Karyawan)
- Pada lembar detail temuan (`/temuan/{id}`), NIK Pelapor dan NIK PIC **disembunyikan/dihapus**, digantikan dengan tampilan **Nama Departemen** masing-masing user untuk privasi data karyawan.

### 2.6. Master Data Management
- **Master Karyawan & Divisi Manajemen**: Pengelolaan data NIK, Nama, Departemen, Status Aktif, dan penanda Divisi Manajemen.
- **Master Departemen**: Pengelolaan daftar unit kerja/departemen di perusahaan.
- **Master Klausul PRP**: Pengelolaan daftar klausul mutu dan prasyarat operasional.
- **Master Sub Area**: Pengelolaan sub-lokasi fisik per departemen.
- **Master Akun User**: Khusus Super Admin untuk pembuatan akun, assignment NIK, pengubahan role (`karyawan`, `qa`, `superadmin`), pembaruan nomor WhatsApp, dan reset password.

### 2.7. Dashboard Analytics Visual Kepatuhan SIVERA
- **Top 5 Berry Stat Cards (Gradient Cards & Abstract Circles)**:
  1. *Total Temuan* (Gradient Purple)
  2. *Status Open* (Gradient Red `#c62828`)
  3. *Status In Progress* (Gradient Orange `#f57c00`)
  4. *Status Pending QA* (Gradient Blue `#1976d2`)
  5. *Status Closed (ACC)* (Gradient Green `#2e7d32`)
- **Grid Charts Analytic SIVERA (Responsive 2x2 Grid)**:
  - `a. Temuan per Departemen` (Bar Chart ramping dengan kapsul melengkung `#673ab7`).
  - `b. Proporsi Status Temuan` (Shadcn SVG Donut Chart dengan counter tengah & legenda interaktif).
  - `c. Temuan per Klausul PRP` (Bar Chart ramping `#2196f3` untuk melihat klausul mana yang paling banyak dilanggar).
  - `d. Temuan tiap Sub Area` (Bar Chart ramping `#ff9800` dengan dropdown filter departemen).

### 2.8. Rekap Periode & Export Dokumen Resmi
- **Multi-Mode Filter Tanggal**: Filter statistik dan rekap berdasarkan:
  - *Per Bulan*: Pilihan Bulan & Tahun.
  - *Per Tahun*: Pilihan Tahun operasional.
  - *Custom Date*: Rentang Tanggal Mulai s/d Tanggal Selesai.
- **Export Data Rekap**:
  - Export Excel / CSV (`.xlsx` / `.csv`).
  - Export PDF Dokumen Rekap Kepatuhan Resmi (`.pdf`).
  - Export PDF Lembar Detail Temuan Audit per ID Temuan.

---

## 3. Struktur Routing

### 3.1. Web Routes (`routes/web.php`)

| Method | URI | Name | Controller / Livewire View | Middleware | Keterangan |
|--------|-----|------|----------------------------|------------|------------|
| GET | `/` | `portal` | Closure (`view('portal')`) | - | Portal Pemilihan Sistem |
| GET | `/register` | - | Closure (`abort(404)`) | - | Register publik dinonaktifkan |
| GET | `/it-admin-portal` | `it.login.form` | `ItPortalAuthController@showLoginForm` | - | Form login rahasia IT |
| POST | `/it-admin-portal` | `it.login.submit` | `ItPortalAuthController@login` | - | Eksekusi login rahasia IT |
| POST | `/it-admin-portal/logout` | `it.logout` | `ItPortalAuthController@logout` | - | Logout IT Admin |
| GET | `/dashboard` | `dashboard` | Closure (auto-redirect per role) | `auth` | Redirect sesuai role SIVERA |
| GET | `/beranda` | `beranda` | `view('pages.beranda')` | `auth`, `system_guard:sivera`, `role:karyawan,qa` | Beranda Pelapor/PIC |
| GET | `/qa/dashboard` | `qa.dashboard` | `view('dashboard')` | `auth`, `system_guard:sivera`, `role:qa` | Dashboard Grafik SIVERA |
| GET | `/qa/daftar-temuan` | `qa.daftar-temuan` | `view('pages.qa.daftar-temuan')` | `auth`, `system_guard:sivera`, `role:qa` | Tabel Daftar Temuan QA |
| GET | `/qa/rekap` | `qa.rekap` | `view('pages.qa.rekap')` | `auth`, `system_guard:sivera`, `role:qa` | Rekap Periode Audit |
| GET | `/qa/master/karyawan` | `qa.master.karyawan` | `view('pages.qa.master-karyawan')` | `auth`, `system_guard:sivera`, `role:qa` | Master Data Karyawan |
| GET | `/qa/master/departemen` | `qa.master.departemen` | `view('pages.qa.master-departemen')` | `auth`, `system_guard:sivera`, `role:qa` | Master Data Departemen |
| GET | `/qa/master/klausul` | `qa.master.klausul` | `view('pages.qa.master-klausul')` | `auth`, `system_guard:sivera`, `role:qa` | Master Data Klausul PRP |
| GET | `/qa/master/akun` | `qa.master.akun` | `view('pages.qa.master-akun')` | `auth`, `system_guard:sivera`, `role:superadmin` | Master Akun User (IT Only) |
| GET | `/export/excel` | `export.excel` | `ExportController@excel` | `auth`, `system_guard:sivera`, `role:qa` | Export Rekap Excel |
| GET | `/export/pdf/temuan/{temuan}` | `export.pdf.temuan` | `ExportController@pdfTemuan` | `auth`, `system_guard:sivera`, `role:qa` | Export PDF Single Temuan |
| GET | `/export/pdf/rekap` | `export.pdf.rekap` | `ExportController@pdfRekap` | `auth`, `system_guard:sivera`, `role:qa` | Export PDF Rekap Periode |
| GET | `/temuan/{temuan}` | `temuan.detail` | Closure (`view('pages.temuan-detail')`) | `auth`, `system_guard:sivera`, `can:view,temuan` | Detail Temuan & Form Aksi |

### 3.2. Settings Routes (`routes/settings.php`)
- Profile edit, appearance settings, security/password update, passkey WebAuthn endpoints.

### 3.3. Middleware Terdaftar

| Middleware Alias | Class | Fungsi / Aturan Kebijakan |
|------------------|-------|---------------------------|
| `system_guard:sivera` | `App\Http\Middleware\SystemGuard` | Memastikan sesi aktif berada di konteks sistem SIVERA |
| `role:karyawan,qa,superadmin` | `App\Http\Middleware\CheckRole` | Menolak akses jika role user tidak sesuai dengan daftar parameter |
| `CheckAccountExpiration` | `App\Http\Middleware\CheckAccountExpiration` | Global middleware web — otomatis logout akun jika `expires_at` terlewati |

---

## 4. Skema Database SIVERA

### 4.1. Tabel Inti

#### `users`
| Column | Type | Constraints / Keterangan |
|--------|------|--------------------------|
| `id` | bigint PK | Auto-increment |
| `nik` | string nullable FK | Unique, relasi ke `karyawan.nik` |
| `name` | string nullable | Nama tampilan user / login username |
| `email` | string nullable | Alamat email (opsional) |
| `password` | string | Hashed password |
| `role` | string default 'karyawan' | Nilai yang diizinkan: `'karyawan'`, `'qa'`, `'superadmin'` |
| `no_whatsapp` | string nullable | Nomor WA untuk notifikasi Twilio (format: `628xxx`) |
| `expires_at` | timestamp nullable | Masa berlaku akun user |
| `remember_token` | string nullable | Token sesi |

#### `karyawan`
| Column | Type | Constraints / Keterangan |
|--------|------|--------------------------|
| `nik` | string PK | Primary key string NIK karyawan |
| `nama` | string | Nama lengkap karyawan |
| `departemen_id` | bigint FK | Relasi ke `departemen.id` (onDelete: restrict) |
| `status_aktif` | boolean default true | Status aktif karyawan |
| `is_anggota_divisi_manajemen` | boolean default false | Status keanggotaan divisi manajemen |

#### `departemen`
| Column | Type | Constraints / Keterangan |
|--------|------|--------------------------|
| `id` | bigint PK | Auto-increment |
| `nama_departemen` | string unique | Nama departemen (e.g. Produksi, QA, Logistik, HR) |

#### `klausul_prp`
| Column | Type | Constraints / Keterangan |
|--------|------|--------------------------|
| `id` | bigint PK | Auto-increment |
| `kode_klausul` | string | Kode klausul standar (e.g. `PRP-01`, `PRP-02`) |
| `nama_klausul` | string | Nama/Deskripsi klausul mutu & keselamatan |

#### `sub_areas`
| Column | Type | Constraints / Keterangan |
|--------|------|--------------------------|
| `id` | bigint PK | Auto-increment |
| `departemen_id` | bigint FK | Relasi ke `departemen.id` (onDelete: cascade) |
| `nama_sub_area` | string | Nama sub area fisik lokasi kerja |

#### `temuan`
| Column | Type | Constraints / Keterangan |
|--------|------|--------------------------|
| `id` | bigint PK | Auto-increment ID Temuan |
| `tanggal_temuan` | date | Tanggal dilaporkannya temuan |
| `pelapor_id` | bigint FK | Relasi ke `users.id` (onDelete: restrict) |
| `pic_id` | bigint FK | Relasi ke `users.id` (onDelete: restrict) |
| `departemen_id` | bigint FK | Relasi ke `departemen.id` (onDelete: restrict) |
| `sub_area` | string | Nama sub area |
| `detail_sub_area` | string nullable | Detail spesifik lokasi sub area |
| `klausul_id` | bigint FK nullable | Relasi ke `klausul_prp.id` (onDelete: restrict) |
| `foto_temuan_path` | string nullable | Path simpan foto temuan (`storage/app/public/temuan/*`) |
| `deskripsi` | text | Deskripsi temuan (*encrypted Eloquent cast*) |
| `saran` | text nullable | Saran perbaikan (*encrypted Eloquent cast*) |
| `status` | string default 'open' | Enum: `'open'`, `'in_progress'`, `'closed_pending_qa'`, `'closed_acc'` |

#### `tindak_lanjut`
| Column | Type | Constraints / Keterangan |
|--------|------|--------------------------|
| `id` | bigint PK | Auto-increment |
| `temuan_id` | bigint FK | Unique / 1:1 relasi ke `temuan.id` (onDelete: cascade) |
| `action` | text nullable | Rencana perbaikan oleh PIC |
| `due_date` | date nullable | Target tanggal penyelesaian perbaikan |
| `foto_bukti_path` | string nullable | JSON Array / String path foto bukti perbaikan |
| `status` | string default 'open' | Status penanganan tindak lanjut |
| `acc_qa` | boolean default false | Status persetujuan QA Auditor |
| `tanggal_acc` | date nullable | Tanggal persetujuan ACC oleh QA |
| `catatan_qa` | text nullable | Catatan atau rekomendasi QA (*encrypted Eloquent cast*) |

#### `passkeys` (WebAuthn)
| Column | Type | Constraints / Keterangan |
|--------|------|--------------------------|
| `id` | bigint PK | |
| `user_id` | bigint FK | Relasi ke `users.id` (onDelete: cascade) |
| `name` | string | Nama perangkat passkey |
| `credential_id` | string unique | Credential ID WebAuthn |
| `credential` | json | Data kredensial WebAuthn |

---

## 5. Models & Relationships

```
User (Pelapor / PIC / QA / SuperAdmin)
  ├─ belongsTo Karyawan (via NIK)
  ├─ hasMany Temuan (as pelapor_id via temuanDilaporkan)
  └─ hasMany Temuan (as pic_id via temuanSebagaiPic)

Karyawan
  ├─ belongsTo Departemen
  └─ hasOne User (via NIK)

Departemen
  ├─ hasMany Karyawan
  ├─ hasMany SubArea
  └─ hasMany Temuan

SubArea
  └─ belongsTo Departemen

KlausulPrp
  └─ hasMany Temuan

Temuan
  ├─ belongsTo User (as pelapor)
  ├─ belongsTo User (as pic)
  ├─ belongsTo Departemen
  ├─ belongsTo KlausulPrp
  └─ hasOne TindakLanjut

TindakLanjut
  └─ belongsTo Temuan
```

---

## 6. Struktur Direktori Penting

```
swp-online/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── ExportController.php        # Export Excel & PDF SIVERA
│   │   │   └── ItPortalAuthController.php  # Portal Rahasia IT Super Admin (/it-admin-portal)
│   │   └── Middleware/
│   │       ├── CheckAccountExpiration.php # Auto logout jika akun expired
│   │       ├── CheckRole.php              # Guard hak akses role
│   │       └── SystemGuard.php            # Guard konteks sistem SIVERA vs BOS'Q
│   ├── Livewire/
│   │   ├── DaftarTemuanPic.php            # Tabel daftar temuan & aksi PIC
│   │   ├── DetailTemuanModal.php          # Modal interaktif detail temuan
│   │   ├── FormTemuan.php                 # Form lapor temuan baru
│   │   ├── GrafikTemuan.php               # Backend data analytics grafik SIVERA
│   │   ├── MasterAkunUser.php             # CRUD User & Reset Password (Super Admin)
│   │   ├── MasterDepartemen.php           # CRUD Master Departemen
│   │   ├── MasterKaryawan.php             # CRUD Master Karyawan
│   │   ├── MasterKlausulPrp.php           # CRUD Master Klausul PRP
│   │   └── RekapPeriode.php               # Rekap kepatuhan periode audit
│   ├── Models/
│   │   ├── User.php                       # Model User Auth (NIK primary key link)
│   │   ├── Karyawan.php                   # Model Karyawan (PK: NIK)
│   │   ├── Departemen.php                 # Model Departemen
│   │   ├── KlausulPrp.php                 # Model Klausul PRP
│   │   ├── SubArea.php                    # Model Sub Area
│   │   ├── Temuan.php                     # Model Temuan (encrypted casts)
│   │   └── TindakLanjut.php               # Model Tindak Lanjut (encrypted casts)
│   └── Services/
│       └── WhatsAppService.php            # Service notifikasi Twilio WhatsApp
├── database/
│   └── migrations/                        # Migrasi tabel SIVERA
├── resources/
│   ├── css/app.css                        # Tailwind CSS v4 config
│   └── views/
│       ├── components/
│       │   └── layouts/app.blade.php      # Main Layout SIVERA
│       ├── livewire/
│       │   ├── daftar-temuan-pic.blade.php
│       │   ├── form-temuan.blade.php
│       │   ├── grafik-temuan.blade.php    # View Dashboard Analytics SIVERA
│       │   ├── master-akun-user.blade.php
│       │   ├── master-departemen.blade.php
│       │   ├── master-karyawan.blade.php
│       │   └── master-klausul-prp.blade.php
│       ├── pages/
│       │   ├── beranda.blade.php
│       │   ├── temuan-detail.blade.php
│       │   └── qa/                        # Halaman-halaman khusus QA
│       ├── pdf/
│       │   ├── temuan.blade.php           # PDF template detail temuan
│       │   └── rekap.blade.php            # PDF template rekap periode
│       ├── portal.blade.php               # Portal awal pemilihan sistem
│       └── it-portal-login.blade.php      # Form login portal rahasia IT
└── routes/
    └── web.php                            # Definisi rute web SIVERA
```

---

## 7. Komponen Kritis & Catatan Teknis

### 7.1. Autentikasi Fleksibel Nama / NIK
File: `app/Providers/FortifyServiceProvider.php` & `app/Http/Controllers/ItPortalAuthController.php`
- Mendukung autentikasi menggunakan **Nama Karyawan ATAU NIK (Nomor Induk Karyawan)**.
- Query pencarian user: `User::where('nik', $loginInput)->orWhere('name', $loginInput)->first()`.

### 7.2. Transisi Status Temuan (4-Tier Workflow Engine)
File: `app/Livewire/DaftarTemuanPic.php` & `app/Livewire/DetailTemuanModal.php`
- **`open`**: Baru dilaporkan. Warna badge: **Merah** (`#c62828`).
- **`in_progress`**: PIC memasukkan `action` & `due_date`. Warna badge: **Kuning/Orange** (`#f57c00`).
- **`closed_pending_qa`**: PIC mengunggah `foto_bukti_path`. Warna badge: **Biru** (`#1976d2`).
- **`closed_acc`**:
  - Diberikan otomatis saat bukti lengkap diunggah (*Auto-ACC*), ATAU
  - Disetujui secara manual oleh QA via tombol ACC di modal detail temuan. Warna badge: **Hijau** (`#2e7d32`).

### 7.3. Enkripsi Data Sensitif Aplikasi (Eloquent Encrypted Casts)
Sesuai standar proteksi data audit, kolom teks berikut otomatis di-encrypt menggunakan kunci aplikasi Laravel (`APP_KEY`):
- `Temuan::$casts['deskripsi'] = 'encrypted'`
- `Temuan::$casts['saran'] = 'encrypted'`
- `TindakLanjut::$casts['catatan_qa'] = 'encrypted'`

### 7.4. Proteksi NIK di Detail Temuan
File: `resources/views/pages/temuan-detail.blade.php`
- NIK Pelapor dan NIK PIC **disembunyikan/dihapus** dari lembar detail temuan, digantikan dengan tampilan **Nama Departemen** dari pengguna yang bersangkutan untuk melindungi privasi karyawan.

### 7.5. Logika WhatsApp Gateway (Twilio Integration)
File: `app/Services/WhatsAppService.php`
- Mengirim pesan terformat dengan pola nomor `whatsapp:+628xxxx`.
- Menangani pembersihan format nomor seluler Indonesia (`08xxx` → `628xxx`).
- Dijalankan secara *graceful*: Jika pengiriman gagal (misal koneksi terputus/kredensial invalid), aplikasi tetap menyimpan data dan mencatat log error tanpa menghentikan eksekusi user.

### 7.6. Isolasi Portal Rahasia IT Super Admin (`/it-admin-portal`)
File: `app/Http/Controllers/ItPortalAuthController.php` & `app/Http/Responses/LoginResponse.php`
- Rute `/login` biasa menolak pengguna dengan role `superadmin`.
- Super Admin hanya dapat login via `/it-admin-portal` dengan memasukkan Nama/NIK + Password + Kode PIN rahasia `2026`.
- Begitu berhasil login, Super Admin langsung diarahkan ke halaman Manajemen Akun User (`/qa/master/akun`) dan tidak memiliki akses ke fitur operasional audit.

### 7.7. Date Handling Strict Compliance
`AppServiceProvider` menerapkan aturan `Date::use(CarbonImmutable::class)`. Seluruh penanganan tanggal Wajib menggunakan `CarbonImmutable` atau helper `now()`. Dilarang menggunakan kelas `Carbon\Carbon` mutabel biasa.

---

## 8. Tracking Progress

### 8.1. Status Implementasi Fitur SIVERA
| Fitur | Status | Keterangan |
|-------|--------|------------|
| Dual Auth (Nama/NIK Login & IT Secret Portal) | ✅ Done | Login via Nama/NIK aktif, Portal rahasia `/it-admin-portal` aktif |
| Lapor Temuan (Upload Foto & Enkripsi Deskripsi) | ✅ Done | Termasuk penunjukan PIC & Klausul PRP |
| Workflow Status 4-Tier (Open -> ACC) | ✅ Done | Open = Merah, Closed = Hijau |
| Proteksi Privasi NIK di Detail Temuan | ✅ Done | NIK digantikan Nama Departemen |
| Auto Kompresi Foto (GD Library JPG/PNG) | ✅ Done | Mengompres ukuran foto temuan & bukti |
| Notifikasi WhatsApp Automatis (Twilio) | ✅ Done | Notifikasi real-time saat update status |
| Master Data (Karyawan, Dept, Klausul, SubArea) | ✅ Done | CRUD Lengkap |
| Super Admin Master Akun Management | ✅ Done | Full access edit NIK, Role, WA & Reset Pass |
| Dashboard Analytics SIVERA | ✅ Done | 5 Berry Stat Cards + 5 Chart.js Analytics |
| Multi-Mode Date Filter (Bulan, Tahun, Custom) | ✅ Done | Reaktif pada grafik & tabel |
| Export Rekap Excel (`.xlsx`) | ✅ Done | Download rekap spreadsheet |
| Export Rekap & Detail Temuan PDF (`.pdf`) | ✅ Done | DomPDF template terformat resmi |

### 8.2. Known Limitations / TODO
- [ ] **TODO**: Implementasi Queue Worker (`queue:work`) untuk pengiriman pesan WhatsApp secara asinkron (*background job*).
- [ ] **TODO**: Penambahan fitur export history temuan audit per departemen ke format ZIP foto bukti.

---

## 9. Konvensi & Protokol AI Agent

### 9.1. Prinsip Debugging
1. **Evidence First**: Baca migrasi, model Eloquent, dan komponen Livewire terkait sebelum mengubah kode.
2. **Periksa Enkripsi**: Jangan melakukan raw SQL query LIKE pada kolom `deskripsi`, `saran`, atau `catatan_qa` karena kolom tersebut di-encrypt di tingkat aplikasi. Gunakan pencarian via relasi atau NIK/Nama.
3. **Verifikasi Jalur Testing**:
   - `composer run lint:check` (Laravel Pint)
   - `composer run types:check` (PHPStan/Larastan)
   - `php artisan test` (Pest Tests)

### 9.2. Komando Penting
| Perintah | Fungsi |
|----------|--------|
| `composer run dev` | Menjalankan server lokal + queue + Vite |
| `composer run lint` | Memformat kode sesuai standar Pint (PSR-12) |
| `composer run lint:check` | Memeriksa format kode tanpa mengubah file |
| `composer run types:check` | Memeriksa tipe data dengan Larastan |
| `php artisan view:clear` | Membersihkan cache compiled Blade views |
| `php artisan route:clear` | Membersihkan cache rute aplikasi |

---

## 10. Quick Reference: File-File Kritis SIVERA

| Untuk Mengedit / Memahami... | Lihat File... |
|------------------------------|---------------|
| Form Lapor Temuan SIVERA | `resources/views/livewire/form-temuan.blade.php` + `app/Livewire/FormTemuan.php` |
| Halaman Dashboard Analytics SIVERA | `resources/views/livewire/grafik-temuan.blade.php` + `app/Livewire/GrafikTemuan.php` |
| Tabel Daftar Temuan & Action PIC | `resources/views/livewire/daftar-temuan-pic.blade.php` + `app/Livewire/DaftarTemuanPic.php` |
| Modal Detail Temuan & Approval QA | `resources/views/livewire/detail-temuan-modal.blade.php` + `app/Livewire/DetailTemuanModal.php` |
| Halaman Detail Temuan (Proteksi NIK) | `resources/views/pages/temuan-detail.blade.php` |
| Portal Rahasia Login IT Admin | `app/Http/Controllers/ItPortalAuthController.php` + `resources/views/it-portal-login.blade.php` |
| Management Akun User (Super Admin) | `app/Livewire/MasterAkunUser.php` + `resources/views/livewire/master-akun-user.blade.php` |
| Service Notifikasi WhatsApp | `app/Services/WhatsAppService.php` |
| Controller Export Excel & PDF | `app/Http/Controllers/ExportController.php` |
| Template PDF Rekap & Detail | `resources/views/pdf/rekap.blade.php` & `resources/views/pdf/temuan.blade.php` |
