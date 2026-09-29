<?php

namespace Tests\Feature;

use App\Models\Dosen;
use App\Models\Fakultas;
use App\Models\JadwalPraktikum;
use App\Models\Kelas;
use App\Models\MataKuliah;
use App\Models\ProgramStudi;
use App\Models\RuangPraktikum;
use App\Models\TahunAkademik;
use App\Services\JadwalConflictService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class JadwalConflictTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        // Memastikan SQLite mengaktifkan aturan Foreign Key
        DB::statement('PRAGMA foreign_keys=ON;');
    }

    public function test_deteksi_bentrok_ruang_praktikum()
    {
        // 1. Buat data induk (Tanpa FK)
        $fakultas = Fakultas::create(['kode_fakultas' => 'FTIK', 'nama_fakultas' => 'Fakultas Teknik', 'status' => 'aktif']);
        $dosen = Dosen::create(['nidn' => '123456', 'nama' => 'Dosen A', 'email' => 'dosen@uinssc.ac.id', 'status' => 'aktif']);
        $ruang = RuangPraktikum::create(['kode_ruang' => 'LAB-01', 'nama_ruang' => 'Ruang A', 'lokasi' => 'Gedung TIK', 'kapasitas' => 30, 'status' => 'tersedia']);
        $tahun = TahunAkademik::create([
            'nama' => '2026/2027',
            'semester' => 'Ganjil',
            'tahun_mulai' => 2026,
            'tahun_selesai' => 2027,
            'is_aktif' => true,
        ]);

        // 2. Buat data turunan level 1 (FK ke induk)
        $prodi = ProgramStudi::create(['fakultas_id' => $fakultas->id, 'kode_program_studi' => 'IF', 'nama_program_studi' => 'Informatika', 'status' => 'aktif']);

        // 3. Buat data turunan level 2 (FK ke prodi)
        $kelas = Kelas::create(['program_studi_id' => $prodi->id, 'nama_kelas' => '7A', 'semester' => 7, 'status' => 'aktif']);
        $mk = MataKuliah::create(['program_studi_id' => $prodi->id, 'kode_mk' => 'IF101', 'nama_mk' => 'Pemrograman Web', 'sks' => 3, 'semester' => 7, 'status' => 'aktif']);

        // 4. Buat jadwal existing (Semua ID pasti valid karena diambil dari objek di atas)
        $jadwal = JadwalPraktikum::create([
            'mata_kuliah_id' => $mk->id,
            'kelas_id' => $kelas->id,
            'dosen_id' => $dosen->id,
            'ruang_praktikum_id' => $ruang->id,
            'tahun_akademik_id' => $tahun->id,
            'hari' => 'Senin',
            'jam_mulai' => '08:00:00',
            'jam_selesai' => '10:00:00',
            'status' => 'aktif',
        ]);

        $service = new JadwalConflictService;

        // Uji Skenario Bentrok
        $isBentrok = $service->cekBentrokRuang($ruang->id, 'Senin', '09:00:00', '11:00:00', $tahun->id);
        $this->assertTrue($isBentrok);

        // Uji Skenario Tidak Bentrok
        $isAman = $service->cekBentrokRuang($ruang->id, 'Senin', '10:00:00', '12:00:00', $tahun->id);
        $this->assertFalse($isAman);

        $jadwal->update(['status' => 'dibatalkan']);
        $isDibatalkan = $service->cekBentrokRuang($ruang->id, 'Senin', '09:00:00', '11:00:00', $tahun->id);
        $this->assertFalse($isDibatalkan);
    }
}
