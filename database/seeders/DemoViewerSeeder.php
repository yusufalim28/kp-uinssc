<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class DemoViewerSeeder extends Seeder
{
    /**
     * Buat role & akun demonstrasi (hanya baca / read-only) untuk keperluan pameran.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $viewPermission = Permission::firstOrCreate(['name' => 'view-jadwal']);
        $roleDemo = Role::firstOrCreate(['name' => 'demo-viewer']);
        $roleDemo->syncPermissions([$viewPermission]);

        $demoUser = User::updateOrCreate(
            ['email' => 'demo@uinssc.ac.id'],
            [
                'name' => 'Pengunjung Demo (Read-Only)',
                'password' => Hash::make('demo1234'),
                'email_verified_at' => now(),
                'fakultas_id' => null,
                'program_studi_id' => null,
                'ruang_praktikum_id' => null,
            ]
        );

        $demoUser->syncRoles([$roleDemo]);
    }
}
