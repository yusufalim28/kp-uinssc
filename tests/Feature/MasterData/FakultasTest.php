<?php

use App\Livewire\Admin\MasterData\FakultasIndex;
use App\Models\Fakultas;
use App\Models\ProgramStudi;
use Database\Seeders\RolePermissionSeeder;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
    loginAsRole('super-admin');
});

test('halaman menampilkan daftar fakultas', function () {
    Fakultas::factory()->create(['nama_fakultas' => 'Fakultas Teknik']);

    Livewire::test(FakultasIndex::class)
        ->assertSee('Fakultas Teknik');
});

test('bisa menambah fakultas baru', function () {
    Livewire::test(FakultasIndex::class)
        ->call('openCreate')
        ->set('nama_fakultas', 'Fakultas Ilmu Komputer')
        ->set('kode_fakultas', 'FIK')
        ->set('status', 'aktif')
        ->call('save')
        ->assertSet('showModal', false);

    expect(Fakultas::where('kode_fakultas', 'FIK')->exists())->toBeTrue();
});

test('kode fakultas wajib unik', function () {
    Fakultas::factory()->create(['kode_fakultas' => 'FT']);

    Livewire::test(FakultasIndex::class)
        ->call('openCreate')
        ->set('nama_fakultas', 'Fakultas Teknik Baru')
        ->set('kode_fakultas', 'FT')
        ->call('save')
        ->assertHasErrors(['kode_fakultas']);
});

test('bisa mengedit fakultas', function () {
    $fakultas = Fakultas::factory()->create(['nama_fakultas' => 'Nama Lama']);

    Livewire::test(FakultasIndex::class)
        ->call('openEdit', $fakultas->id)
        ->set('nama_fakultas', 'Nama Baru')
        ->call('save');

    expect($fakultas->fresh()->nama_fakultas)->toBe('Nama Baru');
});

test('bisa menghapus fakultas yang tidak punya anak', function () {
    $fakultas = Fakultas::factory()->create();

    Livewire::test(FakultasIndex::class)
        ->call('confirmDelete', $fakultas->id)
        ->call('delete');

    expect(Fakultas::find($fakultas->id))->toBeNull();
});

test('tidak bisa menghapus fakultas yang masih punya program studi', function () {
    $fakultas = Fakultas::factory()->create();
    ProgramStudi::factory()->create(['fakultas_id' => $fakultas->id]);

    Livewire::test(FakultasIndex::class)
        ->call('confirmDelete', $fakultas->id)
        ->call('delete');

    expect(Fakultas::find($fakultas->id))->not->toBeNull();
});
