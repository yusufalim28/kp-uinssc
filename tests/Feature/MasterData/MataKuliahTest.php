<?php

use App\Livewire\Admin\MasterData\MataKuliahIndex;
use App\Models\Fakultas;
use App\Models\JadwalPraktikum;
use App\Models\MataKuliah;
use App\Models\ProgramStudi;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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

test('bisa menambahkan modul praktikum file dan tautan drive', function () {
    Storage::fake('public');
    $prodi = ProgramStudi::factory()->create();
    $file = UploadedFile::fake()->create('modul-algo.pdf', 500, 'application/pdf');

    Livewire::test(MataKuliahIndex::class)
        ->call('openCreate')
        ->set('program_studi_id', $prodi->id)
        ->set('kode_mk', 'TIF102')
        ->set('nama_mk', 'Praktikum Algoritma')
        ->set('sks', 2)
        ->set('semester', 1)
        ->set('upload_modul', $file)
        ->set('tautan_modul', 'https://drive.google.com/file/d/xyz')
        ->set('deskripsi_modul', 'Wajib dipelajari sebelum masuk lab')
        ->call('save')
        ->assertHasNoErrors();

    $mk = MataKuliah::where('kode_mk', 'TIF102')->first();
    expect($mk)->not->toBeNull();
    expect($mk->file_modul)->not->toBeNull();
    expect($mk->tautan_modul)->toBe('https://drive.google.com/file/d/xyz');
    expect($mk->hasModul())->toBeTrue();
    Storage::disk('public')->assertExists($mk->file_modul);
});

test('bisa menambah mata kuliah bersama fakultas lintas jurusan', function () {
    $fakultas = Fakultas::factory()->create();

    Livewire::test(MataKuliahIndex::class)
        ->call('openCreate')
        ->set('cakupan_mk', 'bersama_fakultas')
        ->set('fakultas_id', $fakultas->id)
        ->set('kode_mk', 'FASYA-PA')
        ->set('nama_mk', 'Peradilan Agama')
        ->set('sks', 2)
        ->set('semester', 5)
        ->call('save')
        ->assertHasNoErrors();

    $mk = MataKuliah::where('kode_mk', 'FASYA-PA')->first();
    expect($mk)->not->toBeNull();
    expect($mk->isBersamaFakultas())->toBeTrue();
    expect($mk->program_studi_id)->toBeNull();
    expect($mk->fakultas_id)->toBe($fakultas->id);
});
