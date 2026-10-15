<?php

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Laravel\Fortify\Features;

test('login screen can be rendered', function () {
    $response = $this->get(route('login'));

    $response->assertOk();
});

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    $response = $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticated();
});

test('unverified users can authenticate and access the dashboard', function () {
    $user = User::factory()->unverified()->create();

    $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ])
        ->assertSessionHasNoErrors()
        ->assertRedirect(route('dashboard', absolute: false));

    $this->assertAuthenticated();
    $this->get(route('dashboard'))->assertOk();
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $response = $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $response->assertSessionHasErrorsIn('email');

    $this->assertGuest();
});

test('users with two factor enabled are redirected to two factor challenge', function () {
    $this->skipUnlessFortifyHas(Features::twoFactorAuthentication());

    Features::twoFactorAuthentication([
        'confirm' => true,
        'confirmPassword' => true,
    ]);

    $user = User::factory()->withTwoFactor()->create();

    $response = $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    $response->assertRedirect(route('two-factor.login'));
    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('logout'));

    $response->assertRedirect(route('home'));

    $this->assertGuest();
});

test('student NIM cannot authenticate into portal', function () {
    $response = $this->post(route('login.store'), [
        'email' => '210101001',
        'password' => 'password123',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('dosen can authenticate via bridge api v2', function () {
    config(['services.pustikom_bridge.enabled' => true]);
    config(['services.pustikom_bridge.base_url' => 'https://bridge.ecampuz.com']);

    Http::fake([
        'https://bridge.ecampuz.com/api/v2/portal/login' => Http::response([
            'status' => 'success',
            'data' => [
                'token' => 'mock-jwt-token-12345',
            ],
        ], 200),
        'https://bridge.ecampuz.com/api/v2/profil' => Http::response([
            'status' => 'success',
            'data' => [
                'nama' => 'Dr. Ahmad Fauzi, M.Kom',
                'nip' => '198501012010121001',
                'email' => 'ahmad.fauzi@uinssc.ac.id',
                'role' => 'dosen',
            ],
        ], 200),
    ]);

    $response = $this->post(route('login.store'), [
        'email' => '198501012010121001',
        'password' => 'password-rahasia',
    ]);

    $response->assertSessionHasNoErrors();
    $this->assertAuthenticated();
});

test('mahasiswa is blocked even if bridge api returns success', function () {
    config(['services.pustikom_bridge.enabled' => true]);
    config(['services.pustikom_bridge.base_url' => 'https://bridge.ecampuz.com']);

    Http::fake([
        'https://bridge.ecampuz.com/api/v2/portal/login' => Http::response([
            'data' => ['token' => 'mock-student-token'],
        ], 200),
        'https://bridge.ecampuz.com/api/v2/profil' => Http::response([
            'data' => [
                'nama' => 'Budi Santoso',
                'nim' => '210101001',
                'nip' => null,
                'role' => 'mahasiswa',
            ],
        ], 200),
    ]);

    $response = $this->post(route('login.store'), [
        'email' => '210101001',
        'password' => 'password-mahasiswa',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});
