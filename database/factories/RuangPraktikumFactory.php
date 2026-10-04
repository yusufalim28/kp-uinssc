<?php

namespace Database\Factories;

use App\Models\RuangPraktikum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<RuangPraktikum>
 */
class RuangPraktikumFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kode_ruang' => strtoupper(fake()->unique()->bothify('RG###')),
            'nama_ruang' => 'Lab ' . fake()->unique()->word(),
            'lokasi'     => 'Gedung ' . fake()->randomLetter() . ' Lantai ' . fake()->numberBetween(1, 4),
            'kapasitas'  => fake()->numberBetween(20, 60),
            'status'     => 'aktif',


            // 'kode_ruang' => fake()->unique()->regexify('[A-Z]{2}[0-9]{3}'),
            // 'nama_ruang' => fake()->unique()->word(),
            // 'lokasi' => fake()->city(),
            // 'kapasitas' => fake()->numberBetween(10, 100),
        ];
    }
}
