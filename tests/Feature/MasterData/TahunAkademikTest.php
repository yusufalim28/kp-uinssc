<?php

use App\Livewire\Admin\MasterData\TahunAkademikIndex;
use App\Models\JadwalPraktikum;
use App\Models\TahunAkademik;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    loginAsRole('super-admin');
});

test('bisa menambah tahun akademik baru', function () {
    Livewire::test(TahunAkademikIndex::class)
        ->call('openCreate')
        ->set('nama', '2026/2027 Ganjil')
        ->set('tahun_mulai', 2026)
        ->set('status_aktif', false)
        ->call('save');

    expect(TahunAkademik::where('nama', '2026/2027 Ganjil')->exists())->toBeTrue();
});

test('mengaktifkan satu tahun akademik otomatis menonaktifkan tahun lain', function () {
    $tahunLama = TahunAkademik::factory()->create(['status_aktif' => true]);

    Livewire::test(TahunAkademikIndex::class)
        ->call('openCreate')
        ->set('nama', '2027/2028 Ganjil')
        ->set('tahun_mulai', 2027)
        ->set('status_aktif', true)
        ->call('save');

    expect($tahunLama->fresh()->status_aktif)->toBeFalse();
    expect(TahunAkademik::where('nama', '2027/2028 Ganjil')->first()->status_aktif)->toBeTrue();
});

test('tidak bisa menghapus tahun akademik yang masih dipakai jadwal', function () {
    $tahun = TahunAkademik::factory()->create();
    JadwalPraktikum::factory()->create(['tahun_akademik_id' => $tahun->id]);

    Livewire::test(TahunAkademikIndex::class)
        ->call('confirmDelete', $tahun->id)
        ->call('delete');

    expect(TahunAkademik::find($tahun->id))->not->toBeNull();
});
