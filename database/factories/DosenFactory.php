<?php

namespace Database\Factories;

use App\Models\Dosen;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Dosen>
 */
class DosenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kode_dosen' => strtoupper(fake()->unique()->bothify('DSN###')),
            'nama'       => fake()->name(),
            'email'      => fake()->unique()->safeEmail(),
            'status'     => 'aktif',
            'user_id'    => null

            // 'nama' => fake()->name(),
            // 'nidn' => fake()->unique()->regexify('[0-9]{10}'),
            // 'email' => fake()->unique()->safeEmail(),
        ];
    }
}
