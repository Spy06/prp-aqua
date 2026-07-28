<?php

namespace Database\Seeders;

use App\Models\Departemen;
use App\Models\SubArea;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubAreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $map = [
            'Manufacturing' => [
                'SBO Filler Line 1',
                'SBO Filler Line 2',
                'SBO Filler Line 3',
                'Ergo Line 5',
                'End Off Line 1',
                'End Off Line 2',
                'End Off Line 3',
                'End Off Line 5',
                'Storage Preform Existing',
                'Storage Preform Line 5',
                'WT Existing',
                'WT Line 5',
                'Husky',
                'Others',
            ],
            'LOGISTIK' => [
                'Gudang Material Existing',
                'Gudang Material Cimex',
                'Gudang Material Line 5',
                'Gudang Produk Existing',
                'Gudang Produk Cimex',
                'Loading Unloading Produk',
                'Loading Unloading Material',
                'Gudang Kimia',
                'Gudang Afval',
                'Gudang B3',
                'Tangki Solar',
                'Sparepart',
                'Others',
            ],
            'QUALITY' => [
                'LAB Fiskim',
                'LAB Mikro',
                'Ruang IPC',
                'Ruang IMC',
                'Ruang Sample IMC',
                'Ruang HPU',
                'Others',
            ],
            'HR' => [
                'POS Security',
                'Kantin',
                'Lobby',
                'Toilet',
                'Ruang Meeting',
                'Mushola',
                'Others',
            ],
            'ENGINEERING' => [
                'Workshop',
                'Kompresor',
                'Chiller',
                'Soft Water',
                'AHU',
                'Travo',
                'Gardu PLN',
                'Others',
            ],
            'CSR' => [
                'Green House',
                'Others',
            ],
            'PERFORMANCE' => [
                'Others',
            ],
            'FINANCE' => [
                'Others',
            ],
        ];

        DB::table('sub_areas')->delete();

        $departemens = Departemen::all();

        foreach ($departemens as $dept) {
            $namaDept = $dept->nama_departemen;

            if (isset($map[$namaDept])) {
                foreach ($map[$namaDept] as $subAreaName) {
                    SubArea::create([
                        'departemen_id' => $dept->id,
                        'nama_sub_area' => $subAreaName,
                    ]);
                }
            } else {
                SubArea::create([
                    'departemen_id' => $dept->id,
                    'nama_sub_area' => 'Others',
                ]);
            }
        }
    }
}
