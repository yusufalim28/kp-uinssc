<?php

use App\Livewire\Admin\MasterData\RuangPraktikumIndex;
use App\Models\JadwalPraktikum;
use App\Models\RuangPraktikum;
use Database\Seeders\RolePermissionSeeder;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
    loginAsRole('super-admin');
});

test('bisa menambah ruang praktikum baru', function () {
    Livewire::test(RuangPraktikumIndex::class)
        ->call('openCreate')
        ->set('kode_ruang', 'RG101')
        ->set('nama_ruang', 'Lab Jaringan')
        ->set('lokasi', 'Gedung A Lantai 2')
        ->set('kapasitas', 30)
        ->call('save');

    expect(RuangPraktikum::where('kode_ruang', 'RG101')->exists())->toBeTrue();
});

test('kapasitas di luar batas ditolak', function () {
    Livewire::test(RuangPraktikumIndex::class)
        ->call('openCreate')
        ->set('kode_ruang', 'RG102')
        ->set('nama_ruang', 'Lab Besar')
        ->set('lokasi', 'Gedung B')
        ->set('kapasitas', 1500)
        ->call('save')
        ->assertHasErrors(['kapasitas']);
});

test('tidak bisa menghapus ruang yang masih dipakai jadwal', function () {
    $ruang = RuangPraktikum::factory()->create();
    JadwalPraktikum::factory()->create(['ruang_praktikum_id' => $ruang->id]);

    Livewire::test(RuangPraktikumIndex::class)
        ->call('confirmDelete', $ruang->id)
        ->call('delete');

    expect(RuangPraktikum::find($ruang->id))->not->toBeNull();
});
