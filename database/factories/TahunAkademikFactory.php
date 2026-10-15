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
        $tahunMulai = fake()->numberBetween(2020, 2045);
        $tahunSelesai = $tahunMulai + 1;
        $semester = fake()->randomElement(['Ganjil', 'Genap']);

        return [
            'nama' => "{$tahunMulai}/{$tahunSelesai} {$semester} ".fake()->unique()->numerify('###'),
            'semester' => $semester,
            'tahun_mulai' => $tahunMulai,
            'tahun_selesai' => $tahunSelesai,
            'is_aktif' => false,
        ];
    }
}
