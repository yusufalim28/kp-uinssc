<?php

namespace Database\Seeders;

use App\Models\Fakultas;
use App\Models\ProgramStudi;
use App\Models\TahunAkademik;
use Illuminate\Database\Seeder;

class MasterDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $fakultas = Fakultas::create(['kode_fakultas' => 'FTIK', 'nama_fakultas' => 'Fakultas Tarbiyah dan Ilmu Keguruan']);
        $prodi = ProgramStudi::create(['fakultas_id' => $fakultas->id, 'kode_program_studi' => 'IF', 'nama_program_studi' => 'Informatika']);

        TahunAkademik::create([
            'nama' => '2026/2027',
            'semester' => 'Ganjil',
            'tahun_mulai' => 2026,
            'tahun_selesai' => 2027,
            'is_aktif' => true,
        ]);
    }
}
