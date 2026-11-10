<?php

use App\Livewire\Admin\SampahIndex;
use App\Models\Dosen;
use Database\Seeders\RolePermissionSeeder;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
    loginAsRole('super-admin');
});

test('bisa menampilkan data yang terhapus di tempat sampah', function () {
    $dosen = Dosen::factory()->create(['nama' => 'Dosen Terhapus']);
    $dosen->delete();

    Livewire::test(SampahIndex::class)
        ->assertSee('Dosen Terhapus');
});

test('super admin bisa memulihkan semua data dari tempat sampah', function () {
    $dosen1 = Dosen::factory()->create(['nama' => 'Dosen Satu']);
    $dosen2 = Dosen::factory()->create(['nama' => 'Dosen Dua']);
    $dosen1->delete();
    $dosen2->delete();

    expect(Dosen::onlyTrashed()->count())->toBe(2);

    Livewire::test(SampahIndex::class)
        ->call('restoreAll')
        ->assertHasNoErrors();

    expect(Dosen::onlyTrashed()->count())->toBe(0);
    expect(Dosen::count())->toBe(2);
});

test('super admin bisa mengosongkan tempat sampah (hapus permanen semua)', function () {
    $dosen1 = Dosen::factory()->create(['nama' => 'Dosen Hapus 1']);
    $dosen2 = Dosen::factory()->create(['nama' => 'Dosen Hapus 2']);
    $dosen1->delete();
    $dosen2->delete();

    expect(Dosen::onlyTrashed()->count())->toBe(2);

    Livewire::test(SampahIndex::class)
        ->call('forceDeleteAll')
        ->assertHasNoErrors();

    expect(Dosen::withTrashed()->count())->toBe(0);
});
