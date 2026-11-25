<?php

use App\Livewire\Admin\MasterData\RuangPraktikumIndex;
use App\Models\Fakultas;
use App\Models\JadwalPraktikum;
use App\Models\ProgramStudi;
use App\Models\RuangPraktikum;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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

test('bisa menambah ruang dengan kapasitas fleksibel dan batas maksimal', function () {
    Livewire::test(RuangPraktikumIndex::class)
        ->call('openCreate')
        ->set('kode_ruang', 'LAB-FASYA-01')
        ->set('nama_ruang', 'Ruang Sidang Semu (Moot Court)')
        ->set('lokasi', 'Gedung FASYA Lantai 3')
        ->set('tipe_kapasitas', 'fleksibel')
        ->set('kapasitas', 40)
        ->set('kapasitas_maksimal', 80)
        ->call('save')
        ->assertHasNoErrors();

    $ruang = RuangPraktikum::where('kode_ruang', 'LAB-FASYA-01')->first();
    expect($ruang)->not->toBeNull()
        ->and($ruang->tipe_kapasitas)->toBe('fleksibel')
        ->and($ruang->kapasitas)->toBe(40)
        ->and($ruang->kapasitas_maksimal)->toBe(80)
        ->and($ruang->isKapasitasFleksibel())->toBeTrue()
        ->and($ruang->label_kapasitas)->toBe('Fleksibel (Maks. 80 Mahasiswa)');
});

test('foto ruang yang diunggah saat edit tersimpan ke storage dan record', function () {
    Storage::fake('public');
    $ruang = RuangPraktikum::factory()->create(['foto' => null]);

    Livewire::test(RuangPraktikumIndex::class)
        ->call('openEdit', $ruang->id)
        ->set('foto', UploadedFile::fake()->image('lab.jpg', 1600, 1200)->size(15000))
        ->call('save')
        ->assertHasNoErrors();

    $ruang->refresh();
    expect($ruang->foto)->not->toBeNull();
    Storage::disk('public')->assertExists($ruang->foto);
});

test('foto ruang format heic diterima', function () {
    Storage::fake('public');
    $ruang = RuangPraktikum::factory()->create(['foto' => null]);

    Livewire::test(RuangPraktikumIndex::class)
        ->call('openEdit', $ruang->id)
        ->set('foto', UploadedFile::fake()->create('IMG_0001.heic', 4000, 'image/heic'))
        ->call('save')
        ->assertHasNoErrors();

    expect($ruang->refresh()->foto)->toEndWith('.heic');
});

test('foto ruang lebih dari 20MB atau bukan gambar ditolak', function () {
    Storage::fake('public');
    $ruang = RuangPraktikum::factory()->create();

    Livewire::test(RuangPraktikumIndex::class)
        ->call('openEdit', $ruang->id)
        ->set('foto', UploadedFile::fake()->image('besar.jpg')->size(21000))
        ->call('save')
        ->assertHasErrors(['foto']);

    Livewire::test(RuangPraktikumIndex::class)
        ->call('openEdit', $ruang->id)
        ->set('foto', UploadedFile::fake()->create('dokumen.pdf', 100, 'application/pdf'))
        ->call('save')
        ->assertHasErrors(['foto']);
});

test('foto ruang yang sudah tersimpan bisa dihapus lewat tombol hapus foto', function () {
    Storage::fake('public');
    $path = 'ruang-praktikum/foto-lama.jpg';
    Storage::disk('public')->put($path, 'konten-foto');

    $ruang = RuangPraktikum::factory()->create(['foto' => $path]);

    Livewire::test(RuangPraktikumIndex::class)
        ->call('openEdit', $ruang->id)
        ->call('removeFoto')
        ->assertSet('hapusFoto', true)
        ->assertSet('existingFoto', null)
        ->call('save')
        ->assertHasNoErrors();

    expect($ruang->fresh()->foto)->toBeNull();
    Storage::disk('public')->assertMissing($path);
});
