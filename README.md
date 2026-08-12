# SIVERA & BOSQ — Sistem Verifikasi & Pelaporan Temuan Auditee

> **Sistem Informasi Audit Internal & Observasi Perilaku Berbasis Web — PT Tirta Investama (Plant Cianjur)**

SIVERA (*Sistem Verifikasi & Pelaporan Temuan Auditee*) dan BOS'Q (*Behavior Observation System Quality*) adalah dua sistem terintegrasi dalam satu platform web yang mengotomatisasi digitalisasi, pengawasan, dan tindak lanjut temuan audit internal serta observasi kepatuhan perilaku karyawan. Sistem ini dirancang untuk menggantikan proses manual berbasis kertas dan mempercepat siklus penutupan temuan ketidaksesuaian. Target pengguna adalah tim Quality Assurance (QA), Person in Charge (PIC) departemen, pelapor temuan (karyawan), serta IT Administrator di lingkungan pabrik PT Tirta Investama.

---

## Fitur Utama

### SIVERA — Verifikasi PRP Plant

- **Pelaporan Temuan Real-time** — Form laporan temuan dengan upload foto, enkripsi deskripsi, penunjukan PIC, dan pemilihan Klausul PRP
- **Alur Kerja 4-Tier** — Transisi status otomatis: `Open` → `In Progress` → `Closed Pending QA` → `Closed ACC`
- **Verifikasi QA** — QA dapat menyetujui (ACC), menolak, atau memberikan catatan terenkripsi pada setiap temuan
- **Auto Kompresi Foto** — Foto temuan & bukti perbaikan dikompresi otomatis menggunakan GD Library
- **Dashboard Analytics** — 5 Berry Stat Cards + 4 Chart.js (Bar & Donut) untuk visualisasi kepatuhan
- **Rekap Multi-Periode** — Filter berdasarkan Bulan, Tahun, atau rentang Custom Date
- **Export Dokumen Resmi** — Export Rekap ke Excel/CSV & PDF, Export Detail Temuan ke PDF
- **Proteksi NIK** — NIK Pelapor & PIC disembunyikan di halaman detail, digantikan Nama Departemen
- **Enkripsi Data Sensitif** — Kolom `deskripsi`, `saran`, dan `catatan_qa` dienkripsi via Eloquent Encrypted Casts
- **Retensi Data Otomatis** — Artisan command `prp:prune-temuan` untuk menghapus data & foto yang sudah melewati batas retensi

### BOSQ — Behavior Observation System Quality

- **Form Laporan Observasi** — Pelapor/PIC dapat melaporkan observasi perilaku karyawan di area kerja
- **Verifikasi QA BOS'Q** — QA dapat memverifikasi dan menutup laporan observasi
- **Dashboard Analitik BOS'Q** — Visualisasi tren observasi berdasarkan Line & Sub Area
- **Rekap Kepatuhan** — Laporan rekap kepatuhan perilaku per periode
- **Export Data** — Export ke CSV & PDF untuk dashboard maupun rekap periodik
- **Master Data BOS'Q** — Kelola Line, Sub Area, Elemen QFS, dan Master Karyawan BOS'Q

### Sistem Umum (Kedua Platform)

- **Autentikasi Ganda** — Login menggunakan Nama Karyawan ATAU NIK + Password
- **IT Super Admin Secret Portal** — Portal login rahasia 3-lapis di `/admin-SiveraBosQ` (Nama/NIK + Password + PIN)
- **Isolasi Sistem (System Guard)** — Middleware memastikan sesi tidak bercampur antara SIVERA dan BOS'Q
- **Manajemen Akun User** — Super Admin dapat membuat, mengedit role, reset password, dan mengatur masa aktif akun
- **Notifikasi Email Otomatis** — Notifikasi ke PIC, Pelapor, dan QA saat ada perubahan status temuan
- **Rate-Limiting Email** — Sistem cooldown 2 jam mencegah spam notifikasi ke email yang sama
- **Passkey / WebAuthn** — Dukungan login tanpa password menggunakan passkey untuk perangkat yang mendukung
- **Manajemen Sesi** — Auto logout jika akun melewati masa berlaku (`expires_at`)

---

## Tech Stack

| Kategori | Teknologi |
|---|---|
| **Backend Framework** | Laravel 13 (PHP 8.3+) |
| **Frontend Interaktif** | Livewire 4 + Volt (Single File Components) |
| **UI Component Library** | Livewire Flux 2 |
| **Autentikasi** | Laravel Fortify |
| **Styling** | Tailwind CSS v4 (CSS-first via `@import`) |
| **Build Tool** | Vite 8 + `@tailwindcss/vite` plugin |
| **Charts** | Chart.js 4 + chartjs-plugin-datalabels |
| **Database** | MySQL 8 / MariaDB |
| **PDF Generator** | barryvdh/laravel-dompdf ^3.1 |
| **CSV Export** | league/csv ^9.28 |
| **Email Gateway** | SMTP (Gmail) / Resend Laravel SDK |
| **Enkripsi** | Laravel Application Encryption (Eloquent encrypted cast) |
| **Date Handling** | CarbonImmutable (strict via Date::use()) |
| **Passkey / WebAuthn** | @laravel/passkeys |
| **Dev: Static Analysis** | Larastan (PHPStan) Level 5 |
| **Dev: Testing** | Pest 4 + pest-plugin-laravel |
| **Dev: Linting** | Laravel Pint |

---

## Prasyarat (Prerequisites)

Pastikan semua software berikut sudah terinstall di server atau komputer pengembangan Anda:

| Software | Versi Minimum | Keterangan |
|---|---|---|
| **PHP** | 8.3+ | Dengan ekstensi: `pdo_mysql`, `gd`, `mbstring`, `openssl`, `bcmath` |
| **Composer** | 2.x | Package manager PHP |
| **Node.js** | 18+ (LTS) | Untuk build aset frontend |
| **npm** | 9+ | Bawaan Node.js |
| **MySQL** | 8.0+ / MariaDB 10.6+ | Database utama |
| **Gmail SMTP / Resend** | — | Untuk notifikasi Email |
| **Supervisor** | — | Wajib di server produksi untuk menjalankan Queue Worker |

---

## Konfigurasi Environment

Salin file `.env.example` menjadi `.env`, lalu isi variabel berikut:

```bash
cp .env.example .env
php artisan key:generate
```

### Daftar Environment Variable

| Variable | Deskripsi | Contoh Nilai |
|---|---|---|
| `APP_NAME` | Nama aplikasi yang tampil | "Verifikasi PRP Plant" |
| `APP_ENV` | Lingkungan aplikasi | `local` / `production` |
| `APP_KEY` | Kunci enkripsi aplikasi (generate otomatis) | `base64:xxx...` |
| `APP_DEBUG` | Mode debug (matikan di produksi!) | `false` |
| `APP_URL` | URL publik aplikasi | `https://sivera.perusahaan.com` |
| `APP_LOCALE` | Bahasa default antarmuka | `id` |
| `DB_CONNECTION` | Driver database | `mysql` |
| `DB_HOST` | Host database | `127.0.0.1` |
| `DB_PORT` | Port database | `3306` |
| `DB_DATABASE` | Nama database | `verifikasi_prp` |
| `DB_USERNAME` | Username database | `root` |
| `DB_PASSWORD` | Password database | `your_password` |
| `SESSION_DRIVER` | Driver penyimpanan sesi | `database` |
| `QUEUE_CONNECTION` | Driver antrean notifikasi | `database` |
| `CACHE_STORE` | Driver cache | `database` |
| `MAIL_MAILER` | Driver pengiriman email | `smtp` |
| `MAIL_HOST` | Host SMTP email | `smtp.gmail.com` |
| `MAIL_PORT` | Port SMTP | `465` |
| `MAIL_USERNAME` | Username/Alamat email pengirim | `sistem@perusahaan.com` |
| `MAIL_PASSWORD` | Password/App Password email | `your_app_password` |
| `MAIL_ENCRYPTION` | Enkripsi koneksi SMTP | `smtps` |
| `MAIL_FROM_ADDRESS` | Alamat email pengirim | `sistem@perusahaan.com` |
| `MAIL_FROM_NAME` | Nama pengirim email | "SIVERA System" |
| `IT_ADMIN_PIN` | PIN rahasia portal IT Super Admin | `xxxxxx` |
| `TEMUAN_RETENTION_YEARS` | Retensi data temuan (tahun) sebelum di-prune | `2` |
| `PASSKEYS_USER_HANDLE_SECRET` | Secret untuk Passkey/WebAuthn | `your_secret_string` |

> **CAUTION:** Jangan pernah menyimpan nilai asli `.env` ke dalam version control (git). File `.env` sudah terdaftar di `.gitignore`.

---

## Struktur Folder

```
project-aqua/
├── app/
│   ├── Console/Commands/     # Artisan commands (prp:prune-temuan)
│   ├── Http/
│   │   ├── Controllers/      # ExportController, BosqExportController, ItPortalAuthController
│   │   └── Middleware/       # RoleMiddleware, SystemGuardMiddleware
│   ├── Jobs/                 # Background jobs untuk pengiriman Email async
│   ├── Livewire/             # Komponen Livewire SIVERA
│   │   └── BosQ/             # Komponen Livewire BOSQ
│   ├── Mail/                 # Mailable: TemuanNotificationMail
│   ├── Models/               # Eloquent Models (Temuan, BosqTemuan, Karyawan, User, dll)
│   ├── Policies/             # Authorization policies (TemuanPolicy, BosqTemuanPolicy)
│   ├── Providers/            # AppServiceProvider, FortifyServiceProvider
│   └── Services/             # EmailNotificationService (rate-limited notif email)
├── database/
│   ├── migrations/           # Migrasi tabel SIVERA & BOSQ
│   ├── factories/            # Factory untuk testing
│   └── seeders/              # Seeder data awal
├── public/
│   ├── build/                # Asset frontend hasil npm run build
│   └── images/               # Aset gambar statis (logo, dll)
├── resources/
│   ├── css/
│   │   ├── app.css           # Stylesheet utama (Tailwind CSS v4)
│   │   ├── qa.css            # Stylesheet khusus halaman QA/SIVERA
│   │   └── bosq.css          # Stylesheet khusus halaman BOSQ
│   ├── js/
│   │   ├── app.js            # Entry point JavaScript utama
│   │   └── passkeys.js       # Logika WebAuthn/Passkey
│   └── views/
│       ├── layouts/          # Layout utama: app.blade.php, qa.blade.php, bosq.blade.php
│       ├── livewire/         # Blade views untuk komponen Livewire
│       │   └── bosq/         # Blade views khusus BOSQ
│       ├── pages/            # Halaman statis (beranda, detail temuan, auth)
│       │   ├── auth/         # Halaman login & IT portal login
│       │   ├── bosq/         # Halaman BOSQ
│       │   └── qa/           # Halaman QA (dashboard, rekap, master data)
│       ├── pdf/              # Template PDF DomPDF (temuan, rekap)
│       └── portal.blade.php  # Halaman pemilihan sistem (SIVERA / BOSQ)
├── routes/
│   ├── web.php               # Definisi rute web SIVERA & BOSQ
│   ├── settings.php          # Rute pengaturan profil & keamanan
│   └── console.php           # Definisi schedule & artisan commands
├── storage/
│   └── app/public/
│       ├── temuan/           # Foto temuan audit yang di-upload
│       └── bukti/            # Foto bukti perbaikan yang di-upload
└── tests/
    └── Feature/              # Feature tests (Pest 4)
```

---

## Route / Endpoint

### Publik (Tanpa Autentikasi)

| Method | URI | Nama Route | Deskripsi |
|---|---|---|---|
| `GET` | `/` | `portal` | Halaman portal pemilihan sistem (SIVERA / BOSQ) |
| `GET` | `/login` | — | Halaman login untuk Karyawan & QA |
| `POST` | `/login` | — | Proses autentikasi login |
| `GET` | `/admin-SiveraBosQ` | `it.login.form` | Form login rahasia IT Super Admin |
| `POST` | `/admin-SiveraBosQ` | `it.login.submit` | Proses login IT Super Admin (3-lapis) |

### SIVERA — Karyawan & QA (Auth Required)

| Method | URI | Nama Route | Role | Deskripsi |
|---|---|---|---|---|
| `GET` | `/beranda` | `beranda` | karyawan, qa | Beranda pelapor (form lapor + daftar temuan PIC) |
| `GET` | `/qa/dashboard` | `qa.dashboard` | qa | Dashboard analytics & grafik SIVERA |
| `GET` | `/qa/daftar-temuan` | `qa.daftar-temuan` | qa | Tabel daftar semua temuan untuk QA |
| `GET` | `/qa/rekap` | `qa.rekap` | qa | Rekap periode audit |
| `GET` | `/temuan/{id}` | `temuan.detail` | karyawan, qa | Detail temuan + form tindak lanjut PIC |
| `GET` | `/qa/master/karyawan` | `qa.master.karyawan` | qa | Master data PIC/karyawan |
| `GET` | `/qa/master/departemen` | `qa.master.departemen` | qa | Master data departemen |
| `GET` | `/qa/master/klausul` | `qa.master.klausul` | qa | Master data klausul PRP |
| `GET` | `/qa/master/akun` | `qa.master.akun` | superadmin | Manajemen akun user |
| `GET` | `/qa/master/seluruh-karyawan` | `qa.master.seluruh-karyawan` | superadmin | Pusat data seluruh karyawan |
| `GET` | `/export/excel` | `export.excel` | qa | Export rekap ke Excel |
| `GET` | `/export/pdf/daftar` | `export.pdf.daftar` | qa | Export PDF daftar temuan |
| `GET` | `/export/pdf/rekap` | `export.pdf.rekap` | qa | Export PDF rekap periode |
| `GET` | `/export/pdf/temuan/{id}` | `export.pdf.temuan` | qa | Export PDF detail satu temuan |

### BOSQ — Karyawan & QA (Auth Required)

| Method | URI | Nama Route | Role | Deskripsi |
|---|---|---|---|---|
| `GET` | `/bosq/beranda` | `bosq.beranda` | karyawan, qa | Beranda BOSQ (form observasi) |
| `GET` | `/bosq/qa/dashboard` | `bosq.qa.dashboard` | qa, bosq_pic | Dashboard analitik BOSQ |
| `GET` | `/bosq/qa/daftar-observasi` | `bosq.qa.daftar-observasi` | qa, bosq_pic | Daftar semua laporan observasi |
| `GET` | `/bosq/qa/rekap` | `bosq.qa.rekap` | qa | Rekap kepatuhan perilaku BOSQ |
| `GET` | `/bosq/temuan/{id}` | `bosq.temuan.detail` | karyawan, qa | Detail laporan observasi |
| `GET` | `/bosq/qa/master/line` | `bosq.qa.master.line` | qa | Master Line BOSQ |
| `GET` | `/bosq/qa/master/subarea` | `bosq.qa.master.subarea` | qa | Master Sub Area BOSQ |
| `GET` | `/bosq/qa/master/elemen` | `bosq.qa.master.elemen` | qa | Master Elemen QFS |
| `GET` | `/bosq/qa/master/karyawan` | `bosq.qa.master.karyawan` | qa | Master Karyawan BOSQ |
| `GET` | `/bosq/qa/export/csv` | `bosq.qa.export.csv` | qa, bosq_pic | Export dashboard ke CSV |
| `GET` | `/bosq/qa/export/pdf/dashboard` | `bosq.qa.export.pdf.dashboard` | qa, bosq_pic | Export dashboard ke PDF |
| `GET` | `/bosq/qa/export/rekap/csv` | `bosq.qa.export.rekap.csv` | qa | Export rekap ke CSV |
| `GET` | `/bosq/qa/export/rekap/pdf` | `bosq.qa.export.rekap.pdf` | qa | Export rekap ke PDF |

---

## Modul Operasional

### 1. Modul Autentikasi & Otorisasi

- **File Terkait:** `app/Providers/FortifyServiceProvider.php`, `app/Http/Controllers/ItPortalAuthController.php`, `app/Http/Middleware/RoleMiddleware.php`, `app/Http/Middleware/SystemGuardMiddleware.php`
- **Alur Kerja:** Pengguna memasukkan Nama/NIK + Password → Fortify mencari `User` berdasarkan `name` atau `nik` → Jika cocok, sesi dibuat dengan konteks sistem (`login_system = sivera/bosq`) → Middleware `role` memvalidasi akses per halaman
- **Fitur Khusus:** Portal IT Admin di `/admin-SiveraBosQ` menambahkan validasi PIN 4-digit sebelum sesi dibuat

### 2. Modul Workflow Temuan (SIVERA 4-Tier)

- **File Terkait:** `app/Livewire/FormTemuan.php`, `app/Livewire/TindakLanjutPIC.php`, `app/Livewire/VerifikasiQA.php`, `app/Livewire/DaftarTemuanQA.php`
- **Alur Status:** `open` (Merah) → PIC isi action + due_date → `in_progress` (Oranye) → PIC upload foto bukti → `closed_pending_qa` (Biru) → QA ACC → `closed_acc` (Hijau)
- **Auto-ACC:** Jika Divisi Manajemen tidak perlu verifikasi QA, status langsung ke `closed_acc` saat bukti diunggah

### 3. Modul Notifikasi Email

- **File Terkait:** `app/Services/EmailNotificationService.php`, `app/Mail/TemuanNotificationMail.php`
- **Alur Kerja:** Setiap perubahan status temuan → `EmailNotificationService::sendSiveraNotification()` / `sendBosqNotification()` dipanggil → Dicek rate-limit (cooldown 2 jam via Cache) → Jika lolos, email dikirim via SMTP/Resend
- **Penerima:** `baru` ke PIC & QA, `tindaklanjut` ke QA, `bukti` ke QA, `closed` ke PIC & Pelapor

### 5. Modul Queue Worker (Async)

- **Konfigurasi:** `QUEUE_CONNECTION=database` di `.env` — antrean disimpan di tabel `jobs` MySQL
- **Menjalankan (Dev):** `php artisan queue:work`
- **Menjalankan (Production):** Gunakan **Supervisor** agar worker berjalan 24/7 otomatis
- **Job yang Diproses:** Notifikasi Email (Gmail SMTP) via `TemuanNotificationMail` — dikirim secara async melalui queue

### 6. Modul Retensi Data Otomatis (Scheduler)

- **File Terkait:** `app/Console/Commands/PruneOldTemuan.php`
- **Command:** `php artisan prp:prune-temuan [--years=N]`
- **Fungsi:** Menghapus data temuan beserta foto pendukung yang sudah melewati batas retensi (default: 2 tahun, dikonfigurasi via `TEMUAN_RETENTION_YEARS`)
- **Jadwal (Cron):** Daftarkan di Cron Job server: `* * * * * cd /path/to/project && php artisan schedule:run >> /dev/null 2>&1`

### 7. Modul Enkripsi Data

- **File Terkait:** `app/Models/Temuan.php`, `app/Models/TindakLanjut.php`
- **Kolom Terenkripsi:** `temuan.deskripsi`, `temuan.saran`, `tindak_lanjut.catatan_qa`
- **Mekanisme:** Eloquent `encrypted` cast — data dienkripsi/didekripsi otomatis menggunakan `APP_KEY` saat disimpan/dibaca dari database

### 8. Modul Kompresi Foto

- **Mekanisme:** GD Library PHP — foto yang diupload dikompresi otomatis ke format JPG/PNG dengan kualitas teroptimasi sebelum disimpan ke `storage/app/public/temuan/` atau `storage/app/public/bukti/`

---

## Panduan Instalasi & Pengembangan

### Clone & Setup

```bash
# 1. Clone repository
git clone <repository-url> project-aqua
cd project-aqua

# 2. Install PHP dependencies
composer install

# 3. Salin dan konfigurasi environment
cp .env.example .env
# Edit .env sesuai konfigurasi database & layanan Anda

# 4. Generate application key
php artisan key:generate

# 5. Jalankan migrasi database
php artisan migrate

# 6. Buat symlink storage (untuk akses publik foto upload)
php artisan storage:link

# 7. Install Node.js dependencies & build assets
npm install
npm run build
```

### Menjalankan di Development

Gunakan script composer bawaan untuk menjalankan semua service sekaligus:

```bash
composer run dev
```

Script di atas secara otomatis akan menjalankan:
- `php artisan serve` — PHP development server
- `php artisan queue:listen --tries=1` — Queue worker
- `npm run dev` — Vite HMR untuk aset frontend

### Optimasi untuk Production

```bash
php artisan optimize
php artisan view:cache
php artisan event:cache
npm run build
```

---

## Kontribusi (Contributing)

### Setup & Branching

```bash
# Selalu buat branch baru dari main untuk setiap fitur/bugfix
git checkout -b feature/nama-fitur
# atau
git checkout -b fix/deskripsi-bugfix
```

### Coding Convention

Proyek ini menggunakan **Laravel Pint** untuk code style PHP dan **PHPStan Level 5** via Larastan untuk static analysis.

```bash
# Cek dan perbaiki code style otomatis
composer run lint

# Hanya cek (tanpa perbaikan) — untuk CI
composer run lint:check

# Cek static typing
composer run types:check
```

### Menjalankan Tests

```bash
# Jalankan seluruh test suite (dengan linting + static analysis)
composer run test

# Hanya unit/feature tests
php artisan test --parallel
```

### Membuat Pull Request

1. Pastikan semua tests lolos dengan `composer run test`
2. Push branch ke repository dan buat Pull Request
3. Deskripsikan perubahan yang dibuat, sertakan screenshot jika ada perubahan UI
4. Assign ke maintainer untuk review

> **PENTING:** Jangan pernah melakukan perubahan langsung ke branch `main`. Selalu gunakan Pull Request untuk review.

---

## Kontak & Kredit

### Maintainer

| Peran | Nama | GitHub |
|---|---|---|
| Developer | Mhd Fahri Irfandi Dewantara | [FahriID563](https://github.com/FahriID563) |
| Developer | Farhan Hakim | [Spy06](https://github.com/Spy06) |

### Ucapan Terima Kasih

Proyek ini dibangun di atas fondasi library & tools open-source berikut:

| Library/Tool | Peran dalam Proyek |
|---|---|
| [Laravel](https://laravel.com) | Backend framework utama |
| [Livewire](https://livewire.laravel.com) | Reactive UI tanpa SPA JavaScript |
| [Livewire Flux](https://fluxui.dev) | Komponen UI Livewire premium |
| [Tailwind CSS v4](https://tailwindcss.com) | Utility-first CSS framework |
| [Chart.js](https://www.chartjs.org) | Visualisasi data interaktif |
| [Laravel DomPDF](https://github.com/barryvdh/laravel-dompdf) | Generasi dokumen PDF |
| [Resend Laravel](https://github.com/resendlabs/resend-laravel) | SDK alternatif pengiriman Email transaksional |
| [Pest PHP](https://pestphp.com) | Testing framework modern |
| [Larastan](https://github.com/larastan/larastan) | Static analysis untuk Laravel |
| [Laravel Pint](https://laravel.com/docs/pint) | Code style formatter |

---

*README ini dihasilkan berdasarkan penelusuran menyeluruh terhadap source code proyek per 12 Agustus 2026.*






