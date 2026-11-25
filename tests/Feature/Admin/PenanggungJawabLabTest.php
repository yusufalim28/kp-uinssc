<?php

use App\Livewire\Admin\JadwalManage;
use App\Livewire\Admin\MasterData\PenanggungJawabLabIndex;
use App\Livewire\Public\StrukturLab;
use App\Models\Fakultas;
use App\Models\PenanggungJawabLab;
use App\Models\RuangPraktikum;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
});

test('super admin, operator fakultas, dan ketua lab bisa mengakses halaman CRUD penanggung jawab lab sedangkan dosen ditolak', function () {
    $superAdmin = User::factory()->create();
    $superAdmin->assignRole('super-admin');

    $operator = User::factory()->create();
    $operator->assignRole('operator');

    $ketuaLab = User::factory()->create();
    $ketuaLab->assignRole('ketua-lab');

    $dosen = User::factory()->create();
    $dosen->assignRole('dosen');

    $this->actingAs($superAdmin)->get(route('pjlab.index'))->assertSuccessful();
    $this->actingAs($operator)->get(route('pjlab.index'))->assertSuccessful();
    $this->actingAs($ketuaLab)->get(route('pjlab.index'))->assertSuccessful();
    $this->actingAs($dosen)->get(route('pjlab.index'))->assertForbidden();
});

test('super admin bisa menambah, mengedit dengan foto, dan menghapus penanggung jawab lab', function () {
    Storage::fake('public');

    $fakultas = Fakultas::factory()->create(['nama_fakultas' => 'FITK']);
    $ruang = RuangPraktikum::factory()->create([
        'fakultas_id' => $fakultas->id,
        'nama_ruang' => 'Lab Komputer Algoritma',
    ]);

    $superAdmin = User::factory()->create();
    $superAdmin->assignRole('super-admin');

    $foto = UploadedFile::fake()->image('ketua.jpg', 600, 800);

    Livewire::actingAs($superAdmin)
        ->test(PenanggungJawabLabIndex::class)
        ->call('openCreate')
        ->set('ruang_praktikum_id', $ruang->id)
        ->set('nama', 'Riyanto')
        ->set('jabatan', 'Kepala Laboratorium')
        ->set('tingkat', 'pimpinan')
        ->set('urutan', 1)
        ->set('foto', $foto)
        ->set('email', 'riyanto@uinssc.ac.id')
        ->set('no_telepon', '081234567890')
        ->call('save')
        ->assertHasNoErrors();

    $pj = PenanggungJawabLab::where('nama', 'Riyanto')->first();
    expect($pj)->not->toBeNull();
    expect($pj->fakultas_id)->toBe($fakultas->id);
    expect($pj->tingkat)->toBe('pimpinan');
    Storage::disk('public')->assertExists($pj->foto);

    // Edit dengan format HEIC
    $fotoHeic = UploadedFile::fake()->create('profil.heic', 2048, 'image/heic');

    Livewire::actingAs($superAdmin)
        ->test(PenanggungJawabLabIndex::class)
        ->call('openEdit', $pj->id)
        ->set('jabatan', 'Kepala UPT Laboratorium')
        ->set('foto', $fotoHeic)
        ->call('save')
        ->assertHasNoErrors();

    expect($pj->fresh()->jabatan)->toBe('Kepala UPT Laboratorium');

    // Hapus foto profil lewat tombol Hapus Foto
    $fotoSebelumHapus = $pj->fresh()->foto;
    Livewire::actingAs($superAdmin)
        ->test(PenanggungJawabLabIndex::class)
        ->call('openEdit', $pj->id)
        ->call('removeFoto')
        ->assertSet('hapusFoto', true)
        ->call('save')
        ->assertHasNoErrors();

    expect($pj->fresh()->foto)->toBeNull();
    Storage::disk('public')->assertMissing($fotoSebelumHapus);

    // Hapus
    Livewire::actingAs($superAdmin)
        ->test(PenanggungJawabLabIndex::class)
        ->call('confirmDelete', $pj->id)
        ->call('delete');

    expect(PenanggungJawabLab::find($pj->id))->toBeNull();
});

test('foto penanggung jawab lab lebih dari 20MB atau bukan gambar ditolak', function () {
    Storage::fake('public');

    $ruang = RuangPraktikum::factory()->create();
    $superAdmin = User::factory()->create();
    $superAdmin->assignRole('super-admin');

    $fileTerlaluBesar = UploadedFile::fake()->create('besar.jpg', 21000, 'image/jpeg');
    $fileBukanGambar = UploadedFile::fake()->create('dokumen.pdf', 500, 'application/pdf');

    Livewire::actingAs($superAdmin)
        ->test(PenanggungJawabLabIndex::class)
        ->call('openCreate')
        ->set('ruang_praktikum_id', $ruang->id)
        ->set('nama', 'Budi Santoso')
        ->set('jabatan', 'Pranata Lab')
        ->set('foto', $fileTerlaluBesar)
        ->call('save')
        ->assertHasErrors(['foto']);

    Livewire::actingAs($superAdmin)
        ->test(PenanggungJawabLabIndex::class)
        ->call('openCreate')
        ->set('ruang_praktikum_id', $ruang->id)
        ->set('nama', 'Budi Santoso')
        ->set('jabatan', 'Pranata Lab')
        ->set('foto', $fileBukanGambar)
        ->call('save')
        ->assertHasErrors(['foto']);
});

test('operator fakultas hanya bisa mengelola penanggung jawab pada fakultasnya sendiri', function () {
    $fakA = Fakultas::factory()->create(['nama_fakultas' => 'FASYA']);
    $fakB = Fakultas::factory()->create(['nama_fakultas' => 'FEBI']);

    $ruangA = RuangPraktikum::factory()->create(['fakultas_id' => $fakA->id]);
    $ruangB = RuangPraktikum::factory()->create(['fakultas_id' => $fakB->id]);

    $operatorA = User::factory()->create(['fakultas_id' => $fakA->id]);
    $operatorA->assignRole('operator');

    Livewire::actingAs($operatorA)
        ->test(PenanggungJawabLabIndex::class)
        ->call('openCreate')
        ->set('ruang_praktikum_id', $ruangA->id)
        ->set('nama', 'Pengelola FASYA')
        ->set('jabatan', 'Teknisi Lab')
        ->call('save')
        ->assertHasNoErrors();

    // Mencoba menambah ke ruang fakultas lain -> 403
    Livewire::actingAs($operatorA)
        ->test(PenanggungJawabLabIndex::class)
        ->call('openCreate')
        ->set('ruang_praktikum_id', $ruangB->id)
        ->set('nama', 'Penyusup FEBI')
        ->set('jabatan', 'Teknisi Lab')
        ->call('save')
        ->assertForbidden();
});

test('ketua lab terkunci mengelola penanggung jawab hanya pada ruang laboratorium miliknya', function () {
    $ruangMilik = RuangPraktikum::factory()->create(['nama_ruang' => 'Lab Falak']);
    $ruangLain = RuangPraktikum::factory()->create(['nama_ruang' => 'Lab Multimedia']);

    $ketua = User::factory()->create(['ruang_praktikum_id' => $ruangMilik->id]);
    $ketua->assignRole('ketua-lab');

    Livewire::actingAs($ketua)
        ->test(PenanggungJawabLabIndex::class)
        ->call('openCreate')
        ->assertSet('ruang_praktikum_id', $ruangMilik->id)
        ->set('nama', 'Asisten Falak')
        ->set('jabatan', 'Asisten Laboratorium')
        ->call('save')
        ->assertHasNoErrors();

    Livewire::actingAs($ketua)
        ->test(PenanggungJawabLabIndex::class)
        ->call('openCreate')
        ->set('ruang_praktikum_id', $ruangLain->id)
        ->set('nama', 'Asisten Lab Lain')
        ->set('jabatan', 'Asisten Laboratorium')
        ->call('save')
        ->assertForbidden();
});

test('halaman publik struktur lab menampilkan penanggung jawab aktif terkelompok per ruang dan mendukung filter', function () {
    $fakA = Fakultas::factory()->create(['nama_fakultas' => 'Fakultas Syariah', 'status' => 'aktif']);
    $fakB = Fakultas::factory()->create(['nama_fakultas' => 'Fakultas Dakwah', 'status' => 'aktif']);

    $ruangA = RuangPraktikum::factory()->create(['fakultas_id' => $fakA->id, 'nama_ruang' => 'Laboratorium Peradilan', 'status' => 'aktif']);
    $ruangB = RuangPraktikum::factory()->create(['fakultas_id' => $fakB->id, 'nama_ruang' => 'Lab Multimedia FDKI', 'status' => 'aktif']);

    PenanggungJawabLab::factory()->pimpinan()->create([
        'fakultas_id' => $fakA->id,
        'ruang_praktikum_id' => $ruangA->id,
        'nama' => 'Dr. Ahmad Pimpinan',
        'jabatan' => 'Ketua Laboratorium Peradilan',
        'status' => 'aktif',
    ]);

    PenanggungJawabLab::factory()->create([
        'fakultas_id' => $fakA->id,
        'ruang_praktikum_id' => $ruangA->id,
        'nama' => 'Faisal Teknisi',
        'jabatan' => 'Pranata Laboratorium',
        'tingkat' => 'anggota',
        'status' => 'aktif',
    ]);

    PenanggungJawabLab::factory()->create([
        'fakultas_id' => $fakA->id,
        'ruang_praktikum_id' => $ruangA->id,
        'nama' => 'Mantan Staf Nonaktif',
        'status' => 'nonaktif',
    ]);

    PenanggungJawabLab::factory()->create([
        'fakultas_id' => $fakB->id,
        'ruang_praktikum_id' => $ruangB->id,
        'nama' => 'Intan Multimedia',
        'status' => 'aktif',
    ]);

    $this->get(route('struktur.lab'))
        ->assertSuccessful()
        ->assertSee('Penanggung Jawab Laboratorium')
        ->assertSee('Dr. Ahmad Pimpinan')
        ->assertSee('Faisal Teknisi')
        ->assertSee('Intan Multimedia')
        ->assertDontSee('Mantan Staf Nonaktif');

    Livewire::test(StrukturLab::class)
        ->set('fakultasId', $fakA->id)
        ->assertSee('Dr. Ahmad Pimpinan')
        ->assertDontSee('Intan Multimedia');
});

test('validasi nomor whatsapp indonesia dan email menolak input sembarangan serta menerima format valid', function () {
    $ruang = RuangPraktikum::factory()->create();
    $superAdmin = User::factory()->create();
    $superAdmin->assignRole('super-admin');

    $invalidPhones = ['sembarang', '12345', '08123', '08012345678', '+14155552671', '08123456789012345'];
    foreach ($invalidPhones as $phone) {
        Livewire::actingAs($superAdmin)
            ->test(PenanggungJawabLabIndex::class)
            ->call('openCreate')
            ->set('ruang_praktikum_id', $ruang->id)
            ->set('nama', 'Uji WA Gagal')
            ->set('jabatan', 'Teknisi')
            ->set('no_telepon', $phone)
            ->call('save')
            ->assertHasErrors(['no_telepon']);
    }

    $invalidEmails = ['sembarang', 'nama@localhost', 'nama@gmail', 'nama@gmail.c', '@gmail.com'];
    foreach ($invalidEmails as $email) {
        Livewire::actingAs($superAdmin)
            ->test(PenanggungJawabLabIndex::class)
            ->call('openCreate')
            ->set('ruang_praktikum_id', $ruang->id)
            ->set('nama', 'Uji Email Gagal')
            ->set('jabatan', 'Teknisi')
            ->set('email', $email)
            ->call('save')
            ->assertHasErrors(['email']);
    }

    // Format valid (+628 dan 08 dengan tanda strip, serta gmail.com & uinssc.ac.id) berhasil disimpan & dinormalisasi
    Livewire::actingAs($superAdmin)
        ->test(PenanggungJawabLabIndex::class)
        ->call('openCreate')
        ->set('ruang_praktikum_id', $ruang->id)
        ->set('nama', 'Staf Valid Satu')
        ->set('jabatan', 'Pranata Lab')
        ->set('email', '  Staf.Lab@Gmail.com ')
        ->set('no_telepon', '+62 812-3456-7890')
        ->call('save')
        ->assertHasNoErrors();

    $saved = PenanggungJawabLab::where('nama', 'Staf Valid Satu')->first();
    expect($saved)->not->toBeNull()
        ->and($saved->email)->toBe('staf.lab@gmail.com')
        ->and($saved->no_telepon)->toBe('+6281234567890')
        ->and($saved->whatsapp_url)->toBe('https://wa.me/6281234567890');
});

test('fitur import excel dan download template sudah dihapus dari kelola jadwal praktikum', function () {
    $superAdmin = User::factory()->create();
    $superAdmin->assignRole('super-admin');

    $this->actingAs($superAdmin)
        ->get('/jadwal/template/download')
        ->assertNotFound();

    Livewire::actingAs($superAdmin)
        ->test(JadwalManage::class)
        ->assertDontSee('Import Excel')
        ->assertDontSee('Unduh Format Excel');
});
