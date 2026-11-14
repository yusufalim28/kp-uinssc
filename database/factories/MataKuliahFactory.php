<?php

namespace Database\Factories;

use App\Models\Fakultas;
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
            'cakupan_mk' => 'prodi',
            'fakultas_id' => null,
            'program_studi_id' => ProgramStudi::factory(),
            'kode_mk' => strtoupper(fake()->unique()->bothify('MK###')),
            'nama_mk' => 'Praktikum '.fake()->unique()->word(),
            'sks' => fake()->numberBetween(1, 4),
            'semester' => fake()->numberBetween(1, 8),
            'status' => 'aktif',
        ];
    }

    public function bersamaFakultas(): static
    {
        return $this->state(fn (array $attributes) => [
            'cakupan_mk' => 'bersama_fakultas',
            'program_studi_id' => null,
            'fakultas_id' => Fakultas::factory(),
        ]);
    }
}
