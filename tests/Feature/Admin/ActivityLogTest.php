<?php

use App\Livewire\Admin\ActivityLogIndex;
use App\Models\Dosen;
use Database\Seeders\RolePermissionSeeder;
use Livewire\Livewire;
use Spatie\Activitylog\Models\Activity;

beforeEach(function () {
    $this->seed(RolePermissionSeeder::class);
    loginAsRole('super-admin');
});

test('bisa menampilkan riwayat log aktivitas', function () {
    $dosen = Dosen::factory()->create(['nama' => 'Dosen Log Test']);

    Livewire::test(ActivityLogIndex::class)
        ->assertSee('Riwayat Aktivitas');
});

test('super admin bisa mengosongkan seluruh riwayat log aktivitas', function () {
    activity()->log('Log testing 1');
    activity()->log('Log testing 2');

    expect(Activity::count())->toBeGreaterThanOrEqual(2);

    Livewire::test(ActivityLogIndex::class)
        ->set('cleanDays', 0)
        ->call('cleanLogs')
        ->assertHasNoErrors();

    expect(Activity::count())->toBe(0);
});
