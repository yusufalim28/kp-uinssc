<?php

namespace Database\Factories;

use App\Models\Fakultas;
use App\Models\PenanggungJawabLab;
use App\Models\RuangPraktikum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<PenanggungJawabLab>
 */
class PenanggungJawabLabFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'fakultas_id' => Fakultas::factory(),
            'ruang_praktikum_id' => RuangPraktikum::factory(),
            'nama' => fake()->name(),
            'nip_nidn' => fake()->numerify('198#########0110##'),
            'jabatan' => fake()->randomElement(['Ketua Laboratorium', 'Pranata Laboratorium', 'Teknisi Lab', 'Asisten Laboratorium']),
            'tingkat' => 'anggota',
            'urutan' => fake()->numberBetween(1, 10),
            'foto' => null,
            'email' => fake()->safeEmail(),
            'no_telepon' => '08'.fake()->numerify('##########'),
            'link_instagram' => null,
            'link_linkedin' => null,
            'status' => 'aktif',
        ];
    }

    public function pimpinan(): static
    {
        return $this->state(fn () => [
            'jabatan' => 'Ketua Laboratorium',
            'tingkat' => 'pimpinan',
            'urutan' => 1,
        ]);
    }
}
