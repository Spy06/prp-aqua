<?php

namespace Database\Seeders;

use App\Models\Departemen;
use Illuminate\Database\Seeder;

class DepartemenSeeder extends Seeder
{
    public function run(): void
    {
        $departemen = [
            'Manufacturing',
            'LOGISTIK',
            'QUALITY',
            'HR',
            'ENGINEERING',
            'CSR',
            'PERFORMANCE',
            'FINANCE',
            'Safety Health & Environment (SHE)',
        ];

        foreach ($departemen as $nama) {
            Departemen::firstOrCreate(['nama_departemen' => $nama]);
        }
    }
}
