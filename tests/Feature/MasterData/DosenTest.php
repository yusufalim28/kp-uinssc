<?php

use App\Livewire\Admin\MasterData\DosenIndex;
use App\Models\Dosen;
use App\Models\Fakultas;
use App\Models\JadwalPraktikum;
use App\Models\MataKuliah;
use App\Models\ProgramStudi;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
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

test('bisa menambah dosen tanpa email dengan otomatis alias NIDN', function () {
    Livewire::test(DosenIndex::class)
        ->call('openCreate')
        ->set('nidn', '0945198705')
        ->set('nama', 'Dr. Andi Prasetyo')
        ->set('email', '')
        ->call('save')
        ->assertHasNoErrors();

    $dosen = Dosen::where('nidn', '0945198705')->first();
    expect($dosen)->not->toBeNull();
    expect($dosen->email)->toBe('0945198705@uinssc.ac.id');
});

test('email dosen jika diisi wajib valid dan unik', function () {
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

test('operator hanya melihat dosen yang mengajar di fakultasnya secara default', function () {
    $fakultasA = Fakultas::factory()->create(['nama_fakultas' => 'FITK', 'kode_fakultas' => 'FITK']);
    $fakultasB = Fakultas::factory()->create(['nama_fakultas' => 'FEBI', 'kode_fakultas' => 'FEBI']);

    $prodiA = ProgramStudi::factory()->create(['fakultas_id' => $fakultasA->id]);
    $mkA = MataKuliah::factory()->create(['program_studi_id' => $prodiA->id]);

    $dosenA = Dosen::factory()->create(['nama' => 'Dosen FITK', 'nidn' => '11111']);
    JadwalPraktikum::factory()->create([
        'dosen_id' => $dosenA->id,
        'mata_kuliah_id' => $mkA->id,
    ]);

    $dosenB = Dosen::factory()->create(['nama' => 'Dosen FEBI', 'nidn' => '22222']);

    // Login sebagai operator FITK
    $operatorUser = User::factory()->create(['fakultas_id' => $fakultasA->id]);
    $operatorUser->assignRole('operator');
    $this->actingAs($operatorUser);

    Livewire::test(DosenIndex::class)
        ->assertSee('Dosen FITK')
        ->assertDontSee('Dosen FEBI');

    // Ketika beralih ke seluruh direktori kampus, dosen B terlihat
    Livewire::test(DosenIndex::class)
        ->set('viewScope', 'semua')
        ->assertSee('Dosen FITK')
        ->assertSee('Dosen FEBI');
});
