<?php

use App\Livewire\Admin\MasterData\FakultasIndex;
use App\Livewire\Admin\MasterData\MataKuliahIndex;
use App\Livewire\Admin\MasterData\PenanggungJawabLabIndex;
use App\Livewire\Admin\MasterData\TahunAkademikIndex;
use App\Livewire\Admin\PanduanInternal;
use App\Livewire\Admin\SampahIndex;
use App\Livewire\Admin\UserManage;
use App\Models\Fakultas;
use App\Models\MataKuliah;
use App\Models\PenanggungJawabLab;
use App\Models\ProgramStudi;
use App\Models\RuangPraktikum;
use App\Models\TahunAkademik;
use App\Models\User;
use Database\Seeders\DemoViewerSeeder;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
});

test('DemoViewerSeeder membuat role demo-viewer dan akun demo read-only secara idempoten', function () {
    $this->seed(DemoViewerSeeder::class);
    $this->seed(DemoViewerSeeder::class);

    $demoUser = User::where('email', 'demo@uinssc.ac.id')->first();

    expect($demoUser)->not->toBeNull()
        ->and($demoUser->isDemoViewer())->toBeTrue()
        ->and($demoUser->hasSuperAdminView())->toBeTrue();
});

test('demo-viewer dapat mengakses seluruh halaman kelola setara super-admin beserta banner mode demo', function () {
    $demoUser = loginAsRole('demo-viewer');

    $routes = [
        '/dashboard',
        '/master/fakultas',
        '/master/prodi',
        '/master/tahun-akademik',
        '/master/kelas',
        '/master/mata-kuliah',
        '/master/dosen',
        '/master/ruang',
        '/master/penanggung-jawab-lab',
        '/jadwal-manage',
        '/pengajuan',
        '/master/pengguna',
        '/sampah',
        '/log-aktivitas',
        '/kelola-panduan',
        '/admin/panduan',
    ];

    foreach ($routes as $uri) {
        $this->get($uri)
            ->assertOk()
            ->assertSee('Mode Demo (Hanya Baca / Read-Only)');
    }

    // Tidak bisa mengakses halaman khusus dosen
    $this->get('/pengajuan-saya')->assertForbidden();
});

test('demo-viewer dapat melakukan interaksi baca seperti filter, pindah tab, dan pratinjau modal', function () {
    $fakultas = Fakultas::factory()->create(['nama_fakultas' => 'Fakultas Teknik Demo']);

    loginAsRole('demo-viewer');

    Livewire::test(FakultasIndex::class)
        ->set('search', 'Teknik Demo')
        ->assertSee('Fakultas Teknik Demo')
        ->call('openEdit', $fakultas->id)
        ->assertSet('showModal', true)
        ->assertSet('nama_fakultas', 'Fakultas Teknik Demo');

    Livewire::test(PanduanInternal::class)
        ->assertSet('userRoleKey', 'super-admin')
        ->call('setTab', 'fitur')
        ->assertSet('activeTab', 'fitur');
});

test('demo-viewer diblokir (403) pada semua aksi create, update, delete, restore, dan hapus file', function () {
    Storage::fake('public');

    $fakultas = Fakultas::factory()->create(['kode_fakultas' => 'FTD', 'nama_fakultas' => 'Fakultas Asli']);
    $prodi = ProgramStudi::factory()->create(['fakultas_id' => $fakultas->id]);
    $ruang = RuangPraktikum::factory()->create(['fakultas_id' => $fakultas->id, 'program_studi_id' => $prodi->id]);
    $pj = PenanggungJawabLab::factory()->create(['fakultas_id' => $fakultas->id, 'ruang_praktikum_id' => $ruang->id]);
    $tahun = TahunAkademik::factory()->create(['is_aktif' => false]);
    $targetUser = User::factory()->create();

    Storage::disk('public')->put('modul-praktikum/tes.pdf', 'isi-pdf');
    $mk = MataKuliah::factory()->create([
        'fakultas_id' => $fakultas->id,
        'program_studi_id' => $prodi->id,
        'file_modul' => 'modul-praktikum/tes.pdf',
    ]);

    $deletedFakultas = Fakultas::factory()->create(['nama_fakultas' => 'Fakultas Sampah']);
    $deletedFakultas->delete();

    loginAsRole('demo-viewer');

    // Create & Update Fakultas ditolak
    Livewire::test(FakultasIndex::class)
        ->set('kode_fakultas', 'BARU')
        ->set('nama_fakultas', 'Fakultas Baru')
        ->call('save')
        ->assertForbidden();

    // Delete Fakultas ditolak
    Livewire::test(FakultasIndex::class)
        ->call('confirmDelete', $fakultas->id)
        ->call('delete')
        ->assertForbidden();

    // Create / Delete Penanggung Jawab Lab ditolak
    Livewire::test(PenanggungJawabLabIndex::class)
        ->call('confirmDelete', $pj->id)
        ->call('delete')
        ->assertForbidden();

    // Aktivasi Tahun Akademik ditolak
    Livewire::test(TahunAkademikIndex::class)
        ->call('setAktif', $tahun->id)
        ->assertForbidden();

    // Kelola Pengguna (create/delete/broadcast onboarding) ditolak
    Livewire::test(UserManage::class)
        ->call('broadcastNoticeAgain')
        ->assertForbidden();

    Livewire::test(UserManage::class)
        ->call('confirmDelete', $targetUser->id)
        ->call('delete')
        ->assertForbidden();

    // Restore / Force Delete di Tempat Sampah ditolak
    Livewire::test(SampahIndex::class)
        ->call('restore', 'fakultas', $deletedFakultas->id)
        ->assertForbidden();

    // Hapus file modul PDF juga diblokir sebelum menyentuh Storage
    Livewire::test(MataKuliahIndex::class)
        ->call('openEdit', $mk->id)
        ->call('hapusFileModul')
        ->assertForbidden();

    // Pastikan seluruh data & file tetap utuh 100%
    $this->assertDatabaseMissing('fakultas', ['kode_fakultas' => 'BARU']);
    $this->assertDatabaseHas('fakultas', ['id' => $fakultas->id, 'deleted_at' => null]);
    $this->assertDatabaseHas('penanggung_jawab_lab', ['id' => $pj->id, 'deleted_at' => null]);
    $this->assertDatabaseHas('tahun_akademik', ['id' => $tahun->id, 'is_aktif' => false]);
    $this->assertDatabaseHas('users', ['id' => $targetUser->id]);
    $this->assertSoftDeleted('fakultas', ['id' => $deletedFakultas->id]);
    Storage::disk('public')->assertExists('modul-praktikum/tes.pdf');
});

test('demo-viewer tetap dapat logout dengan normal dan super-admin tetap bebas melakukan CRUD', function () {
    loginAsRole('demo-viewer');

    $this->post('/logout')->assertRedirect('/');
    $this->assertGuest();

    // Super admin tidak terdampak guard demo
    loginAsRole('super-admin');

    Livewire::test(FakultasIndex::class)
        ->set('kode_fakultas', 'SA01')
        ->set('nama_fakultas', 'Fakultas Super Admin')
        ->set('status', 'aktif')
        ->call('save')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('fakultas', ['kode_fakultas' => 'SA01']);
});
