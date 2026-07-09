<?php

namespace Database\Seeders;

use App\Models\Departemen;
use Illuminate\Database\Seeder;

class DepartemenSeeder extends Seeder
{
    public function run(): void
    {
        $departemen = [
            'Produksi',
            'Quality Control (QC)',
            'Gudang',
            'Maintenance',
            'Sanitasi',
        ];

        foreach ($departemen as $nama) {
            Departemen::firstOrCreate(['nama_departemen' => $nama]);
        }
    }
}
