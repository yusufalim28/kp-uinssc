<?php

use App\Livewire\Public\JadwalRuangan;
use App\Models\Dosen;
use App\Models\Fakultas;
use App\Models\JadwalPraktikum;
use App\Models\Kelas;
use App\Models\MataKuliah;
use App\Models\ProgramStudi;
use App\Models\RuangPraktikum;
use App\Models\TahunAkademik;
use Livewire\Livewire;

test('halaman jadwal ruangan publik dapat diakses dengan sukses', function () {
    $this->get(route('jadwal.ruang'))
        ->assertSuccessful()
        ->assertSee('Jadwal Pemakaian Ruang')
        ->assertSee('Format Matriks Resmi');
});

test('menampilkan empty state sebelum memilih ruangan', function () {
    Livewire::test(JadwalRuangan::class)
        ->assertSee('Pilih Laboratorium untuk Menampilkan Jadwal')
        ->assertDontSee('JADWAL PENGGUNAAN');
});

test('menampilkan matriks jadwal ketika ruangan dipilih', function () {
    $ta = TahunAkademik::factory()->create([
        'nama' => '2025/2026',
        'semester' => 'Genap',
        'is_aktif' => true,
    ]);

    $fakultas = Fakultas::factory()->create([
        'kode_fakultas' => 'FUA',
        'nama_fakultas' => 'Fakultas Ushuluddin dan Adab',
    ]);

    $prodi = ProgramStudi::factory()->create([
        'fakultas_id' => $fakultas->id,
        'kode_program_studi' => 'TP',
        'nama_program_studi' => 'Tasawuf dan Psikoterapi',
    ]);

    $ruang = RuangPraktikum::factory()->create([
        'fakultas_id' => $fakultas->id,
        'kode_ruang' => 'LAB-PSIKO',
        'nama_ruang' => 'Laboratorium Psikoterapi',
        'status' => 'aktif',
    ]);

    $dosen = Dosen::factory()->create(['nama' => 'Siti Asiyah, M.Ag']);
    $kelas = Kelas::factory()->create(['nama_kelas' => 'TP/IV/A', 'program_studi_id' => $prodi->id]);
    $mk = MataKuliah::factory()->create(['nama_mk' => 'SQ dan Pengukurannya', 'program_studi_id' => $prodi->id]);

    JadwalPraktikum::factory()->create([
        'mata_kuliah_id' => $mk->id,
        'kelas_id' => $kelas->id,
        'dosen_id' => $dosen->id,
        'ruang_praktikum_id' => $ruang->id,
        'tahun_akademik_id' => $ta->id,
        'hari' => 'Rabu',
        'jam_mulai' => '07:30',
        'jam_selesai' => '09:45',
        'status' => 'aktif',
    ]);

    Livewire::test(JadwalRuangan::class)
        ->set('tahunAkademikId', $ta->id)
        ->call('selectRuang', $ruang->id)
        ->assertSet('hasSearched', true)
        ->assertSee('JADWAL PENGGUNAAN LABORATORIUM PSIKOTERAPI')
        ->assertSee('FAKULTAS USHULUDDIN DAN ADAB')
        ->assertSee('SQ dan Pengukurannya')
        ->assertSee('Siti Asiyah, M.Ag')
        ->assertSee('(TP/IV/A)');
});
