<?php

namespace Database\Seeders;

use App\Models\BosqElemenQfs;
use App\Models\BosqLine;
use App\Models\BosqSubArea;
use App\Models\Departemen;
use Illuminate\Database\Seeder;

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

        // Departemen IDs lookup
        $mfgDept  = Departemen::where('nama_departemen', 'Manufacturing')->first()?->id;
        $qaDept   = Departemen::where('nama_departemen', 'Quality Assurance')->first()?->id;
        $logDept  = Departemen::where('nama_departemen', 'Logistics')->first()?->id;
        $hrDept   = Departemen::where('nama_departemen', 'Human Resource')->first()?->id;
        $engDept  = Departemen::where('nama_departemen', 'Engineering')->first()?->id;
        $sheDept  = Departemen::where('nama_departemen', 'Safety Health & Environment (SHE)')->first()?->id;

        // 2. Seed Sub Areas with Opsi A (departemen_id)
        $subAreasMapping = [
            // Manufacturing
            ['nama' => 'Filler', 'dept' => $mfgDept],
            ['nama' => 'Spektrum 1', 'dept' => $mfgDept],
            ['nama' => 'Labeller', 'dept' => $mfgDept],
            ['nama' => 'Spektrum 2', 'dept' => $mfgDept],
            ['nama' => 'Versa/Variopack/SMI', 'dept' => $mfgDept],
            ['nama' => 'Weight Checker', 'dept' => $mfgDept],
            ['nama' => 'Palletizer', 'dept' => $mfgDept],
            ['nama' => 'Storage Preform Existing', 'dept' => $mfgDept],
            ['nama' => 'Storage Preform Gede', 'dept' => $mfgDept],
            ['nama' => 'WT Existing', 'dept' => $mfgDept],
            ['nama' => 'WT Gede', 'dept' => $mfgDept],
            ['nama' => 'Husky', 'dept' => $mfgDept],
            ['nama' => 'Ruang IPC', 'dept' => $mfgDept],

            // Engineering / Maintenance
            ['nama' => 'HPU', 'dept' => $engDept],
            ['nama' => 'Sumber 1', 'dept' => $engDept],
            ['nama' => 'Sumber 3', 'dept' => $engDept],
            ['nama' => 'Sumber 4', 'dept' => $engDept],

            // Quality
            ['nama' => 'Lab Fiskim', 'dept' => $qaDept],
            ['nama' => 'Lab Mikro', 'dept' => $qaDept],
            ['nama' => 'Ruang IMC', 'dept' => $qaDept],

            // Logistics
            ['nama' => 'Gudang Produk', 'dept' => $logDept],
            ['nama' => 'Gudang Material', 'dept' => $logDept],
            ['nama' => 'Gudang Chemical', 'dept' => $logDept],
            ['nama' => 'Gudang Limbah', 'dept' => $logDept],
            ['nama' => 'Gudang Afval', 'dept' => $logDept],
            ['nama' => 'Loading unloading', 'dept' => $logDept],

            // HR & SHE / General
            ['nama' => 'Post Security', 'dept' => $hrDept],
            ['nama' => 'Parkiran', 'dept' => $hrDept],
            ['nama' => 'Office', 'dept' => $hrDept],
            ['nama' => 'Others', 'dept' => null],
        ];

        foreach ($subAreasMapping as $item) {
            BosqSubArea::firstOrCreate([
                'nama_sub_area' => $item['nama'],
            ], [
                'departemen_id' => $item['dept'],
            ]);
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
