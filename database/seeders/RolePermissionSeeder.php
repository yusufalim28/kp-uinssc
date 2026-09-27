<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // Buat Permissions
        $permissions = [
            'manage-master-data',
            'manage-jadwal',
            'approve-pengajuan',
            'submit-pengajuan',
            'view-jadwal',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Buat Roles dan Assign Permissions
        $roleSuperAdmin = Role::create(['name' => 'super-admin']);
        $roleSuperAdmin->givePermissionTo(Permission::all());

        $roleOperator = Role::create(['name' => 'operator']);
        $roleOperator->givePermissionTo(['manage-master-data', 'manage-jadwal', 'approve-pengajuan', 'view-jadwal']);

        $roleDosen = Role::create(['name' => 'dosen']);
        $roleDosen->givePermissionTo(['submit-pengajuan', 'view-jadwal']);
    }
}
