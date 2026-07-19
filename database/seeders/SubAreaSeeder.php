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
        $departemens = \App\Models\Departemen::all();
        
        foreach ($departemens as $dept) {
            for ($i = 1; $i <= 6; $i++) {
                \App\Models\SubArea::create([
                    'departemen_id' => $dept->id,
                    'nama_sub_area' => "Sub Area {$dept->nama_departemen} $i",
                ]);
            }
        }
    }
}
