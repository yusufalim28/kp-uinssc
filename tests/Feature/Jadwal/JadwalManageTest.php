<?php

use App\Livewire\Admin\JadwalManage;
use App\Models\Dosen;
use App\Models\JadwalPraktikum;
use App\Models\Kelas;
use App\Models\MataKuliah;
use App\Models\RuangPraktikum;
use App\Models\TahunAkademik;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    loginAsRole('operator');

    $this->mk     = MataKuliah::factory()->create();
    $this->kelas  = Kelas::factory()->create();
    $this->dosen  = Dosen::factory()->create();
    $this->ruang  = RuangPraktikum::factory()->create();
    $this->tahun  = TahunAkademik::factory()->create();
});

function isiFormJadwal($component, array $override = [])
{
    $data = array_merge([
        'mata_kuliah_id'     => test()->mk->id,
        'kelas_id'           => test()->kelas->id,
        'dosen_id'           => test()->dosen->id,
        'ruang_praktikum_id' => test()->ruang->id,
        'tahun_akademik_id'  => test()->tahun->id,
        'hari'               => 'Senin',
        'jam_mulai'          => '08:00',
        'jam_selesai'        => '10:00',
        'status'             => 'aktif',
    ], $override);

    foreach ($data as $key => $value) {
        $component->set($key, $value);
    }

    return $component;
}

test('bisa membuat jadwal baru tanpa bentrok', function () {
    $component = Livewire::test(JadwalManage::class)->call('openCreate');

    isiFormJadwal($component)->call('save');

    expect(JadwalPraktikum::count())->toBe(1);
    $component->assertSet('conflictMessage', null);
});

test('jadwal bentrok ruang ditolak dan pesan error tampil', function () {
    JadwalPraktikum::factory()->create([
        'ruang_praktikum_id' => $this->ruang->id,
        'tahun_akademik_id'  => $this->tahun->id,
        'hari'               => 'Senin',
        'jam_mulai'          => '08:00',
        'jam_selesai'        => '10:00',
    ]);

    $component = Livewire::test(JadwalManage::class)->call('openCreate');

    isiFormJadwal($component, ['jam_mulai' => '09:00', 'jam_selesai' => '11:00'])
        ->call('save');

    expect(JadwalPraktikum::count())->toBe(1); // tetap 1, yang baru gagal disimpan
    $component->assertSet('conflictMessage', fn ($msg) => ! empty($msg));
});

test('mengedit jadwal tanpa mengubah jam tidak dianggap bentrok dengan dirinya sendiri', function () {
    $jadwal = JadwalPraktikum::factory()->create([
        'ruang_praktikum_id' => $this->ruang->id,
        'tahun_akademik_id'  => $this->tahun->id,
        'hari'               => 'Senin',
        'jam_mulai'          => '08:00',
        'jam_selesai'        => '10:00',
        'keterangan'         => null,
    ]);

    $component = Livewire::test(JadwalManage::class)->call('openEdit', $jadwal->id);

    $component->set('keterangan', 'Ruang pindah sementara')->call('save');

    expect($jadwal->fresh()->keterangan)->toBe('Ruang pindah sementara');
    $component->assertSet('conflictMessage', null);
});

test('tidak bisa menghapus jadwal yang masih punya riwayat pengajuan perubahan', function () {
    $jadwal = JadwalPraktikum::factory()->create();
    \App\Models\PengajuanPerubahan::create([
        'jadwal_praktikum_id' => $jadwal->id,
        'dosen_id'            => $this->dosen->id,
        'perubahan'           => 'Pindah jam',
        'alasan'              => 'Ada rapat',
        'status'              => 'menunggu',
    ]);

    Livewire::test(JadwalManage::class)
        ->call('confirmDelete', $jadwal->id)
        ->call('delete');

    expect(JadwalPraktikum::find($jadwal->id))->not->toBeNull();
});
