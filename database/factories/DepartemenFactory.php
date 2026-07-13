<?php

namespace Database\Factories;

use App\Models\Departemen;
use Illuminate\Database\Eloquent\Factories\Factory;

class DepartemenFactory extends Factory
{
    protected $model = Departemen::class;

    public function definition(): array
    {
        return [
            'nama_departemen' => $this->faker->unique()->company() . ' Dept',
        ];
    }
}
