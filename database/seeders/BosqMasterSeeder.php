<?php

namespace Database\Seeders;

use App\Models\BosqElemenQfs;
use App\Models\BosqLine;
use App\Models\BosqSubArea;
use App\Models\Departemen;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BosqMasterSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Lines
        $lines = [
            'Line 1',
            'Line 2',
            'Line 3',
            'Line 5',
            'Husky',
            'Quality',
            'Logistik',
            'HR',
            'Engineering',
            'Others Manufacturing',
        ];

        foreach ($lines as $line) {
            BosqLine::firstOrCreate([
                'nama_line' => $line,
            ], [
                'default_auditee_id' => null,
            ]);
        }

        // 2. Seed Sub Areas matching exact Departemen & SubArea spec
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

        DB::table('bosq_sub_area')->delete();

        $departemens = Departemen::all();

        foreach ($departemens as $dept) {
            $namaDept = $dept->nama_departemen;

            if (isset($map[$namaDept])) {
                foreach ($map[$namaDept] as $subAreaName) {
                    BosqSubArea::create([
                        'departemen_id' => $dept->id,
                        'nama_sub_area' => $subAreaName,
                    ]);
                }
            } else {
                BosqSubArea::create([
                    'departemen_id' => $dept->id,
                    'nama_sub_area' => 'Others',
                ]);
            }
        }

        // 3. Seed Elemen QFS
        $elemenList = [
            'Standar Produk',
            'Standar Material',
            'Standar Proses',
            'Hygiene Personil',
            'Hygiene Ruangan',
            'Cleaning Sanitasi',
            'Preventive Maintenance Related Quality',
            'GLP (Good Laboratory Practice)',
            'Pest Control',
            'Food Defence',
            'Traceability',
            'Others',
        ];

        foreach ($elemenList as $elemen) {
            BosqElemenQfs::firstOrCreate([
                'nama_elemen' => $elemen,
            ]);
        }
    }
}
