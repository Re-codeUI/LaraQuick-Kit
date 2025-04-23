<?php

namespace MagicBox\LaraQuickKit\Helpers;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Artisan;

class PermissionHelper
{
    /**
     * Buat role dan permission berdasarkan konfigurasi di laraquick.php
     */
    public static function setupPermissions()
    {
        $rolesConfig = Config::get('laraquick.roles', []);

        foreach ($rolesConfig as $roleKey => $roleData) {
            try {
                // Buat atau ambil role
                $role = Role::firstOrCreate(['name' => $roleKey]);
                Log::info("Role '$roleKey' berhasil dibuat atau sudah ada.");

                // Pastikan role memiliki permissions sebelum diproses
                if (!isset($roleData['permissions']) || !is_array($roleData['permissions'])) {
                    Log::warning("Role '$roleKey' tidak memiliki permissions yang terdaftar.");
                    continue;
                }

                foreach ($roleData['permissions'] as $permissionName) {
                    // Buat atau ambil permission
                    $permission = Permission::firstOrCreate(['name' => $permissionName]);
                    Log::info("Permission '$permissionName' berhasil dibuat atau sudah ada.");

                    // Berikan permission ke role jika belum ada
                    if (!$role->hasPermissionTo($permissionName)) {
                        $role->givePermissionTo($permissionName);
                        Log::info("Permission '$permissionName' diberikan ke role '$roleKey'.");
                    }
                }
            } catch (\Exception $e) {
                Log::error("Error saat setup permissions untuk role '$roleKey': " . $e->getMessage());
            }
        }

        // Bersihkan cache setelah setup permissions
        self::clearCache();
    }

    /**
     * Periksa apakah pengguna memiliki role tertentu
     */
    public static function userHasRole($user, $role)
    {
        return $user->hasRole($role);
    }

    /**
     * Periksa apakah pengguna memiliki permission tertentu
     */
    public static function userHasPermission($user, $permission)
    {
        return $user->hasPermissionTo($permission);
    }

    /**
     * Sinkronisasi ulang role dan permission dari konfigurasi
     */
    public static function syncPermissions()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        self::setupPermissions();
    }

    /**
     * Hapus cache permission untuk memastikan perubahan langsung diterapkan
     */
    public static function clearCache()
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
