<?php

use App\Livewire\Admin\JadwalManage;
use App\Models\Dosen;
use App\Models\Fakultas;
use App\Models\JadwalPraktikum;
use App\Models\Kelas;
use App\Models\MataKuliah;
use App\Models\PengajuanPerubahan;
use App\Models\ProgramStudi;
use App\Models\RuangPraktikum;
use App\Models\TahunAkademik;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
    loginAsRole('operator');

    $this->mk = MataKuliah::factory()->create();
    $this->kelas = Kelas::factory()->create();
    $this->dosen = Dosen::factory()->create();
    $this->ruang = RuangPraktikum::factory()->create();
    $this->tahun = TahunAkademik::factory()->create();
});

function isiFormJadwal($component, array $override = [])
{
    $data = array_merge([
        'mata_kuliah_id' => test()->mk->id,
        'kelas_id' => test()->kelas->id,
        'dosen_id' => test()->dosen->id,
        'ruang_praktikum_id' => test()->ruang->id,
        'tahun_akademik_id' => test()->tahun->id,
        'hari' => 'Senin',
        'jam_mulai' => '08:00',
        'jam_selesai' => '10:00',
        'status' => 'aktif',
    ], $override);

    foreach ($data as $key => $value) {
        $component->set($key, $value);
    }

    return $component;
}

test('bisa membuat jadwal baru tanpa bentrok', function () {
    $component = Livewire::test(JadwalManage::class)->call('openCreate');

    isiFormJadwal($component)->call('save');

    expect(JadwalPraktikum::count())->toBe(1);
    $component->assertSet('conflictMessage', null);
});

test('jadwal bentrok ruang ditolak dan pesan error tampil', function () {
    JadwalPraktikum::factory()->create([
        'ruang_praktikum_id' => $this->ruang->id,
        'tahun_akademik_id' => $this->tahun->id,
        'hari' => 'Senin',
        'jam_mulai' => '08:00',
        'jam_selesai' => '10:00',
    ]);

    $component = Livewire::test(JadwalManage::class)->call('openCreate');

    isiFormJadwal($component, ['jam_mulai' => '09:00', 'jam_selesai' => '11:00'])
        ->call('save');

    expect(JadwalPraktikum::count())->toBe(1); // tetap 1, yang baru gagal disimpan
    $component->assertSet('conflictMessage', fn ($msg) => ! empty($msg));
});

test('mengedit jadwal tanpa mengubah jam tidak dianggap bentrok dengan dirinya sendiri', function () {
    $jadwal = JadwalPraktikum::factory()->create([
        'ruang_praktikum_id' => $this->ruang->id,
        'tahun_akademik_id' => $this->tahun->id,
        'hari' => 'Senin',
        'jam_mulai' => '08:00',
        'jam_selesai' => '10:00',
        'keterangan' => null,
    ]);

    $component = Livewire::test(JadwalManage::class)->call('openEdit', $jadwal->id);

    $component->set('keterangan', 'Ruang pindah sementara')->call('save');

    expect($jadwal->fresh()->keterangan)->toBe('Ruang pindah sementara');
    $component->assertSet('conflictMessage', null);
});

test('tidak bisa menghapus jadwal yang masih punya riwayat pengajuan perubahan', function () {
    $jadwal = JadwalPraktikum::factory()->create();
    PengajuanPerubahan::create([
        'jadwal_praktikum_id' => $jadwal->id,
        'dosen_id' => $this->dosen->id,
        'perubahan' => 'Pindah jam',
        'alasan' => 'Ada rapat',
        'status' => 'menunggu',
    ]);

    Livewire::test(JadwalManage::class)
        ->call('confirmDelete', $jadwal->id)
        ->call('delete');

    expect(JadwalPraktikum::find($jadwal->id))->not->toBeNull();
});

test('prodi yang diizinkan pada ruang multi-prodi bisa membuat jadwal sedangkan prodi lain ditolak', function () {
    $fak1 = Fakultas::factory()->create(['nama_fakultas' => 'FITK']);
    $fak2 = Fakultas::factory()->create(['nama_fakultas' => 'FUAD']);

    $prodiPBA = ProgramStudi::factory()->create(['fakultas_id' => $fak1->id, 'nama_program_studi' => 'PBA']);
    $prodiBSA = ProgramStudi::factory()->create(['fakultas_id' => $fak2->id, 'nama_program_studi' => 'BSA']);
    $prodiLain = ProgramStudi::factory()->create(['nama_program_studi' => 'Hukum']);

    $ruangMulti = RuangPraktikum::factory()->create([
        'nama_ruang' => 'Lab Bahasa Arab Terpadu',
        'cakupan_akses' => 'khusus_multi_prodi',
        'fakultas_id' => $fak1->id,
    ]);
    $ruangMulti->prodiPengguna()->sync([$prodiPBA->id, $prodiBSA->id]);

    $dosen2 = Dosen::factory()->create();

    $mkPBA = MataKuliah::factory()->create(['program_studi_id' => $prodiPBA->id]);
    $mkBSA = MataKuliah::factory()->create(['program_studi_id' => $prodiBSA->id]);
    $mkLain = MataKuliah::factory()->create(['program_studi_id' => $prodiLain->id]);

    $kelasPBA = Kelas::factory()->create(['program_studi_id' => $prodiPBA->id]);
    $kelasBSA = Kelas::factory()->create(['program_studi_id' => $prodiBSA->id]);
    $kelasLain = Kelas::factory()->create(['program_studi_id' => $prodiLain->id]);

    // 1. Jadwal PBA di ruang multi -> Berhasil
    Livewire::test(JadwalManage::class)
        ->call('openCreate')
        ->set('mata_kuliah_id', $mkPBA->id)
        ->set('kelas_id', $kelasPBA->id)
        ->set('dosen_id', $this->dosen->id)
        ->set('ruang_praktikum_id', $ruangMulti->id)
        ->set('tahun_akademik_id', $this->tahun->id)
        ->set('hari', 'Senin')
        ->set('jam_mulai', '08:00')
        ->set('jam_selesai', '10:00')
        ->call('save')
        ->assertHasNoErrors();

    // 2. Jadwal BSA di ruang multi pada jam berbeda -> Berhasil
    Livewire::test(JadwalManage::class)
        ->call('openCreate')
        ->set('mata_kuliah_id', $mkBSA->id)
        ->set('kelas_id', $kelasBSA->id)
        ->set('dosen_id', $dosen2->id)
        ->set('ruang_praktikum_id', $ruangMulti->id)
        ->set('tahun_akademik_id', $this->tahun->id)
        ->set('hari', 'Senin')
        ->set('jam_mulai', '10:00')
        ->set('jam_selesai', '12:00')
        ->call('save')
        ->assertHasNoErrors();

    // 3. Jadwal BSA di ruang multi pada jam yang bertabrakan -> Ditolak bentrok
    $compBentrok = Livewire::test(JadwalManage::class)
        ->call('openCreate')
        ->set('mata_kuliah_id', $mkBSA->id)
        ->set('kelas_id', $kelasBSA->id)
        ->set('dosen_id', $dosen2->id)
        ->set('ruang_praktikum_id', $ruangMulti->id)
        ->set('tahun_akademik_id', $this->tahun->id)
        ->set('hari', 'Senin')
        ->set('jam_mulai', '09:00')
        ->set('jam_selesai', '11:00')
        ->call('save');

    $compBentrok->assertSet('conflictMessage', fn ($msg) => ! empty($msg));

    // 4. Jadwal Prodi Lain di ruang multi -> Ditolak validasi hak pakai prodi
    Livewire::test(JadwalManage::class)
        ->call('openCreate')
        ->set('mata_kuliah_id', $mkLain->id)
        ->set('kelas_id', $kelasLain->id)
        ->set('dosen_id', $dosen2->id)
        ->set('ruang_praktikum_id', $ruangMulti->id)
        ->set('tahun_akademik_id', $this->tahun->id)
        ->set('hari', 'Selasa')
        ->set('jam_mulai', '08:00')
        ->set('jam_selesai', '10:00')
        ->call('save')
        ->assertHasErrors(['ruang_praktikum_id']);
});

test('ketua lab hanya berwenang menambah dan mengelola jadwal di laboratorium miliknya', function () {
    $ruangKu = RuangPraktikum::factory()->create(['nama_ruang' => 'Lab Komputer 1']);
    $ruangLain = RuangPraktikum::factory()->create(['nama_ruang' => 'Lab Komputer 2']);

    $userKetua = User::factory()->create(['ruang_praktikum_id' => $ruangKu->id]);
    $userKetua->assignRole('ketua-lab');

    test()->actingAs($userKetua);

    // openCreate otomatis mengeset ruang_praktikum_id ke ruangKu
    $comp = Livewire::test(JadwalManage::class)
        ->call('openCreate');
    $comp->assertSet('ruang_praktikum_id', $ruangKu->id);

    // Simpan di ruang sendiri berhasil
    isiFormJadwal($comp, ['ruang_praktikum_id' => $ruangKu->id])
        ->call('save')
        ->assertHasNoErrors();

    expect(JadwalPraktikum::where('ruang_praktikum_id', $ruangKu->id)->exists())->toBeTrue();

    // Mencoba menyimpan di ruang lain -> 403 Forbidden
    $compLain = Livewire::test(JadwalManage::class)->call('openCreate');
    isiFormJadwal($compLain, ['ruang_praktikum_id' => $ruangLain->id])
        ->call('save')
        ->assertForbidden();
});

test('bisa membuat jadwal dengan mata kuliah bersama fakultas dan ruang bersama fakultas', function () {
    $fakultas = Fakultas::factory()->create(['nama_fakultas' => 'Fakultas Syariah']);
    $prodiHKI = ProgramStudi::factory()->create(['fakultas_id' => $fakultas->id, 'nama_program_studi' => 'HKI']);
    $kelasHKI = Kelas::factory()->create(['program_studi_id' => $prodiHKI->id, 'nama_kelas' => 'HK A']);

    $mkBersama = MataKuliah::factory()->create([
        'nama_mk' => 'Peradilan Agama',
        'cakupan_mk' => 'bersama_fakultas',
        'fakultas_id' => $fakultas->id,
        'program_studi_id' => null,
    ]);

    $ruangBersama = RuangPraktikum::factory()->create([
        'nama_ruang' => 'Laboratorium Peradilan',
        'cakupan_akses' => 'bersama_fakultas',
        'fakultas_id' => $fakultas->id,
    ]);

    $dosen = Dosen::factory()->create();

    Livewire::test(JadwalManage::class)
        ->call('openCreate')
        ->set('mata_kuliah_id', $mkBersama->id)
        ->set('kelas_id', $kelasHKI->id)
        ->set('dosen_id', $dosen->id)
        ->set('ruang_praktikum_id', $ruangBersama->id)
        ->set('tahun_akademik_id', $this->tahun->id)
        ->set('hari', 'Rabu')
        ->set('jam_mulai', '09:10')
        ->set('jam_selesai', '12:30')
        ->call('save')
        ->assertHasNoErrors();

    expect(JadwalPraktikum::where('mata_kuliah_id', $mkBersama->id)->where('ruang_praktikum_id', $ruangBersama->id)->exists())->toBeTrue();
});
