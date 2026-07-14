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
            'Engineering',
            'Logistics',
            'Human Resource',
            'Quality Assurance',
            'Safety Health & Environment (SHE)',
            'Corporate Social Responsibility'
        ];

        foreach ($departemen as $nama) {
            Departemen::firstOrCreate(['nama_departemen' => $nama]);
        }
    }
}
