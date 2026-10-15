<?php

use App\Livewire\Admin\MasterData\KelasIndex;
use App\Models\JadwalPraktikum;
use App\Models\Kelas;
use App\Models\ProgramStudi;
use Database\Seeders\RolePermissionSeeder;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
    loginAsRole('super-admin');
});

test('bisa menambah kelas dengan semester valid', function () {
    $prodi = ProgramStudi::factory()->create();

    Livewire::test(KelasIndex::class)
        ->call('openCreate')
        ->set('program_studi_id', $prodi->id)
        ->set('nama_kelas', 'TI-3A')
        ->set('semester', 3)
        ->call('save');

    expect(Kelas::where('nama_kelas', 'TI-3A')->exists())->toBeTrue();
});

test('semester di luar 1-8 ditolak', function () {
    $prodi = ProgramStudi::factory()->create();

    Livewire::test(KelasIndex::class)
        ->call('openCreate')
        ->set('program_studi_id', $prodi->id)
        ->set('nama_kelas', 'TI-9Z')
        ->set('semester', 12)
        ->call('save')
        ->assertHasErrors(['semester']);
});

test('tidak bisa menghapus kelas yang masih dipakai jadwal', function () {
    $kelas = Kelas::factory()->create();
    JadwalPraktikum::factory()->create(['kelas_id' => $kelas->id]);

    Livewire::test(KelasIndex::class)
        ->call('confirmDelete', $kelas->id)
        ->call('delete');

    expect(Kelas::find($kelas->id))->not->toBeNull();
});
