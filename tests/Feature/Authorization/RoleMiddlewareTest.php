<?php

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
});

test('tamu (belum login) diarahkan ke halaman login saat akses master data', function () {
    $this->get('/master/fakultas')->assertRedirect('/login');
});

test('dosen tidak bisa akses master data (403)', function () {
    loginAsRole('dosen');

    $this->get('/master/fakultas')->assertForbidden();
});

test('operator bisa akses master data', function () {
    loginAsRole('operator');

    $this->get('/master/fakultas')->assertOk();
});

test('super-admin bisa akses master data', function () {
    loginAsRole('super-admin');

    $this->get('/master/fakultas')->assertOk();
});

test('super-admin dan operator tidak bisa akses halaman pengajuan-saya milik dosen', function () {
    loginAsRole('super-admin');

    $this->get('/pengajuan-saya')->assertForbidden();
});

test('dosen bisa akses pengajuan-saya', function () {
    loginAsRole('dosen');

    $this->get('/pengajuan-saya')->assertOk();
});

test('user tanpa role sama sekali ditolak di semua area terproteksi', function () {
    $user = User::factory()->create(); // tidak di-assignRole()
    $this->actingAs($user);

    $this->get('/master/fakultas')->assertForbidden();
    $this->get('/jadwal-manage')->assertForbidden();
    $this->get('/pengajuan')->assertForbidden();
    $this->get('/pengajuan-saya')->assertForbidden();
});
