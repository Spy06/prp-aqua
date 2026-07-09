<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     * Urutan penting: departemen & klausul harus ada sebelum karyawan & users.
     */
    public function run(): void
    {
        $this->call([
            DepartemenSeeder::class,
            KlausulPrpSeeder::class,
            KaryawanUserSeeder::class,
        ]);
    }
}
