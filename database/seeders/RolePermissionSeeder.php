<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $admin = Role::where('name', 'Admin')->first();
        $petugas = Role::where('name', 'Petugas')->first();
        $peminjam = Role::where('name', 'Peminjam')->first();

        if ($admin) {
            $admin->syncPermissions(Permission::all());
        }

        if ($petugas) {
            $petugas->syncPermissions([
                'alat.view',
                'alat.create',
                'alat.update',
                'kategori.view',
                'peminjaman.view',
                'peminjaman.create',
                'peminjaman.approve',
                'pengembalian.view',
                'pengembalian.create',
                'pengembalian.scan',
            ]);
        }

        if ($peminjam) {
            $peminjam->syncPermissions([
                'peminjaman.view',
                'peminjaman.create',
            ]);
        }
    }
}