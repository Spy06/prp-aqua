<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $map = [
            'Manufacturing' => ['SBO Filler Line 1', 'SBO Filler Line 2', 'SBO Filler Lane 3', 'Ergo Line 5', 'End Off Line 1', 'End Off Line 2', 'End Off Line 3', 'End Off Line 5', 'Storage Preform Existing', 'Storage Preform Line 5', 'WT Existing', 'WT Line 5', 'Husky', 'Others'],
            'Logistics' => ['Gudang Material Existing', 'Gudang Material Cimex', 'Gudang Material Line 5', 'Gudang Produk Existing', 'Gudang Produk Cimex', 'Loading Unloading Produk', 'Loading Unloading Material', 'Gudang Kimia', 'Gudang Afval', 'Gudang B3', 'Tangki Solar', 'Sparepart', 'Others'],
            'Quality Assurance' => ['LAB Fiskim', 'LAB Mikro', 'Ruang IPC', 'Ruang IMC', 'Ruang Sample IMC', 'Ruang HPU', 'Others'],
            'Human Resource' => ['POS Security', 'Kantin', 'Lobby', 'Toilet', 'Ruang Meeting', 'Mushola', 'Others'],
            'Engineering' => ['Workshop', 'Kompresor', 'Chiller', 'Soft Water', 'AHU', 'Travo', 'Gardu PLN', 'Ruang Server', 'Others'],
            'Corporate Social Responsibility' => ['Green House']
        ];

        $departemens = \App\Models\Departemen::all();
        
        // Bersihkan tabel sebelum di-seed (menggunakan delete karena truncate kadang hang di SQLite dengan foreign keys)
        \Illuminate\Support\Facades\DB::table('sub_areas')->delete();

        foreach ($departemens as $dept) {
            $namaDept = $dept->nama_departemen;
            
            if (isset($map[$namaDept])) {
                $subAreas = $map[$namaDept];
                foreach ($subAreas as $subAreaName) {
                    \App\Models\SubArea::create([
                        'departemen_id' => $dept->id,
                        'nama_sub_area' => $subAreaName,
                    ]);
                }
            } else {
                // Default if not specified (e.g. for SHE)
                \App\Models\SubArea::create([
                    'departemen_id' => $dept->id,
                    'nama_sub_area' => 'Others',
                ]);
            }
        }
    }
}
