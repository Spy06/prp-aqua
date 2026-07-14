<?php

namespace Database\Seeders;

use App\Models\KlausulPrp;
use Illuminate\Database\Seeder;

class KlausulPrpSeeder extends Seeder
{
    public function run(): void
    {
        $klausuls = [
            ['kode_klausul' => '4', 'nama_klausul' => 'Kontruksi dan tata letak bangunan'],
            ['kode_klausul' => '5', 'nama_klausul' => 'Tata letak tempat dan ruang kerja'],
            ['kode_klausul' => '6', 'nama_klausul' => 'Utilitas — Udara, Air, Energi'],
            ['kode_klausul' => '7', 'nama_klausul' => 'Pembuangan Limbah'],
            ['kode_klausul' => '8', 'nama_klausul' => 'Kesesuaian Peralatan, Pembersihan dan Pemeliharaan'],
            ['kode_klausul' => '9', 'nama_klausul' => 'Manajemen Bahan Baku yang Dibeli'],
            ['kode_klausul' => '10', 'nama_klausul' => 'Langkah-langkah Pencegahan Kontaminasi Silang'],
            ['kode_klausul' => '11', 'nama_klausul' => 'Pembersihan dan Sanitasi'],
            ['kode_klausul' => '12', 'nama_klausul' => 'Pengendalian Hama'],
            ['kode_klausul' => '13', 'nama_klausul' => 'Kebersihan Personel dan Fasilitas Karyawan'],
            ['kode_klausul' => '14', 'nama_klausul' => 'Rework'],
            ['kode_klausul' => '15', 'nama_klausul' => 'Prosedur Penarikan Produk (Recall)'],
            ['kode_klausul' => '16', 'nama_klausul' => 'Storage dan Handling ( Gudang Produk & Material)'],
            ['kode_klausul' => '17', 'nama_klausul' => 'Informasi Produk dan Kesadaran Konsumen'],
            ['kode_klausul' => '18', 'nama_klausul' => 'Food Defence'],
            ['kode_klausul' => '19', 'nama_klausul' => 'Pengendalian Proses, Produk dan Layanan yang Disediakan Secara Eksternal'],
            ['kode_klausul' => '20', 'nama_klausul' => 'Verifikasi PRP'],
        ];

        foreach ($klausuls as $klausul) {
            KlausulPrp::firstOrCreate(
                ['kode_klausul' => $klausul['kode_klausul']],
                ['nama_klausul' => $klausul['nama_klausul']]
            );
        }
    }
}
