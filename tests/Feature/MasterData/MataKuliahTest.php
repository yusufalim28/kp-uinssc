<?php

use App\Livewire\Admin\MasterData\MataKuliahIndex;
use App\Models\JadwalPraktikum;
use App\Models\MataKuliah;
use App\Models\ProgramStudi;
use Database\Seeders\RolePermissionSeeder;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
    loginAsRole('super-admin');
});

test('bisa menambah mata kuliah lengkap dengan sks dan semester', function () {
    $prodi = ProgramStudi::factory()->create();

    Livewire::test(MataKuliahIndex::class)
        ->call('openCreate')
        ->set('program_studi_id', $prodi->id)
        ->set('kode_mk', 'MK101')
        ->set('nama_mk', 'Praktikum Basis Data')
        ->set('sks', 2)
        ->set('semester', 3)
        ->call('save');

    expect(MataKuliah::where('kode_mk', 'MK101')->exists())->toBeTrue();
});

test('kode mk wajib unik', function () {
    MataKuliah::factory()->create(['kode_mk' => 'MK101']);
    $prodi = ProgramStudi::factory()->create();

    Livewire::test(MataKuliahIndex::class)
        ->call('openCreate')
        ->set('program_studi_id', $prodi->id)
        ->set('kode_mk', 'MK101')
        ->set('nama_mk', 'Duplikat')
        ->set('sks', 2)
        ->set('semester', 1)
        ->call('save')
        ->assertHasErrors(['kode_mk']);
});

test('tidak bisa menghapus mata kuliah yang masih dipakai jadwal', function () {
    $mk = MataKuliah::factory()->create();
    JadwalPraktikum::factory()->create(['mata_kuliah_id' => $mk->id]);

    Livewire::test(MataKuliahIndex::class)
        ->call('confirmDelete', $mk->id)
        ->call('delete');

    expect(MataKuliah::find($mk->id))->not->toBeNull();
});
