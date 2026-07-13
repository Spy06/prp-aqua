<?php

namespace Database\Factories;

use App\Models\Karyawan;
use App\Models\Departemen;
use Illuminate\Database\Eloquent\Factories\Factory;

class KaryawanFactory extends Factory
{
    protected $model = Karyawan::class;

    public function definition(): array
    {
        return [
            'nik' => 'K' . $this->faker->unique()->numerify('######'),
            'nama' => $this->faker->name(),
            'departemen_id' => Departemen::factory(),
            'status_aktif' => true,
        ];
    }
}
