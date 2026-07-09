# Jobdesk & batasan kerja AI — pengembangan sistem verifikasi PRP plant

Dokumen ini adalah pagar pengaman (guardrail) untuk AI coding agent (mis. Antigravity) yang mengerjakan pembangunan sistem ini, supaya progress harian tetap sejalan dengan tujuan awal dan tidak menyimpang dari spesifikasi yang sudah disepakati. Dokumen pendamping: `implementation-plan-verifikasi-prp.md` (spesifikasi teknis) dan `prd-verifikasi-prp.md` (kebutuhan produk).

> Kalau tool Anda memakai konvensi file instruksi tersendiri (mis. `AGENTS.md`), isi dokumen ini bisa disalin ke sana.

---

## 1. Peran AI dalam proyek ini

AI bertindak sebagai **pair-programmer/eksekutor teknis** — menulis kode, migrasi, test, dan dokumentasi berdasarkan `implementation-plan-verifikasi-prp.md` dan `prd-verifikasi-prp.md`. AI **bukan** pengambil keputusan produk/bisnis baru. Perubahan yang menyentuh tujuan, cakupan, atau arsitektur inti harus dikonfirmasi ke pengguna (manusia) dulu — tidak diputuskan sendiri oleh AI, sekalipun menurut AI "lebih baik".

---

## 2. Tujuan & konteks yang wajib selalu diingat

Sebelum menulis kode apa pun, AI harus terus mengacu ke tujuan awal berikut:
- Sistem ini menggantikan Google Form manual untuk pelaporan ketidaksesuaian PRP (Prerequisite Program) di pabrik.
- Ada tiga **kapasitas kerja**, bukan tiga role tetap: Pelapor (melapor), PIC (ditunjuk Pelapor untuk menindaklanjuti temuan tertentu, dinamis per-temuan), dan QA (verifikasi berjenjang + admin master).
- Tujuan inti: kejelasan penanggung jawab sejak laporan dibuat, kecepatan notifikasi (WhatsApp + deep link), verifikasi berjenjang sebelum temuan dianggap selesai, kontrol akses ketat (NIK terdaftar), dan data yang siap dipakai untuk audit.

**Kalau AI ragu apakah suatu fitur/perubahan sejalan dengan tujuan di atas, itu sinyal untuk bertanya dulu — bukan menebak dan melanjutkan.**

---

## 3. Dokumen acuan & urutan prioritas

Kalau ada perbedaan antara instruksi yang diberikan langsung ke AI dalam sesi kerja dengan isi dokumen acuan, urutan prioritasnya:

1. Instruksi eksplisit terbaru dari pengguna (manusia) dalam sesi kerja saat ini
2. `implementation-plan-verifikasi-prp.md`
3. `prd-verifikasi-prp.md`

AI **dilarang mengubah isi dokumen acuan sendiri** hanya karena ada cara lain yang menurutnya lebih mudah diimplementasikan. Kalau menemukan hal yang menurut AI sebaiknya diubah, laporkan sebagai usulan ke pengguna — jangan langsung dieksekusi.

---

## 4. Batasan keras (tidak boleh dilanggar apa pun alasannya)

### 4.1 Stack & arsitektur
- **WAJIB:** Laravel 13, Breeze, Livewire 3, Tailwind CSS, MySQL 8, local filesystem storage, Twilio PHP SDK, barryvdh/laravel-dompdf, maatwebsite/excel.
- **DILARANG** mengganti framework/library inti ini (mis. pindah ke React/Vue terpisah, Inertia, database lain, atau layanan notifikasi selain Twilio) tanpa persetujuan eksplisit pengguna.
- **DILARANG** membangun lapisan REST API terpisah untuk fitur utama — pakai Livewire sesuai arsitektur monolith yang sudah ditentukan.

### 4.2 Role & model akses
- **WAJIB:** hanya 2 role akun — `karyawan` dan `qa`. **DILARANG** menambah nilai role baru (mis. `admin`, `pelapor`, `pic` sebagai kolom role) — PIC dan Pelapor adalah kapasitas dinamis lewat relasi data, bukan role.
- **DILARANG** mengaktifkan pendaftaran akun publik. Akun hanya dibuat oleh `qa` dari NIK yang terdaftar & berstatus aktif di tabel `karyawan`.
- **WAJIB:** login memakai NIK, bukan email.

### 4.3 Model data & alur status
- **WAJIB** mengikuti skema tabel di implementation plan §5 (`karyawan`, `users`, `departemen`, `klausul_prp`, `temuan`, `tindak_lanjut`) — termasuk `pic_id` berada di `temuan`, bukan di `tindak_lanjut`.
- **WAJIB:** status hanya 4 nilai — `open`, `in_progress`, `closed_pending_qa`, `closed_acc` — dengan urutan transisi yang sudah ditentukan. **DILARANG** membuat PIC bisa langsung mengeset status ke `closed_acc` (hanya QA yang boleh).
- **DILARANG** mengubah struktur tabel yang sudah berisi data lewat migration yang bersifat destruktif (drop kolom/tabel, ubah tipe data secara paksa). Perubahan skema harus lewat migration baru yang aditif.

### 4.4 Keamanan & data sensitif
- **WAJIB** menerapkan otorisasi level-objek (Laravel Policy) di setiap akses ke `/temuan/{id}` — bukan hanya middleware role. Hanya `pelapor_id`, `pic_id` dari temuan tersebut, atau role `qa` yang boleh mengakses.
- **DILARANG** menyimpan kredensial (Twilio, DB, `APP_KEY`) di kode atau commit ke repository — wajib lewat `.env`.
- **WAJIB** menerapkan cast `encrypted` pada field yang sudah ditentukan sensitif (`deskripsi`, `catatan_qa`, dan pertimbangkan `no_whatsapp`).
- **DILARANG** mengirim isi lengkap data sensitif (deskripsi temuan, catatan QA) lewat teks pesan WhatsApp — pesan cukup ringkasan singkat + link, detail lengkap hanya terlihat setelah login.
- **WAJIB** memvalidasi tipe & ukuran file upload sebelum disimpan.

### 4.5 Notifikasi
- **WAJIB** setiap notifikasi WhatsApp menyertakan link langsung (`/temuan/{id}`) ke laporan terkait, bukan sekadar teks informasi.
- **WAJIB** pengiriman WhatsApp dijalankan lewat queue (`ShouldQueue`) — tidak boleh sinkron/blocking di request utama.

---

## 5. Batasan cakupan (jangan tambah tanpa izin)

Sesuai PRD §4 (non-goals), AI **dilarang** menambahkan hal berikut ke v1 meski terlihat seperti "peningkatan" wajar, tanpa izin eksplisit dari pengguna terlebih dulu:

- Aplikasi mobile native
- Integrasi ke ERP/QMS pabrik
- E-signature
- Reminder otomatis due date
- Reassignment PIC otomatis
- Channel notifikasi selain WhatsApp (email, SMS, dll.)
- Role atau tabel tambahan yang tidak ada di model data acuan

Kalau AI melihat kebutuhan nyata untuk salah satu di atas selama pengerjaan, catat sebagai usulan di bagian roadmap lanjutan — jangan langsung diimplementasikan.

---

## 6. Standar kerja & kualitas kode

- Ikuti konvensi Laravel standar (PSR-12, penamaan Eloquent model singular-PascalCase, migration & seeder terpisah per tabel).
- Setiap fitur baru disertai test (Pest untuk logic/Policy, Dusk untuk alur UI penting seperti switch tampilan & deep link).
- Commit kecil dan deskriptif per unit kerja, idealnya selaras dengan checklist harian — bukan satu commit besar di akhir.
- Tidak menyisakan kode debug (`dd()`, `dump()`, `console.log`) di kode yang dianggap selesai.
- Tidak menyisakan akun/data dummy berpassword lemah di jalur yang berpotensi ikut ke production.

---

## 7. Alur kerja harian & definition of done

AI mengikuti urutan Hari 1–7 di implementation plan §12 — **tidak melompat** ke scope hari berikutnya sebelum "Output yang harus terlihat di akhir hari" pada hari berjalan tercapai.

Kalau satu hari belum selesai:
- Laporkan status apa adanya (bagian mana yang selesai, mana yang belum).
- Minta arahan lanjutan.
- **Jangan** menandai sebagai selesai kalau sebenarnya belum, dan **jangan** diam-diam menggeser/skip scope ke hari berikutnya tanpa bilang.

---

## 8. Kapan AI wajib berhenti dan bertanya ke manusia

AI berhenti dan meminta konfirmasi — bukan menebak dan melanjutkan sendiri — kalau menemui:

- Instruksi yang tampak bertentangan dengan batasan di bagian 4.
- Kebutuhan/aturan bisnis yang belum dijelaskan di implementation plan atau PRD.
- Keputusan yang berdampak ke keamanan atau data pribadi karyawan (mis. mengubah cara enkripsi, menambah field data pribadi baru).
- Perubahan yang bersifat merusak/destruktif (drop tabel, ubah tipe kolom berisi data, hapus migration lama).
- Deviasi dari stack teknologi yang sudah ditentukan di bagian 4.1.

---

## 9. Checklist sebelum AI mengklaim satu unit kerja "selesai"

- [ ] Sesuai skema data & alur status di implementation plan
- [ ] Role/akses sesuai bagian 4.2 (tidak menambah role baru)
- [ ] Otorisasi level-objek sudah diterapkan di rute yang relevan
- [ ] Tidak ada kredensial hardcoded di kode
- [ ] Field sensitif sudah memakai cast `encrypted` sesuai ketentuan
- [ ] Notifikasi (kalau relevan) menyertakan link & dikirim lewat queue
- [ ] Ada test minimal untuk fitur yang dikerjakan
- [ ] Tidak ada kode debug tertinggal
