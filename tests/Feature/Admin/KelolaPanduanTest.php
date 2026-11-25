<?php

use App\Livewire\Admin\KelolaPanduan;
use App\Livewire\Admin\PanduanInternal;
use App\Livewire\Public\PanduanPublik;
use App\Models\Panduan;
use App\Models\User;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'super-admin']);
    Role::firstOrCreate(['name' => 'operator']);
    Role::firstOrCreate(['name' => 'ketua-lab']);
    Role::firstOrCreate(['name' => 'dosen']);
});

test('super admin bisa mengakses halaman kelola panduan', function () {
    $superAdmin = User::factory()->create();
    $superAdmin->assignRole('super-admin');

    $this->actingAs($superAdmin)
        ->get(route('panduan.manage'))
        ->assertOk();
});

test('operator biasa tidak bisa mengakses halaman kelola panduan', function () {
    $operator = User::factory()->create();
    $operator->assignRole('operator');

    $this->actingAs($operator)
        ->get(route('panduan.manage'))
        ->assertForbidden();
});

test('super admin bisa menambah dan mengubah butir panduan', function () {
    $superAdmin = User::factory()->create();
    $superAdmin->assignRole('super-admin');

    Livewire::actingAs($superAdmin)
        ->test(KelolaPanduan::class)
        ->set('kategori', 'publik')
        ->set('urutan', 1)
        ->set('judul', 'Tata Tertib Khusus Lab')
        ->set('subjudul', 'Pedoman kedisiplinan dan penggunaan alat')
        ->set('konten', '<p>Wajib menggunakan jas lab resmi.</p>')
        ->set('is_active', true)
        ->call('save')
        ->assertHasNoErrors();

    expect(Panduan::where('judul', 'Tata Tertib Khusus Lab')->exists())->toBeTrue();
});

test('halaman publik menampilkan panduan yang aktif', function () {
    Panduan::create([
        'kategori' => 'publik',
        'urutan' => 1,
        'judul' => 'Aturan Masuk Ruang Komputer',
        'subjudul' => 'Harus memakai sepatu',
        'konten' => '<p>Dilarang membawa makanan dan minuman.</p>',
        'is_active' => true,
    ]);

    Livewire::test(PanduanPublik::class)
        ->assertSee('Aturan Masuk Ruang Komputer')
        ->assertSee('Harus memakai sepatu');
});

test('operator hanya melihat alur operator dan petunjuk tiap halaman, bukan alur role lain', function () {
    $operator = User::factory()->create();
    $operator->assignRole('operator');

    Livewire::actingAs($operator)
        ->test(PanduanInternal::class)
        ->assertSee('Alur Operator Fakultas')
        ->assertSee('Petunjuk Tiap Halaman')
        ->assertDontSee('Alur Ketua Laboratorium')
        ->assertDontSee('Alur Super Admin')
        ->assertDontSee('Alur Dosen / Tutor')
        ->call('setTab', 'super-admin')
        ->assertSet('activeTab', 'operator')
        ->call('setTab', 'fitur')
        ->assertSet('activeTab', 'fitur');
});

test('ketua lab hanya melihat alur ketua laboratorium dan petunjuk tiap halaman', function () {
    $ketuaLab = User::factory()->create();
    $ketuaLab->assignRole('ketua-lab');

    Livewire::actingAs($ketuaLab)
        ->test(PanduanInternal::class)
        ->assertSee('Alur Ketua Laboratorium')
        ->assertSee('Petunjuk Tiap Halaman')
        ->assertDontSee('Alur Operator Fakultas')
        ->assertDontSee('Alur Super Admin')
        ->assertDontSee('Alur Dosen / Tutor')
        ->call('setTab', 'operator')
        ->assertSet('activeTab', 'ketua-lab');
});

test('super admin hanya melihat alur super admin dan petunjuk tiap halaman pada panduan internal', function () {
    $superAdmin = User::factory()->create();
    $superAdmin->assignRole('super-admin');

    Livewire::actingAs($superAdmin)
        ->test(PanduanInternal::class)
        ->assertSee('Alur Super Admin')
        ->assertSee('Petunjuk Tiap Halaman')
        ->assertDontSee('Alur Operator Fakultas')
        ->assertDontSee('Alur Ketua Laboratorium')
        ->assertDontSee('Alur Dosen / Tutor');
});
