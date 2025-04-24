<?php

namespace MagicBox\LaraQuickKit\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Console\Command;

class RolePermissionSeeder extends Seeder
{
    public function run(Command $command = null): void
    {
        $config = config('laraquick');
        $modules = collect($config['modules'])
            ->filter(fn($mod) => $mod['enabled'] === true)
            ->keys();

        $allRoles = $config['roles'];

        // 1. Buat Role admin_global
        $adminGlobal = Role::firstOrCreate(['name' => 'admin_global']);

        // 2. Kumpulkan permission dari semua modul yang aktif
        $globalPermissions = [];

        foreach ($allRoles as $roleKey => $roleData) {
            // Abaikan 'admin' karena bukan per modul
            if ($roleKey === 'admin') {
                continue;
            }

            // Dapatkan prefix modul dari key (e.g. 'inventory_manager' => 'inventory')
            $parts = explode('_', $roleKey);
            $modulePrefix = $parts[0];

            if ($modules->contains($modulePrefix)) {
                foreach ($roleData['permissions'] as $permissionName) {
                    $permission = Permission::firstOrCreate(['name' => $permissionName]);
                    $globalPermissions[] = $permission->name;
                }

                // Buat role per modul
                Role::firstOrCreate(['name' => $roleKey])
                    ->syncPermissions($roleData['permissions']);
            }
        }

        // Tambahkan permission dari role admin umum (bukan per modul)
        if (isset($allRoles['admin'])) {
            foreach ($allRoles['admin']['permissions'] as $perm) {
                $permission = Permission::firstOrCreate(['name' => $perm]);
                $globalPermissions[] = $permission->name;
            }
        }

        // Sync semua permission ke admin_global
        $adminGlobal->syncPermissions(array_unique($globalPermissions));

        if ($command) {
            $command->info('Role admin_global dan role per modul berhasil dibuat dengan permission yang sesuai.');
        }
    }
}
