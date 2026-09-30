<?php

namespace Database\Factories;

use App\Models\MataKuliah;
use App\Models\ProgramStudi;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MataKuliah>
 */
class MataKuliahFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'program_studi_id' => ProgramStudi::factory(),
            'kode_mk' => fake()->unique()->regexify('[A-Z]{2}[0-9]{3}'),
            'nama_mk' => fake()->unique()->words(3, true),
            'sks' => fake()->numberBetween(1, 6),
            'semester' => fake()->numberBetween(1, 8),
        ];
    }
}
