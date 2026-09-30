<?php

namespace Database\Factories;

use App\Models\TahunAkademik;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TahunAkademik>
 */
class TahunAkademikFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $tahunMulai = fake()->numberBetween(2020, 2035);
        $tahunSelesai = $tahunMulai + 1;

        return [
            'nama' => "{$tahunMulai}/{$tahunSelesai}",
            'semester' => fake()->randomElement(['Ganjil', 'Genap']),
            'tahun_mulai' => $tahunMulai,
            'tahun_selesai' => $tahunSelesai,
        ];
    }
}
