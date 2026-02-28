<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
        ]);

        $adminUser = User::updateOrCreate(
            ['email' => 'admin@admin.com'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('admin123'),
            ]
        );
        $adminUser->assignRole('Admin');

        $petugasUser = User::updateOrCreate(
            ['email' => 'petugas@admin.com'],
            [
                'name' => 'Petugas User',
                'password' => bcrypt('password123'),
            ]
        );
        $petugasUser->assignRole('Petugas');

        $peminjamUser = User::updateOrCreate(
            ['email' => 'peminjam@admin.com'],
            [
                'name' => 'Peminjam User',
                'password' => bcrypt('password123'),
            ]
        );
        $peminjamUser->assignRole('Peminjam');
    }
}