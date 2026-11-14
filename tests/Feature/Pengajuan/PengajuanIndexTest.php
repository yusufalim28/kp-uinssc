<?php

use App\Livewire\Admin\PengajuanIndex;
use App\Models\Dosen;
use App\Models\Fakultas;
use App\Models\JadwalPraktikum;
use App\Models\PengajuanPerubahan;
use App\Models\RuangPraktikum;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
    loginAsRole('operator');

    $dosen = Dosen::factory()->create();
    $jadwal = JadwalPraktikum::factory()->create(['dosen_id' => $dosen->id]);

    $this->pengajuan = PengajuanPerubahan::create([
        'jadwal_praktikum_id' => $jadwal->id,
        'dosen_id' => $dosen->id,
        'perubahan' => 'Pindah ke Rabu',
        'alasan' => 'Bentrok jadwal lain',
        'status' => 'menunggu',
    ]);
});

test('operator bisa menyetujui pengajuan', function () {
    Livewire::test(PengajuanIndex::class)
        ->call('openApprove', $this->pengajuan->id)
        ->call('confirmProcess');

    expect($this->pengajuan->fresh()->status)->toBe('disetujui');
});

test('operator bisa menolak pengajuan dengan catatan', function () {
    Livewire::test(PengajuanIndex::class)
        ->call('openReject', $this->pengajuan->id)
        ->set('catatan_penolakan', 'Ruang pengganti tidak tersedia')
        ->call('confirmProcess');

    $fresh = $this->pengajuan->fresh();
    expect($fresh->status)->toBe('ditolak');
    expect($fresh->catatan_penolakan)->toBe('Ruang pengganti tidak tersedia');
});

test('menolak tanpa catatan ditolak oleh validasi', function () {
    Livewire::test(PengajuanIndex::class)
        ->call('openReject', $this->pengajuan->id)
        ->set('catatan_penolakan', '')
        ->call('confirmProcess')
        ->assertHasErrors(['catatan_penolakan']);

    expect($this->pengajuan->fresh()->status)->toBe('menunggu');
});

test('filter status hanya menampilkan pengajuan sesuai status dipilih', function () {
    $disetujui = PengajuanPerubahan::create([
        'jadwal_praktikum_id' => $this->pengajuan->jadwal_praktikum_id,
        'dosen_id' => $this->pengajuan->dosen_id,
        'perubahan' => 'Sudah beres',
        'alasan' => 'Contoh',
        'status' => 'disetujui',
    ]);

    Livewire::test(PengajuanIndex::class)
        ->set('filterStatus', 'disetujui')
        ->assertSee('Sudah beres')
        ->assertDontSee('Pindah ke Rabu');
});

test('operator fakultas lain tidak dapat menyetujui pengajuan fakultas berbeda', function () {
    $fakultasA = Fakultas::factory()->create();
    $fakultasB = Fakultas::factory()->create();

    $operatorB = User::factory()->create(['fakultas_id' => $fakultasB->id]);
    $operatorB->assignRole('operator');

    $ruangA = RuangPraktikum::factory()->create(['fakultas_id' => $fakultasA->id]);
    $jadwalA = JadwalPraktikum::factory()->create(['ruang_praktikum_id' => $ruangA->id]);
    $dosen = Dosen::factory()->create();

    $pengajuanA = PengajuanPerubahan::create([
        'jadwal_praktikum_id' => $jadwalA->id,
        'dosen_id' => $dosen->id,
        'perubahan' => 'Pindah Jam',
        'alasan' => 'Ada acara',
        'status' => 'menunggu',
    ]);

    test()->actingAs($operatorB);

    Livewire::test(PengajuanIndex::class)
        ->call('openApprove', $pengajuanA->id)
        ->call('confirmProcess')
        ->assertForbidden();

    expect($pengajuanA->fresh()->status)->toBe('menunggu');
});

test('ketua lab hanya bisa menyetujui pengajuan pada laboratorium miliknya', function () {
    $ruang1 = RuangPraktikum::factory()->create();
    $ruang2 = RuangPraktikum::factory()->create();

    $ketuaLab1 = User::factory()->create(['ruang_praktikum_id' => $ruang1->id]);
    $ketuaLab1->assignRole('ketua-lab');

    $jadwal1 = JadwalPraktikum::factory()->create(['ruang_praktikum_id' => $ruang1->id]);
    $jadwal2 = JadwalPraktikum::factory()->create(['ruang_praktikum_id' => $ruang2->id]);
    $dosen = Dosen::factory()->create();

    $pengajuan1 = PengajuanPerubahan::create([
        'jadwal_praktikum_id' => $jadwal1->id,
        'dosen_id' => $dosen->id,
        'perubahan' => 'Pindah sesi lab 1',
        'alasan' => 'Kuliah pengganti',
        'status' => 'menunggu',
    ]);

    $pengajuan2 = PengajuanPerubahan::create([
        'jadwal_praktikum_id' => $jadwal2->id,
        'dosen_id' => $dosen->id,
        'perubahan' => 'Pindah sesi lab 2',
        'alasan' => 'Kuliah pengganti',
        'status' => 'menunggu',
    ]);

    test()->actingAs($ketuaLab1);

    // Pengajuan lab sendiri -> Berhasil disetujui
    Livewire::test(PengajuanIndex::class)
        ->call('openApprove', $pengajuan1->id)
        ->call('confirmProcess')
        ->assertHasNoErrors();

    expect($pengajuan1->fresh()->status)->toBe('disetujui');

    // Pengajuan lab lain -> 403 Forbidden
    Livewire::test(PengajuanIndex::class)
        ->call('openApprove', $pengajuan2->id)
        ->call('confirmProcess')
        ->assertForbidden();

    expect($pengajuan2->fresh()->status)->toBe('menunggu');
});
