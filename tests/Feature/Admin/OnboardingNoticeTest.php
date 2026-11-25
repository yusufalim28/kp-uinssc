<?php

use App\Livewire\Admin\OnboardingNoticeModal;
use App\Livewire\Admin\UserManage;
use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Livewire\Livewire;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
});

test('modal peringatan muncul saat operator login pertama kali', function () {
    $operator = User::factory()->create();
    $operator->assignRole('operator');

    expect($operator->shouldSeeOnboardingNotice())->toBeTrue();

    Livewire::actingAs($operator)
        ->test(OnboardingNoticeModal::class)
        ->assertSet('show', true)
        ->assertSee('Wajib Diperiksa Saat Pertama Kali Login', false)
        ->assertSee('NIDN', false);
});

test('modal peringatan tidak muncul lagi setelah ditutup oleh operator', function () {
    $operator = User::factory()->create();
    $operator->assignRole('operator');

    Livewire::actingAs($operator)
        ->test(OnboardingNoticeModal::class)
        ->assertSet('show', true)
        ->call('dismiss')
        ->assertSet('show', false);

    $operator->refresh();
    expect($operator->shouldSeeOnboardingNotice())->toBeFalse();

    Livewire::actingAs($operator)
        ->test(OnboardingNoticeModal::class)
        ->assertSet('show', false);
});

test('super admin bisa me-reset dan menampilkan kembali peringatan ke operator', function () {
    $superAdmin = User::factory()->create();
    $superAdmin->assignRole('super-admin');

    $operator = User::factory()->create([
        'onboarding_notice_version' => 1,
        'onboarding_notice_dismissed_at' => now(),
    ]);
    $operator->assignRole('operator');

    expect($operator->shouldSeeOnboardingNotice())->toBeFalse();

    // Super admin me-reset pengumuman
    Livewire::actingAs($superAdmin)
        ->test(UserManage::class)
        ->call('broadcastNoticeAgain')
        ->assertHasNoErrors();

    $operator->refresh();
    expect($operator->shouldSeeOnboardingNotice())->toBeTrue();

    // Sekarang operator melihat peringatan kembali 1x
    Livewire::actingAs($operator)
        ->test(OnboardingNoticeModal::class)
        ->assertSet('show', true);
});
