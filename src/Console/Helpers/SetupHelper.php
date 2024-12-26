<?php

namespace Magicbox\LaraQuickKit\Console\Helpers;

use Magicbox\LaraQuickKit\Models\User;
use Illuminate\Support\Facades\File;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;

class SetupHelper
{
    public static function displayMagicBanner()
    {
        echo PHP_EOL;
        echo '*********************************************' . PHP_EOL;
        echo '*                                           *' . PHP_EOL;
        echo '*               🎩 MAGIC BOX                *' . PHP_EOL;
        echo '*               ┌───────────┐               *' . PHP_EOL;
        echo '*               │  WELCOME  │               *' . PHP_EOL;
        echo '*               │    TO     │               *' . PHP_EOL;
        echo '*               │   MAGIC!  │               *' . PHP_EOL;
        echo '*               └───────────┘               *' . PHP_EOL;
        echo '*                                           *' . PHP_EOL;
        echo '*********************************************' . PHP_EOL;
        echo PHP_EOL;
    }

    public static function createModuleRolesAndPermissions($module)
    {
        echo "Creating roles and permissions for the $module module..." . PHP_EOL;

        $rolesAndPermissions = self::getModuleRolesAndPermissions($module);

        foreach ($rolesAndPermissions as $role => $permissions) {
            $roleInstance = Role::firstOrCreate(
                ['name' => $role],
                ['guard_name' => 'web'] // Tambahkan guard_name
            );

            foreach ($permissions as $permission) {
                $permissionInstance = Permission::firstOrCreate(
                    ['name' => $permission],
                    ['guard_name' => 'web'] // Tambahkan guard_name
                );
                $roleInstance->givePermissionTo($permissionInstance);
            }
        }

        echo "$module roles and permissions created." . PHP_EOL;
    }


    public static function createDefaultUsers()
    {
        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'Admin',
            ],
            [
                'name' => 'User',
                'email' => 'user@example.com',
                'password' => Hash::make('password'),
                'role' => 'User',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => $userData['password'],
            ]);

            // Menetapkan role dengan guard_name
            $user->assignRole($userData['role'], 'web'); // Tambahkan guard_name di sini
        }

        echo "Default users created." . PHP_EOL;
    }

    public static function getDefaultUserCredentials()
    {
        return [
            ['Admin', 'admin@example.com', 'password', 'Admin'],
            ['User', 'user@example.com', 'password', 'User'],
        ];
    }

    public static function getModuleRolesAndPermissions($module)
    {
        $modulesConfig = [
            'Inventory' => [
                'Admin' => ['inventory.view', 'inventory.manage'],
                'Manager' => ['inventory.view'],
                'Staff' => ['inventory.add', 'inventory.update'],
            ],
            'Sales' => [
                'Admin' => ['sales.view', 'sales.manage'],
                'Salesperson' => ['sales.add', 'sales.update'],
            ],
            'CRM' => [
                'Admin' => ['crm.view', 'crm.manage'],
                'Agent' => ['crm.view', 'crm.update'],
            ],
        ];

        return $modulesConfig[$module] ?? [];
    }

    public static function displayThankYouCharacter()
    {
        echo PHP_EOL;
        echo '*********************************************' . PHP_EOL;
        echo '*                                           *' . PHP_EOL;
        echo '*  🎩 Thank you for using LaraQuickKit!     *' . PHP_EOL;
        echo '*  😊 We hope you enjoy our magic!          *' . PHP_EOL;
        echo '*                                           *' . PHP_EOL;
        echo '*       ┌──────┐                            *' . PHP_EOL;
        echo '*      (  °  °  )                           *' . PHP_EOL;
        echo '*       ╰──────╯                            *' . PHP_EOL;
        echo '*********************************************' . PHP_EOL;
        echo PHP_EOL;
    }
    public static function copyPermissionConfig()
    {
        $sourcePath = __DIR__ . '/../../config/permission.php'; // Path ke config package
        $destinationPath = config_path('permission.php'); // Path config Laravel Fresh

        if (file_exists($sourcePath)) {
            copy($sourcePath, $destinationPath);
            echo "File permission.php copied to Laravel config folder successfully.\n";
        } else {
            echo "File permission.php not found in the package.\n";
        }
    }


}
