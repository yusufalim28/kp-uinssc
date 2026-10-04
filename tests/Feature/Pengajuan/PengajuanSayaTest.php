<?php

use App\Livewire\Dosen\PengajuanSaya;
use App\Models\Dosen;
use App\Models\JadwalPraktikum;
use App\Models\PengajuanPerubahan;
use App\Models\User;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(\Database\Seeders\RolePermissionSeeder::class);

    $this->user  = User::factory()->create();
    $this->user->assignRole('dosen');
    $this->dosen = Dosen::factory()->create(['user_id' => $this->user->id]);
    $this->actingAs($this->user);

    $this->jadwal = JadwalPraktikum::factory()->create(['dosen_id' => $this->dosen->id]);
});

test('dosen bisa mengajukan perubahan untuk jadwalnya sendiri', function () {
    Livewire::test(PengajuanSaya::class)
        ->call('openCreate')
        ->set('jadwal_praktikum_id', $this->jadwal->id)
        ->set('perubahan', 'Pindah ke Selasa 13:00-15:00')
        ->set('alasan', 'Bentrok dengan jadwal mengajar lain')
        ->call('save');

    expect(PengajuanPerubahan::where('dosen_id', $this->dosen->id)->count())->toBe(1);
    expect(PengajuanPerubahan::first()->status)->toBe('menunggu');
});

test('alasan wajib diisi', function () {
    Livewire::test(PengajuanSaya::class)
        ->call('openCreate')
        ->set('jadwal_praktikum_id', $this->jadwal->id)
        ->set('perubahan', 'Pindah jam')
        ->set('alasan', '')
        ->call('save')
        ->assertHasErrors(['alasan']);
});

test('dosen tanpa akun tertaut mendapat pesan error, bukan crash', function () {
    $userLain = User::factory()->create();
    $userLain->assignRole('dosen');
    $this->actingAs($userLain); // user ini TIDAK punya baris Dosen terkait

    Livewire::test(PengajuanSaya::class)
        ->assertSet('tidakAdaProfilDosen', true);
});

test('dosen hanya melihat riwayat pengajuannya sendiri, bukan milik dosen lain', function () {
    $dosenLain = Dosen::factory()->create();
    $jadwalLain = JadwalPraktikum::factory()->create(['dosen_id' => $dosenLain->id]);

    PengajuanPerubahan::create([
        'jadwal_praktikum_id' => $jadwalLain->id,
        'dosen_id'            => $dosenLain->id,
        'perubahan'           => 'Punya dosen lain',
        'alasan'              => 'Tidak relevan',
        'status'              => 'menunggu',
    ]);

    Livewire::test(PengajuanSaya::class)
        ->assertDontSee('Punya dosen lain');
});
