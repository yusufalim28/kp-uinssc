<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Membuat Akun Super Admin
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@mail.com',
            'password' => Hash::make('password'), // Password login: password
        ]);
        // Menempelkan role 'super-admin' yang sudah dibuat di RolePermissionSeeder
        $superAdmin->assignRole('super-admin');

        // 2. Membuat Akun Operator
        $operator = User::create([
            'name' => 'Operator Fakultas',
            'email' => 'operator@mail.com',
            'password' => Hash::make('password'),
        ]);
        $operator->assignRole('operator');

        // 3. Membuat Akun Dosen
        $dosen = User::create([
            'name' => 'Bapak Dosen, M.Kom.',
            'email' => 'dosen@mail.com',
            'password' => Hash::make('password'),
        ]);
        $dosen->assignRole('dosen');
    }
}
