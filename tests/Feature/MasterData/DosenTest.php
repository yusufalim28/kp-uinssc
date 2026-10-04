<?php

use App\Livewire\Admin\MasterData\DosenIndex;
use App\Models\Dosen;
use App\Models\JadwalPraktikum;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    loginAsRole('super-admin');
});

test('bisa menambah dosen baru', function () {
    Livewire::test(DosenIndex::class)
        ->call('openCreate')
        ->set('kode_dosen', 'DSN001')
        ->set('nama', 'Budi Santoso')
        ->set('email', 'budi@kampus.ac.id')
        ->call('save');

    expect(Dosen::where('email', 'budi@kampus.ac.id')->exists())->toBeTrue();
});

test('email dosen wajib valid dan unik', function () {
    Dosen::factory()->create(['email' => 'ada@kampus.ac.id']);

    Livewire::test(DosenIndex::class)
        ->call('openCreate')
        ->set('kode_dosen', 'DSN002')
        ->set('nama', 'Dosen Lain')
        ->set('email', 'ada@kampus.ac.id')
        ->call('save')
        ->assertHasErrors(['email']);
});

test('tidak bisa menghapus dosen yang masih punya jadwal', function () {
    $dosen = Dosen::factory()->create();
    JadwalPraktikum::factory()->create(['dosen_id' => $dosen->id]);

    Livewire::test(DosenIndex::class)
        ->call('confirmDelete', $dosen->id)
        ->call('delete');

    expect(Dosen::find($dosen->id))->not->toBeNull();
});
