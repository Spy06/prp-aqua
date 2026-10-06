# Spesifikasi Kebutuhan Perangkat Lunak
## untuk BOS'Q (Behavior Observation System Quality)

**Versi 1.0 disetujui**  
**Disiapkan oleh Mhd. Fahri Irfandi Dewantara**  
**Pabrik Aqua**  
**Agustus 2026**

---

## Daftar Isi
1. Perkenalan
2. Deskripsi Keseluruhan
3. Persyaratan Antarmuka Eksternal
4. Fitur Sistem
5. Persyaratan Nonfungsional Lainnya
6. Persyaratan Lainnya
Lampiran A: Glosarium
Lampiran B: Model Analisis
Lampiran C: Daftar Yang Akan Ditentukan

---

## Riwayat Revisi
| Nama | Tanggal | Alasan Perubahan | Versi |
|---|---|---|---|
| Mhd. Fahri Irfandi Dewantara | 14/08/2026 | Pembuatan dokumen awal menyesuaikan standar IEEE (Karl E. Wiegers) | 1.0 |

---

## 1. Perkenalan

### 1.1 Tujuan
Spesifikasi Kebutuhan Perangkat Lunak (SRS) ini ditujukan untuk mendefinisikan persyaratan fungsional dan non-fungsional dari perangkat lunak **BOS'Q (Behavior Observation System Quality)** versi 1.0. Dokumen ini menjelaskan secara menyeluruh seluruh fitur aplikasi yang berfokus pada observasi kepatuhan perilaku keselamatan dan kualitas karyawan di lapangan.

### 1.2 Konvensi Dokumen
Setiap persyaratan fungsional di Bagian 4 akan diidentifikasi dengan awalan unik `REQ-` yang diikuti dengan angka urut (contoh: REQ-1, REQ-2). Prioritas persyaratan (Tinggi, Sedang, Rendah) akan dicantumkan pada masing-masing fitur.

### 1.3 Audiens yang Dituju dan Saran Bacaan
Dokumen ini ditujukan untuk Pengembang Perangkat Lunak, Tim Quality Assurance (QA), dan Manajemen Pabrik. Disarankan untuk membaca bagian Deskripsi Keseluruhan (Bab 2) terlebih dahulu untuk memahami konteks ruang lingkup observasi perilaku sebelum masuk ke rincian teknis di Bab 4.

### 1.4 Lingkup Produk
BOS'Q adalah sistem berbasis web yang ditujukan untuk digitalisasi proses observasi perilaku (*behavior observation*) terhadap standar keselamatan dan kualitas (QFS - *Quality & Food Safety*) karyawan.
Manfaat utama sistem ini adalah mendorong budaya pengawasan aktif (*active supervision*) di area produksi (berdasarkan *Line* dan *Sub Area*), merekam perilaku tidak aman atau tidak sesuai standar secara *real-time*, memverifikasinya melalui tim QA, dan mengekspor hasilnya sebagai landasan program pelatihan/perbaikan perilaku di pabrik.

### 1.5 Referensi
- Dokumen *Product Requirements Document* (PRD) Sistem Operasional BOS'Q.
- Dokumentasi README.md.
- Standar *Quality & Food Safety* (QFS) Pabrik Aqua.

---

## 2. Deskripsi Keseluruhan

### 2.1 Perspektif Produk
BOS'Q beroperasi berdampingan dengan sistem SIVERA (Verifikasi PRP) dalam satu instalasi platform yang sama. Meskipun menggunakan kredensial *user* yang sama, akses ke dalam BOS'Q diisolasi secara khusus melalui *System Guard Middleware* (`bosq` guard), sehingga data observasi perilaku tidak bercampur dengan temuan audit fasilitas.

### 2.2 Fungsi Produk
Fungsi-fungsi utama yang disediakan oleh BOS'Q meliputi:
- Pengisian Form Laporan Observasi perilaku berdasarkan Elemen QFS.
- Verifikasi dan Penutupan laporan observasi oleh QA.
- Visualisasi Tren Observasi (berdasarkan *Line* & *Sub Area*) melalui Dashboard Analitik QA.
- Ekspor data Dashboard dan Rekap Kepatuhan periodik ke format CSV dan PDF.
- Manajemen Master Data spesifik BOS'Q (Line, Sub Area, Elemen QFS, Karyawan).
- Pengiriman notifikasi Email otomatis terkait laporan observasi.

### 2.3 Kelas dan Karakteristik Pengguna
1. **Karyawan / PIC BOS'Q:** Bertugas melakukan observasi rekan kerja atau bawahan dan memasukkan laporannya ke sistem melalui HP/Mobile.
2. **QA Auditor:** Memverifikasi laporan observasi yang masuk, menganalisis grafik tren, dan merekap data kepatuhan dari PC/Desktop.
3. **Super Admin (IT):** Pengelola sistem yang memiliki otoritas untuk mengatur akun pengguna (via `/admin-SiveraBosQ`) tanpa mengakses data observasi BOS'Q secara fungsional.

### 2.4 Lingkungan Operasi
- **Platform Pengguna:** Web Browser (Desktop & Mobile).
- **Backend:** PHP 8.4 dengan framework Laravel 13.
- **Frontend:** Livewire 4 + Livewire Flux 2 + Alpine.js dengan Tailwind CSS v4.
- **Database:** MySQL 8 / MariaDB.

### 2.5 Kendala Desain dan Implementasi
- Sistem harus menanggung entri data yang sangat cepat dan repetitif karena observasi perilaku dilakukan secara harian oleh banyak karyawan. 
- *Rate-Limiting Email:* Karena volume laporan yang tinggi, pengiriman notifikasi Email wajib dikenakan jeda (*cooldown*) 2 jam agar *inbox* QA tidak di-spam oleh ratusan laporan observasi harian.

### 2.6 Dokumentasi Pengguna
Sistem menyediakan panduan langsung melalui *tooltips* dan antarmuka komponen berbasis *Livewire Flux* yang intuitif dan *self-explanatory*.

### 2.7 Asumsi dan Ketergantungan
Kegagalan layanan pengiriman Email (*SMTP/Resend*) tidak boleh memblokir proses input laporan observasi harian oleh karyawan di lapangan.

---

## 3. Persyaratan Antarmuka Eksternal

### 3.1 Antarmuka Pengguna
Antarmuka pengguna menggunakan *Livewire Flux 2* untuk komponen UI modern yang ringan. Halaman Beranda (`/bosq/beranda`) langsung menyajikan formulir observasi ringkas yang disesuaikan untuk layar *smartphone*.

### 3.2 Antarmuka Perangkat Keras
Tidak ada perangkat keras spesifik selain *smartphone* atau komputer milik karyawan yang membutuhkan akses internet via Browser.

### 3.3 Antarmuka Perangkat Lunak
Backend Laravel terhubung ke database `verifikasi_prp` dan menggunakan Queue Worker (dikendalikan oleh *Supervisor*) untuk memproses notifikasi secara asinkron (di latar belakang).

### 3.4 Antarmuka Komunikasi
Protokol HTTP/HTTPS untuk akses pengguna, dan antarmuka SMTP (atau *Resend* API) untuk pengiriman notifikasi Email yang diamankan dengan enkripsi `smtps`.

---

## 4. Fitur Sistem

### 4.1 Autentikasi dan Manajemen Sesi Terintegrasi
**4.1.1 Deskripsi dan Prioritas**
Penyediaan akses ke BOS'Q melalui sistem login ganda (Passkey dan Nama/NIK). (Prioritas: Tinggi).

**4.1.2 Urutan Stimulus/Respon**
Pengguna membuka halaman portal `/`, memilih sistem BOS'Q, kemudian masuk via Passkey (biometrik) atau kredensial NIK. Sistem mengarahkan pengguna ke `/bosq/beranda` atau `/bosq/qa/dashboard` sesuai *Role*.

**4.1.3 Persyaratan Fungsional**
- **REQ-1:** Sistem harus menyediakan fitur login *passwordless* dengan *Passkey/WebAuthn*.
- **REQ-2:** Sistem harus membatasi sesi pengguna murni pada ruang lingkup BOS'Q melalui pengecekan `system_guard:bosq`.
- **REQ-3:** Sistem harus mengakhiri sesi pengguna secara otomatis saat melampaui `expires_at` dari master akun.

### 4.2 Pelaporan Observasi Perilaku
**4.2.1 Deskripsi dan Prioritas**
Formulir bagi karyawan untuk mencatat perilaku aman (Safe) atau tidak aman (Unsafe) berdasarkan elemen *Quality & Food Safety* (QFS). (Prioritas: Tinggi).

**4.2.2 Urutan Stimulus/Respon**
Karyawan memilih Elemen QFS, Line, dan Sub Area. Karyawan memilih status *Safe/Unsafe* dan menyimpan form.

**4.2.3 Persyaratan Fungsional**
- **REQ-4:** Sistem harus merekam laporan observasi perilaku secara detail termasuk relasinya terhadap Master Line dan Master Elemen QFS.
- **REQ-5:** Sistem harus otomatis mendelegasikan laporan ke Queue Worker untuk mengirimkan Email peringatan kepada QA.
- **REQ-6:** Sistem harus menerapkan *cooldown* 2 jam pada pengiriman Email ke tujuan yang sama untuk menghindari *spam*.

### 4.3 Verifikasi QA dan Analitik Tren
**4.3.1 Deskripsi dan Prioritas**
Dashboard khusus QA untuk memverifikasi laporan harian dan memvisualisasikan tren observasi *Line/Sub Area* dalam bentuk grafik. (Prioritas: Tinggi).

**4.3.2 Urutan Stimulus/Respon**
QA membuka Dashboard BOS'Q, menganalisis grafik *Bar/Donut*, memeriksa daftar observasi, memverifikasi status laporan, dan mengekspor hasilnya.

**4.3.3 Persyaratan Fungsional**
- **REQ-7:** Sistem harus menampilkan analitik perilaku visual (*Chart.js*) yang bisa difilter berdasarkan bulan, tahun, *Line*, dan *Sub Area*.
- **REQ-8:** QA dapat memverifikasi laporan observasi yang masuk melalui halaman detail laporan.
- **REQ-9:** Sistem harus mampu mengekspor *Dashboard Analytics* BOS'Q ke format CSV dan PDF.
- **REQ-10:** Sistem harus mampu mengekspor data *Rekap Kepatuhan* ke format CSV (menggunakan `league/csv`) dan PDF (menggunakan `dompdf`).

### 4.4 Master Data BOS'Q
**4.4.1 Deskripsi dan Prioritas**
Manajemen elemen data yang mendukung klasifikasi observasi. (Prioritas: Sedang).

**4.4.2 Urutan Stimulus/Respon**
QA membuka halaman Master, menambahkan daftar *Line* pabrik, atau menambahkan kategori *Elemen QFS* yang baru.

**4.4.3 Persyaratan Fungsional**
- **REQ-11:** Sistem harus mengizinkan QA menambah, mengubah, dan menonaktifkan *Master Line* dan *Master Sub Area* khusus BOS'Q.
- **REQ-12:** Sistem harus mengizinkan QA menambah, mengubah, dan menonaktifkan *Master Elemen QFS*.
- **REQ-13:** Sistem harus mengizinkan QA mengelola daftar Karyawan/PIC BOS'Q.

---

## 5. Persyaratan Nonfungsional Lainnya

### 5.1 Persyaratan Kinerja
- Form laporan observasi harus sangat ringan karena digunakan terus-menerus. Waktu simpan data (*save to database*) tidak boleh melebihi 1,5 detik.
- Ekspor *Dashboard* dan *Rekap Kepatuhan* ke PDF berkapasitas besar maksimal membutuhkan 5 detik pemrosesan *rendering*.

### 5.2 Persyaratan Keselamatan
- Proses pengiriman notifikasi wajib diletakkan pada sistem latar belakang (melalui implementasi `Queue::push()`) agar interupsi jaringan SMTP Gmail/Resend tidak menggagalkan fungsi *input* observasi dari karyawan di pabrik.

### 5.3 Persyaratan Keamanan
- Sama seperti SIVERA, kolom deskriptif di observasi (seperti keterangan perilaku) dienkripsi melalui Laravel Application Encryption di sisi ORM.

### 5.4 Atribut Kualitas Perangkat Lunak
- Sistem dikembangkan dengan kepatuhan tinggi terhadap standar *Clean Code*. Penulisan logika divalidasi oleh *Larastan* (Level 5) untuk memastikan *static analysis typing* yang ketat, serta pengujian otomatis menggunakan *Pest PHP v4*.

### 5.5 Peraturan Bisnis
- Jika status suatu *Master Line* atau *Master Elemen QFS* dinonaktifkan oleh QA, elemen tersebut tidak akan muncul lagi di *dropdown* laporan karyawan, namun data historis observasi yang menggunakan elemen tersebut tetap dipertahankan dan dihitung di dalam grafik *Dashboard*.

---

## 6. Persyaratan Lainnya
- Arsitektur pengkodean mengharuskan penggunaan struktur *Single File Component (SFC)* via *Livewire Volt* khusus untuk komponen Frontend interaktif.

---

## Lampiran A: Glosarium
- **BOS'Q:** *Behavior Observation System Quality*.
- **Line & Sub Area:** Pembagian wilayah geografis atau jalur produksi mesin di dalam pabrik Aqua.
- **Elemen QFS:** *Quality & Food Safety Element*. Kategori pilar keselamatan pangan/karyawan yang menjadi tolok ukur observasi (contoh: APD, Penanganan Bahan Kimia, Sanitasi).
- **Passkey / WebAuthn:** Standar global untuk autentikasi *passwordless* yang memanfaatkan sensor biometrik (seperti sidik jari / Face ID) di perangkat pengguna.
- **Safe / Unsafe:** Nilai status hasil observasi perilaku karyawan.

## Lampiran B: Model Analisis
*(Disimpan pada repositori kode sumber perusahaan).*

## Lampiran C: Daftar Yang Akan Ditentukan
- Tidak ada (*None*). Seluruh spesifikasi dasar sistem telah terpenuhi dan diimplementasi.
