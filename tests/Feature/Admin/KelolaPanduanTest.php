<?php

use App\Livewire\Admin\KelolaPanduan;
use App\Livewire\Public\PanduanPublik;
use App\Models\Panduan;
use App\Models\User;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

beforeEach(function () {
    Role::firstOrCreate(['name' => 'super-admin']);
    Role::firstOrCreate(['name' => 'operator']);
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
