# PANDUAN PENGGUNAAN SISTEM VERIFIKASI PRP PLANT

Sistem Verifikasi PRP Plant digunakan untuk melaporkan dan menindaklanjuti temuan ketidaksesuaian PRP (Prerequisite Program) di area pabrik secara cepat, terstruktur, dan terpantau.

---

## 1. CARA MASUK KE SISTEM (LOGIN)

1. Buka alamat website sistem di web browser Anda.
2. Pada halaman login:
   - **Username**: Masukkan **NIK (Nomor Induk Karyawan)** Anda (contoh: `2024001`).
   - **Password**: Masukkan password akun Anda.
3. Klik tombol **Masuk**.

> [!NOTE]
> Sistem ini tidak mendukung pendaftaran akun secara publik. Akun Anda hanya dapat dibuat dan diaktifkan oleh tim **Quality Assurance (QA)**.

---

## 2. PANDUAN UNTUK PELAPOR (MEMBUAT LAPORAN TEMUAN BARU)

Jika Anda menemukan ketidaksesuaian PRP di area kerja:

1. Di halaman **Beranda**, pastikan Anda berada pada tab **Pelapor**.
2. Isi formulir **Lapor Temuan Baru**:
   - **Tanggal Temuan**: Terisi otomatis dengan tanggal hari ini.
   - **Departemen**: Pilih departemen lokasi temuan dari daftar pilihan.
   - **Sub Area**: Tulis lokasi spesifik temuan (contoh: `Line 1 Filling Room`).
   - **Foto Temuan**: Unggah foto bukti temuan ketidaksesuaian tersebut (format gambar, maksimal 5MB).
   - **Deskripsi**: Tulis deskripsi detail temuan ketidaksesuaian yang terjadi.
   - **Cari PIC**: Ketik NIK atau nama karyawan yang akan ditugaskan sebagai PIC untuk menyelesaikan temuan ini. Klik pada nama karyawan yang muncul untuk memilih.
3. Klik tombol **Submit Laporan**.
4. Setelah berhasil, PIC yang ditunjuk akan menerima notifikasi otomatis via WhatsApp yang berisi detail singkat temuan beserta link langsung menuju laporan tersebut.

---

## 3. PANDUAN UNTUK PIC (MENINDAKLANJUTI TEMUAN)

Sebagai PIC yang ditunjuk untuk menyelesaikan suatu temuan:

1. Anda akan mendapatkan pesan WhatsApp berisi link laporan (contoh: `/temuan/1`).
2. Klik link tersebut. Jika Anda belum login, sistem akan meminta Anda login terlebih dahulu. Setelah login, Anda akan otomatis diarahkan ke halaman detail temuan tersebut.
3. Di halaman detail temuan, scroll ke bawah ke bagian **Form Tindak Lanjut PIC**:
4. **Langkah Pengerjaan**:
   - **Pilih Klausul PRP**: Pilih klausul standar PRP yang sesuai dari daftar pilihan.
   - **Tindakan Perbaikan**: Tuliskan tindakan nyata yang Anda lakukan untuk memperbaiki temuan tersebut.
   - **Due Date (Batas Waktu)**: Tentukan batas waktu penyelesaian.
   - Klik tombol **Simpan Detail** untuk menyimpan informasi awal.
   - Klik tombol **Mulai Pengerjaan (In Progress)** untuk menandakan Anda sedang mengerjakan perbaikan.
5. **Unggah Bukti & Selesaikan**:
   - Setelah pekerjaan selesai, unggah foto bukti perbaikan pada kolom **Foto Bukti**.
   - Klik tombol **Selesai & Kirim ke QA** untuk mengirim laporan ke QA guna diverifikasi.
   - Sistem akan mengirim notifikasi WhatsApp otomatis ke QA untuk melakukan pemeriksaan.

---

## 4. PANDUAN UNTUK QUALITY ASSURANCE (QA)

Sebagai tim QA, Anda memiliki kontrol penuh untuk memverifikasi temuan, mengunduh rekap data, dan mengelola data master.

### A. Verifikasi Temuan PIC
1. Buka detail temuan dari Dashboard atau dari link WhatsApp yang Anda terima.
2. Di bagian bawah halaman, Anda akan melihat panel **Verifikasi QA** (hanya muncul jika status temuan adalah *Closed Pending QA*).
3. Periksa deskripsi tindakan dan foto bukti yang dikirim oleh PIC.
4. **Menyetujui Temuan**:
   - Jika perbaikan sudah sesuai, klik **Setujui**.
   - Status temuan akan berubah menjadi **Closed ACC** (selesai). Pelapor dan PIC akan mendapat notifikasi WhatsApp bahwa temuan resmi ditutup.
5. **Menolak Temuan**:
   - Jika perbaikan belum sesuai, isi **Catatan QA** (alasan penolakan), lalu klik **Tolak**.
   - Status temuan akan dikembalikan ke **In Progress**. PIC terkait akan mendapat notifikasi WhatsApp beserta catatan perbaikan dari Anda.

### B. Mengunduh Rekap Data (Export)
1. Buka menu **Rekap Periode** di sidebar kiri.
2. Pilih filter yang diinginkan:
   - **Per Bulan**: Pilih bulan dan tahun.
   - **Per Tahun**: Pilih tahun tertentu.
   - **Custom Range**: Pilih rentang tanggal awal dan akhir secara bebas.
3. Klik **Export Excel** untuk mendownload berkas Excel (.csv) berisi seluruh data temuan sesuai filter.
4. Klik **Export PDF** untuk mendownload dokumen rekap PDF berformat landscape yang rapi.
5. Untuk mendownload berkas PDF untuk **satu kasus temuan** (untuk kebutuhan audit/arsip), klik tombol **PDF** di kolom aksi pada tabel daftar temuan di Dashboard QA atau halaman Rekap.

### C. Mengelola Data Master (Hanya QA)
Melalui sidebar kiri bagian **Master Data**, QA dapat mengelola data pendukung sistem:
- **Karyawan**: Menambah, mengubah nama/departemen, atau menonaktifkan karyawan (karyawan non-aktif tidak bisa dibuatkan akun).
- **Departemen**: Menambah, mengubah nama, atau menghapus departemen (tidak bisa dihapus jika masih ada karyawan/temuan terikat).
- **Klausul PRP**: Menambah, mengubah, atau menghapus klausul acuan audit.
- **Akun User**: Membuat akun baru untuk karyawan aktif, mengubah no WhatsApp, mengubah role (karyawan/qa), atau melakukan reset password.
