<?php

use App\Models\Dosen;
use App\Models\JadwalPraktikum;
use App\Models\Kelas;
use App\Models\MataKuliah;
use App\Models\ProgramStudi;
use App\Models\RuangPraktikum;
use App\Models\TahunAkademik;
use App\Services\JadwalConflictService;

beforeEach(function () {
    $this->service = new JadwalConflictService;

    $this->ruang = RuangPraktikum::factory()->create();
    $this->dosen = Dosen::factory()->create();
    $this->tahun = TahunAkademik::factory()->create();
    $prodi = ProgramStudi::factory()->create();
    $this->mk = MataKuliah::factory()->create(['program_studi_id' => $prodi->id]);
    $this->kelas = Kelas::factory()->create(['program_studi_id' => $prodi->id]);
});

function buatJadwal(array $override = []): JadwalPraktikum
{
    return JadwalPraktikum::create(array_merge([
        'mata_kuliah_id' => test()->mk->id,
        'kelas_id' => test()->kelas->id,
        'dosen_id' => test()->dosen->id,
        'ruang_praktikum_id' => test()->ruang->id,
        'tahun_akademik_id' => test()->tahun->id,
        'hari' => 'Senin',
        'jam_mulai' => '08:00',
        'jam_selesai' => '10:00',
        'status' => 'aktif',
    ], $override));
}

test('ruang bentrok ketika jam saling overlap', function () {
    buatJadwal(['jam_mulai' => '08:00', 'jam_selesai' => '10:00']);

    $hasil = $this->service->cekBentrokRuang(
        $this->ruang->id, 'Senin', '09:00', '11:00', $this->tahun->id
    );

    expect($hasil)->not->toBeNull();
});

test('ruang tidak bentrok ketika jam persis bersambung', function () {
    buatJadwal(['jam_mulai' => '08:00', 'jam_selesai' => '10:00']);

    $hasil = $this->service->cekBentrokRuang(
        $this->ruang->id, 'Senin', '10:00', '12:00', $this->tahun->id
    );

    expect($hasil)->toBeNull();
});

test('dosen bentrok walau ruang berbeda', function () {
    buatJadwal(['jam_mulai' => '08:00', 'jam_selesai' => '10:00']);

    $ruangLain = RuangPraktikum::factory()->create();

    $hasil = $this->service->cekBentrokDosen(
        $this->dosen->id, 'Senin', '09:00', '10:30', $this->tahun->id
    );

    expect($hasil)->not->toBeNull();
});

test('jadwal yang sedang diedit tidak dianggap bentrok dengan dirinya sendiri', function () {
    $jadwal = buatJadwal(['jam_mulai' => '08:00', 'jam_selesai' => '10:00']);

    $hasil = $this->service->cekBentrokRuang(
        $this->ruang->id, 'Senin', '08:00', '10:00', $this->tahun->id,
        excludeId: $jadwal->id
    );

    expect($hasil)->toBeNull();
});
