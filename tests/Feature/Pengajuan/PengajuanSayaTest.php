<?php

use App\Livewire\Dosen\PengajuanSaya;
use App\Models\Dosen;
use App\Models\JadwalPraktikum;
use App\Models\PengajuanPerubahan;
use App\Models\RuangPraktikum;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);

    $this->user = User::factory()->create();
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
        'dosen_id' => $dosenLain->id,
        'perubahan' => 'Punya dosen lain',
        'alasan' => 'Tidak relevan',
        'status' => 'menunggu',
    ]);

    Livewire::test(PengajuanSaya::class)
        ->assertDontSee('Punya dosen lain');
});

test('dosen bisa memindahkan jadwal langsung secara instan jika slot bebas bentrok', function () {
    $ruangBaru = RuangPraktikum::factory()->create();

    Livewire::test(PengajuanSaya::class)
        ->call('openCreate')
        ->set('jadwal_praktikum_id', $this->jadwal->id)
        ->set('jenis_perubahan', 'ganti_jadwal_ruang')
        ->set('hari_baru', 'Selasa')
        ->set('jam_mulai_baru', '13:00')
        ->set('jam_selesai_baru', '15:30')
        ->set('ruang_praktikum_id_baru', $ruangBaru->id)
        ->set('alasan', 'Ada workshop universitas')
        ->assertSet('isSlotAvailable', true)
        ->call('rescheduleInstant');

    $this->jadwal->refresh();
    expect($this->jadwal->hari)->toBe('Selasa');
    expect($this->jadwal->ruang_praktikum_id)->toBe($ruangBaru->id);
    expect($this->jadwal->status)->toBe('reschedule');

    $pengajuan = PengajuanPerubahan::where('jadwal_praktikum_id', $this->jadwal->id)->latest()->first();
    expect($pengajuan)->not->toBeNull();
    expect($pengajuan->status)->toBe('disetujui');
});

test('dosen ditolak memindahkan instan jika ruangan bentrok dengan jadwal lain', function () {
    $ruangBentrok = RuangPraktikum::factory()->create();

    // Jadwal lain di ruang & waktu yang sama
    JadwalPraktikum::factory()->create([
        'ruang_praktikum_id' => $ruangBentrok->id,
        'tahun_akademik_id' => $this->jadwal->tahun_akademik_id,
        'hari' => 'Rabu',
        'jam_mulai' => '08:00',
        'jam_selesai' => '10:30',
        'status' => 'aktif',
    ]);

    Livewire::test(PengajuanSaya::class)
        ->call('openCreate')
        ->set('jadwal_praktikum_id', $this->jadwal->id)
        ->set('jenis_perubahan', 'ganti_jadwal_ruang')
        ->set('hari_baru', 'Rabu')
        ->set('jam_mulai_baru', '08:00')
        ->set('jam_selesai_baru', '10:30')
        ->set('ruang_praktikum_id_baru', $ruangBentrok->id)
        ->set('alasan', 'Coba pindah ke slot yang bentrok')
        ->assertSet('isSlotAvailable', false)
        ->assertSet('conflictMessage', fn ($msg) => filled($msg));
});
