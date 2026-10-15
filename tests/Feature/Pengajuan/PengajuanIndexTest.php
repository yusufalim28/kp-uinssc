<?php

use App\Livewire\Admin\PengajuanIndex;
use App\Models\Dosen;
use App\Models\JadwalPraktikum;
use App\Models\PengajuanPerubahan;
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
