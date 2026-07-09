<?php

namespace Database\Seeders;

use App\Models\KlausulPrp;
use Illuminate\Database\Seeder;

class KlausulPrpSeeder extends Seeder
{
    public function run(): void
    {
        $klausuls = [
            ['kode_klausul' => 'PRP-01', 'nama_klausul' => 'Higiene Karyawan'],
            ['kode_klausul' => 'PRP-02', 'nama_klausul' => 'Pengendalian Hama'],
            ['kode_klausul' => 'PRP-03', 'nama_klausul' => 'Kalibrasi Alat'],
            ['kode_klausul' => 'PRP-04', 'nama_klausul' => 'Kebersihan Fasilitas'],
            ['kode_klausul' => 'PRP-05', 'nama_klausul' => 'Penyimpanan & Penanganan Bahan'],
            ['kode_klausul' => 'PRP-06', 'nama_klausul' => 'Kualitas Air & Es'],
            ['kode_klausul' => 'PRP-07', 'nama_klausul' => 'Pengendalian Alergen'],
            ['kode_klausul' => 'PRP-08', 'nama_klausul' => 'Pembuangan Limbah'],
        ];

        foreach ($klausuls as $klausul) {
            KlausulPrp::firstOrCreate(
                ['kode_klausul' => $klausul['kode_klausul']],
                ['nama_klausul' => $klausul['nama_klausul']]
            );
        }
    }
}
