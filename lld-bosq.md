# Low Level Design (LLD)
## Sistem BOS'Q (Behavior Observation System Quality)

**Disiapkan oleh:** Mhd. Fahri Irfandi Dewantara  
**Proyek:** BOS'Q Plant Cianjur  
**Versi:** 1.0  
**Tanggal:** Agustus 2026

---

## 1. Pendahuluan

Dokumen Low Level Design (LLD) ini merincikan spesifikasi tingkat komponen teknis dari sistem BOS'Q. Dokumen ini menjadi acuan utama bagi pengembang dalam memahami rancangan basis data (*database schema*), arsitektur *routing*, hierarki kelas (*class hierarchy*), dan alur komponen antarmuka pengguna berbasis Laravel dan Livewire untuk modul observasi perilaku.

---

## 2. Arsitektur Sistem

Sistem BOS'Q merupakan modul yang terintegrasi secara *monolithic* dengan SIVERA namun terisolasi secara logis pada lapisan rute dan basis data.
- **Backend:** Laravel 13 (PHP 8.4)
- **Frontend State Management:** Livewire 4 + Alpine.js
- **UI Components:** Livewire Flux 2 + Tailwind CSS v4
- **Database:** MySQL 8

---

## 3. Desain Basis Data (Database Schema)

Sistem menggunakan database relasional. Untuk otentikasi, sistem ini berbagi tabel `users` dengan SIVERA, namun menyimpan transaksi observasi pada tabel-tabel berspesifik `bosq_`.

### 3.1 Tabel Master Data BOS'Q
1. **`bosq_line`**
   - `id` (BIGINT, PK, Auto-increment)
   - `nama_line` (VARCHAR)
   - `default_auditee_id` (BIGINT, Nullable, FK ke `users.id`)
2. **`bosq_sub_area`**
   - `id` (BIGINT, PK)
   - `departemen_id` (BIGINT, Nullable, FK ke `departemen.id`)
   - `nama_sub_area` (VARCHAR)
3. **`bosq_sub_area_pics`** (Tabel Pivot / Relasi M:N)
   - `id` (BIGINT, PK)
   - `sub_area_id` (BIGINT, FK ke `bosq_sub_area.id`)
   - `user_id` (BIGINT, FK ke `users.id`)
   - *Constraint:* `UNIQUE(sub_area_id, user_id)`
4. **`bosq_elemen_qfs`**
   - `id` (BIGINT, PK)
   - `nama_elemen` (VARCHAR)
   - `deskripsi` (TEXT, Nullable)

### 3.2 Tabel `bosq_temuan` (Laporan Observasi)
Inti transaksi observasi.
- `id` (BIGINT, PK, Auto-increment)
- `tanggal_temuan` (DATE)
- `pelapor_id` (BIGINT, FK ke `users.id`)
- `auditee_id` (BIGINT, FK ke `users.id`)
- `departemen_id` (BIGINT, FK ke `departemen.id`)
- `line_id` (BIGINT, Nullable, FK ke `bosq_line.id`)
- `sub_area_id` (BIGINT, FK ke `bosq_sub_area.id`)
- `detail_sub_area` (VARCHAR, Nullable)
- `elemen_qfs_id` (BIGINT, FK ke `bosq_elemen_qfs.id`)
- `temuan_bqa` (TEXT, **Encrypted**)
- `tingkat_resiko` (VARCHAR) — *Enum logis: `food_safety_risk`, `major_quality_risk`, `minor_quality_risk`*
- `dampak_temuan` (VARCHAR) — *Enum logis: `positif`, `negatif`*
- `status` (VARCHAR) — *`open`, `closed_pending_qa`, `closed_acc`*

### 3.3 Tabel `bosq_tindak_lanjut`
Menyimpan langkah perbaikan (*action plan*). Berelasi 1-to-1 dengan `bosq_temuan`.
- `id` (BIGINT, PK)
- `bosq_temuan_id` (BIGINT, FK ke `bosq_temuan.id`)
- `action` (TEXT, Nullable)
- `due_date` (DATE, Nullable)
- `foto_bukti_path` (TEXT, Nullable) — Menyimpan teks path file foto bukti
- `status` (VARCHAR, default: `open`)
- `acc_qa` (BOOLEAN, default: `false`)
- `tanggal_acc` (DATE, Nullable)
- `catatan_qa` (TEXT, Nullable, **Encrypted**)

---

## 4. Desain Kelas dan Komponen (Class Hierarchy)

### 4.1 Models (Eloquent)
Semua Model memiliki proteksi *mass assignment* (`$guarded = ['id']`) dan merelasikan ke skema tabel yang tepat.
- **`App\Models\BosqTemuan`**: 
  - Relasi: `belongsTo(User::class, 'pelapor_id')`, `belongsTo(User::class, 'auditee_id')`, `belongsTo(BosqLine::class)`, `belongsTo(BosqElemenQfs::class)`, `hasOne(BosqTindakLanjut::class)`.
- **`App\Models\BosqTindakLanjut`**:
  - Relasi: `belongsTo(BosqTemuan::class)`.
- **`App\Models\User`**:
  - Memiliki relasi pivot spesifik: `bosqSubAreas()` yang mengaitkan user ke tabel `bosq_sub_area_pics` sebagai *PIC Sub Area*. Memiliki method *helper* `isBosqPicUser()`.

### 4.2 Livewire Components (Frontend Logic)
Komponen-komponen spesifik BOS'Q yang terisolasi di direktori `App\Livewire\BosQ\`:
- `FormObservasi`: Mengatur input pengamatan, dropdown *cascading* (Line → Sub Area), dan unggah foto.
- `DashboardAnalitik`: Menjalankan query agregasi untuk memvisualisasikan `Chart.js` berdasarkan tren elemen QFS, tingkat risiko, dan dampak observasi.
- `DaftarObservasi`: Grid datatables untuk menampilkan laporan perilaku.
- `Master/BosqLine` & `Master/BosqSubArea`: Komponen administratif pengelolaan master data BOS'Q, khusus QA.

---

## 5. Kontrak Rute dan Keamanan (*Routing & Middleware*)

Seluruh rute BOS'Q dilindungi oleh Middleware otentikasi serta `system_guard:bosq` untuk mencegah intrusi sesi silang (*cross-session*) dari pengguna yang masuk dengan *Guard* SIVERA.

| Rute (URI) | Method | Middleware | Deskripsi |
|---|---|---|---|
| `/bosq/beranda` | `GET` | `auth, system_guard:bosq` | Beranda input observasi perilaku. |
| `/bosq/qa/dashboard` | `GET` | `auth, system_guard:bosq, role:qa`| Dashboard analitik grafis QA. |
| `/bosq/temuan/{id}` | `GET` | `auth, system_guard:bosq` | Halaman detail suatu laporan observasi. |
| `/bosq/qa/export/csv` | `GET` | `auth, system_guard:bosq, role:qa`| Endpoint ekspor dataset agregasi ke CSV. |

> **Catatan Keamanan:** Pengguna hanya dapat mengakses sistem BOS'Q bila mereka melalui halaman Portal pemilihan sistem dan menset nilai *session* `login_system` menjadi `bosq`.

---

## 6. Diagram Alur Transisi Status (State Machine)

Alur verifikasi BOS'Q sedikit lebih sederhana dibandingkan SIVERA karena fokus pada pelaporan observasi cepat.

1. **`open`:** Status awal laporan. Karyawan mengajukan hasil pantauan perilakunya di area bersangkutan.
2. **`closed_pending_qa`:** PIC di lapangan melengkapi temuan dengan bukti perbaikan (*foto bukti*) ke dalam `bosq_tindak_lanjut`.
3. **`closed_acc`:** QA memvalidasi observasi (dan *action plan*-nya jika temuan tersebut adalah *Unsafe Behavior*), kemudian menekan tombol "Verifikasi/ACC" di dasbor QA. Transaksi dikunci dan datanya dilemparkan ke tabel agregat analitik.

---

## 7. Pekerjaan Latar Belakang (*Background Jobs & Scheduler*)

- **Queue Worker (Email):** Setiap *event* pembuatan observasi (`BosqTemuanCreated`) memicu job *queue* asinkron untuk mengirimkan rangkuman laporan via SMTP ke PIC terkait (Berdasarkan relasi tabel `bosq_sub_area_pics`), mematuhi aturan jeda *rate-limit* pengiriman 2 jam (menggunakan `Cache`).
