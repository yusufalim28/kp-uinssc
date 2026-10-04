<?php

use App\Livewire\Admin\MasterData\ProgramStudiIndex;
use App\Models\Fakultas;
use App\Models\Kelas;
use App\Models\ProgramStudi;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(\Database\Seeders\RolePermissionSeeder::class);
    loginAsRole('super-admin');
});

test('bisa menambah program studi dengan fakultas induk', function () {
    $fakultas = Fakultas::factory()->create();

    Livewire::test(ProgramStudiIndex::class)
        ->call('openCreate')
        ->set('fakultas_id', $fakultas->id)
        ->set('nama_program_studi', 'Teknik Informatika')
        ->set('kode_program_studi', 'TI')
        ->call('save');

    expect(ProgramStudi::where('kode_program_studi', 'TI')->exists())->toBeTrue();
});

test('fakultas_id wajib dipilih', function () {
    Livewire::test(ProgramStudiIndex::class)
        ->call('openCreate')
        ->set('nama_program_studi', 'Teknik Informatika')
        ->set('kode_program_studi', 'TI')
        ->call('save')
        ->assertHasErrors(['fakultas_id']);
});

test('tidak bisa menghapus program studi yang masih punya kelas', function () {
    $prodi = ProgramStudi::factory()->create();
    Kelas::factory()->create(['program_studi_id' => $prodi->id]);

    Livewire::test(ProgramStudiIndex::class)
        ->call('confirmDelete', $prodi->id)
        ->call('delete');

    expect(ProgramStudi::find($prodi->id))->not->toBeNull();
});
