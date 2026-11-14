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
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Buat Roles dan Assign Permissions
        $roleSuperAdmin = Role::firstOrCreate(['name' => 'super-admin']);
        $roleSuperAdmin->givePermissionTo(Permission::all());

        $roleOperator = Role::firstOrCreate(['name' => 'operator']);
        $roleOperator->givePermissionTo(['manage-master-data', 'manage-jadwal', 'approve-pengajuan', 'view-jadwal']);

        $roleKetuaLab = Role::firstOrCreate(['name' => 'ketua-lab']);
        $roleKetuaLab->givePermissionTo(['manage-jadwal', 'approve-pengajuan', 'view-jadwal']);

        $roleDosen = Role::firstOrCreate(['name' => 'dosen']);
        $roleDosen->givePermissionTo(['submit-pengajuan', 'view-jadwal']);
    }
}
