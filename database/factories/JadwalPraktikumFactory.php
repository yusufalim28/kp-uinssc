<?php

namespace Database\Factories;

use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\MataKuliah;
use App\Models\RuangPraktikum;
use App\Models\TahunAkademik;
use Illuminate\Database\Eloquent\Factories\Factory;

class JadwalPraktikumFactory extends Factory
{
    public function definition(): array
    {
        return [
            'mata_kuliah_id' => MataKuliah::factory(),
            'kelas_id' => Kelas::factory(),
            'dosen_id' => Dosen::factory(),
            'ruang_praktikum_id' => RuangPraktikum::factory(),
            'tahun_akademik_id' => TahunAkademik::factory(),
            'hari' => fake()->randomElement(['Senin', 'Selasa', 'Rabu', 'Kamis', "Jum'at", 'Sabtu']),
            'jam_mulai' => '08:00',
            'jam_selesai' => '10:00',
            'status' => 'aktif',
            'keterangan' => null,
        ];
    }
}
