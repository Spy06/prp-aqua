# PRP Verification System - Design System

## Project Context
Aplikasi ini adalah "Sistem Verifikasi PRP (Pre-Requisite Program) Plant", digunakan oleh karyawan pabrik (Pelapor), PIC (Person In Charge), dan tim QA (Quality Assurance) untuk melaporkan, menindaklanjuti, dan memverifikasi temuan ketidaksesuaian standar kebersihan/kualitas di area pabrik.

## Design Principles
- **Minimalist & Clean**: Desain harus menghindari elemen visual yang tidak perlu. Fokus pada data dan tindakan.
- **Accessible & Legible**: Kontras warna yang tinggi, ukuran font yang mudah dibaca, dan ruang kosong (whitespace) yang cukup.
- **Intuitive UI/UX**: Navigasi yang jelas, status yang mudah dikenali dalam pandangan pertama, dan form pelaporan yang tidak mengintimidasi.

## Typography
- **Primary Font**: `Inter` atau `Roboto` untuk keterbacaan optimal di layar digital.
- **Hierarchy**:
  - Heading 1 (Title): 24px, Bold (zinc-900 / zinc-100 dark)
  - Heading 2 (Section): 18px, SemiBold
  - Body Text: 14px, Regular (zinc-700 / zinc-300 dark)
  - Caption/Hint: 12px, Regular (zinc-500)

## Color Palette (Aqua/Clean Theme)
- **Primary**: Indigo/Blue (`#4F46E5` / Indigo-600) untuk aksi utama (Tombol Submit, Tab aktif).
- **Surface/Background**: 
  - Light Mode: Putih (`#FFFFFF`) untuk card, abu-abu sangat terang (`#F9FAFB` / Gray-50) untuk background halaman.
  - Dark Mode: Zinc-900 (`#18181B`) untuk background, Zinc-800 (`#27272A`) untuk card.
- **Status Colors (Crucial for UI)**:
  - **Open**: Kuning/Orange (Yellow-500) - Menandakan butuh tindakan segera.
  - **In Progress**: Biru (Blue-500) - Sedang dikerjakan.
  - **Pending QA**: Ungu (Purple-500) - Menunggu verifikasi tim mutu.
  - **Closed (ACC)**: Hijau (Green-500) - Selesai dan disetujui.

## Layout & Components

### 1. Tab Navigation (Switch Tampilan)
- Desain *pill-shaped* atau *underline* tab yang minimalis untuk berpindah antara mode "Pelapor" dan "PIC".
- Transisi warna yang halus saat di-hover atau aktif.

### 2. Form Pelaporan (Form Temuan)
- **Input Fields**: Border tipis yang halus (Zinc-300), sudut membulat (Rounded-lg). Saat di-klik (focus), berikan *ring* warna Primary.
- **Searchable Dropdown (PIC)**: Desain *pop-over* yang melayang (floating) dengan bayangan halus (Shadow-md) untuk hasil pencarian nama karyawan.
- **File Upload Area**: Area *drag-and-drop* atau tombol unggah dengan ikon kamera/dokumen yang ramah pengguna.
- **Call to Action (CTA)**: Tombol "Kirim Laporan" dengan warna solid Primary di kanan bawah form.

### 3. Data Tables & Lists (Daftar Temuan)
- Gunakan list berbasis Card (bukan tabel kaku) jika memungkinkan di tampilan mobile, atau tabel dengan garis pembagi horisontal yang sangat tipis untuk desktop.
- **Status Badges**: Wajib menggunakan bentuk kapsul (rounded-full) dengan warna latar transparan/pudar (bg-opacity-10) dan teks warna pekat. Contoh: `bg-green-100 text-green-800`.
- **Aksi**: Link "Lihat Detail" yang bersih tanpa *underline* kecuali di-hover.

## User Flow to Consider for Generation
1. **Empty State**: Tampilan ketika belum ada laporan (ilustrasi minimalis berwarna abu-abu).
2. **Form Interaction**: Tampilan saat *user* sedang mengetik pencarian PIC.
3. **Data Populated**: Tampilan Daftar Temuan ketika sudah ada 3-4 data dengan status yang berbeda-beda.
