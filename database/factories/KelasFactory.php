<?php

namespace Database\Factories;

use App\Models\Kelas;
use App\Models\ProgramStudi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Kelas>
 */
class KelasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_kelas' => fake()->unique()->regexify('[1-8][A-Z]'),
            'semester' => fake()->numberBetween(1, 8),
            'program_studi_id' => ProgramStudi::factory(),
        ];
    }
}
