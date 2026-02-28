<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [

            // MASTER DATA
            'alat.view',
            'alat.create',
            'alat.update',
            'alat.delete',

            'kategori.view',
            'kategori.create',
            'kategori.update',
            'kategori.delete',

            // TRANSAKSI
            'peminjaman.view',
            'peminjaman.create',
            'peminjaman.update',
            'peminjaman.delete',
            'peminjaman.approve',

            'pengembalian.view',
            'pengembalian.create',
            'pengembalian.update',
            'pengembalian.delete',
            'pengembalian.scan',

            // USERS MANAGEMENT
            'users.view',
            'users.create',
            'users.update',
            'users.delete',

            'role.view',
            'role.create',
            'role.update',
            'role.delete',

            'permission.view',
            'permission.create',
            'permission.update',
            'permission.delete',

            // LAPORAN
            'laporan.peminjaman',
            'laporan.pengembalian',

            // LOG
            'log.view',

            // PENGATURAN
            'pengaturan.update',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'api',
            ]);
        }
    }
}