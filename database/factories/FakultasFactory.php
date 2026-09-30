<?php

namespace Database\Factories;

use App\Models\Fakultas;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Fakultas>
 */
class FakultasFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kode_fakultas' => fake()->unique()->regexify('FAK[0-9]{3}'),
            'nama_fakultas' => fake()->unique()->words(2, true),
        ];
    }
}
