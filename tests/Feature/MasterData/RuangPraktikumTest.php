<?php

use App\Livewire\Admin\MasterData\RuangPraktikumIndex;
use App\Models\Fakultas;
use App\Models\JadwalPraktikum;
use App\Models\ProgramStudi;
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

test('bisa menambah ruang multi-prodi lintas fakultas dan menyimpan pivot', function () {
    $fak1 = Fakultas::factory()->create(['nama_fakultas' => 'FITK']);
    $fak2 = Fakultas::factory()->create(['nama_fakultas' => 'FUAD']);

    $prodi1 = ProgramStudi::factory()->create(['fakultas_id' => $fak1->id, 'nama_program_studi' => 'PBA']);
    $prodi2 = ProgramStudi::factory()->create(['fakultas_id' => $fak2->id, 'nama_program_studi' => 'BSA']);

    Livewire::test(RuangPraktikumIndex::class)
        ->call('openCreate')
        ->set('kode_ruang', 'LAB-BA-01')
        ->set('nama_ruang', 'Lab Bahasa Arab Terpadu')
        ->set('lokasi', 'Gedung FITK Lt. 2')
        ->set('kapasitas', 35)
        ->set('cakupan_akses', 'khusus_multi_prodi')
        ->set('selected_prodi_ids', [$prodi1->id, $prodi2->id])
        ->call('save')
        ->assertHasNoErrors();

    $ruang = RuangPraktikum::where('kode_ruang', 'LAB-BA-01')->first();
    expect($ruang)->not->toBeNull()
        ->and($ruang->cakupan_akses)->toBe('khusus_multi_prodi')
        ->and($ruang->prodiPengguna()->pluck('program_studi.id')->toArray())->toEqualCanonicalizing([$prodi1->id, $prodi2->id]);

    // Test helper canBeUsedByProdi
    expect($ruang->canBeUsedByProdi($prodi1->id))->toBeTrue()
        ->and($ruang->canBeUsedByProdi($prodi2->id))->toBeTrue();

    // Prodi lain harus false
    $prodiLain = ProgramStudi::factory()->create();
    expect($ruang->canBeUsedByProdi($prodiLain->id))->toBeFalse();
});

test('ruang multi-prodi wajib memilih minimal satu prodi pemakai', function () {
    Livewire::test(RuangPraktikumIndex::class)
        ->call('openCreate')
        ->set('kode_ruang', 'LAB-BA-02')
        ->set('nama_ruang', 'Lab Bahasa')
        ->set('lokasi', 'Gedung FITK Lt. 2')
        ->set('kapasitas', 35)
        ->set('cakupan_akses', 'khusus_multi_prodi')
        ->set('selected_prodi_ids', [])
        ->call('save')
        ->assertHasErrors(['selected_prodi_ids']);
});
