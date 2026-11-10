<?php

use App\Models\Dosen;
use App\Models\Fakultas;
use App\Models\User;
use Spatie\Permission\Models\Role;

test('guests are redirected to the login page', function () {
    $response = $this->get(route('dashboard'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can visit the dashboard', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('dashboard'));
    $response->assertOk();
});

test('super admin sees super admin dashboard', function () {
    Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole('super-admin');

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Dashboard Eksekutif Universitas')
        ->assertSee('Distribusi Fasilitas', false);
});

test('operator sees faculty operator dashboard', function () {
    Role::firstOrCreate(['name' => 'operator', 'guard_name' => 'web']);
    $fakultas = Fakultas::firstOrCreate(
        ['kode_fakultas' => 'FITK'],
        ['nama_fakultas' => 'Fakultas Ilmu Tarbiyah dan Keguruan', 'status' => 'aktif']
    );
    $user = User::factory()->create(['fakultas_id' => $fakultas->id]);
    $user->assignRole('operator');

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Dashboard Operasional Fakultas FITK')
        ->assertSee('Utilisasi Lab FITK');
});

test('dosen sees lecturer dashboard', function () {
    Role::firstOrCreate(['name' => 'dosen', 'guard_name' => 'web']);
    $user = User::factory()->create();
    $user->assignRole('dosen');
    Dosen::firstOrCreate(
        ['nidn' => '0945198705'],
        ['user_id' => $user->id, 'nama' => $user->name, 'email' => $user->email, 'status' => 'aktif']
    );

    $this->actingAs($user)
        ->get(route('dashboard'))
        ->assertOk()
        ->assertSee('Dashboard Dosen Pengampu')
        ->assertSee('Jadwal Praktikum Saya');
});
