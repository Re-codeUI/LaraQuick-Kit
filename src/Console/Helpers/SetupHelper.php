<?php

namespace Magicbox\LaraQuickKit\Console\Helpers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

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

    public static function installUIFramework($uiFramework)
    {
        if ($uiFramework === 'Bootstrap') {
            echo "Installing Bootstrap..." . PHP_EOL;
            exec('composer require laravel/ui');
            exec('php artisan ui bootstrap');
            exec('npm install && npm run dev');
            self::customizeAuthViews('bootstrap');
            echo "Bootstrap installed." . PHP_EOL;
        } elseif ($uiFramework === 'Tailwind') {
            echo "Installing Tailwind CSS..." . PHP_EOL;
            exec('npm install -D tailwindcss postcss autoprefixer');
            exec('npx tailwindcss init');
            self::customizeAuthViews('tailwind');
            echo "Tailwind CSS installed." . PHP_EOL;
        } elseif ($uiFramework === 'Vue.js') {
            echo "Installing Vue.js..." . PHP_EOL;
            exec('composer require laravel/ui');
            exec('php artisan ui vue');
            exec('npm install && npm run dev');
            self::customizeAuthViews('vue');
            echo "Vue.js installed." . PHP_EOL;
        }
    }
    private static function customizeAuthViews($framework)
    {
        $authViewsPath = resource_path("views/auth");
        File::deleteDirectory($authViewsPath);

        $customAuthPath = __DIR__ . "/../Resources/views/auth/$framework";
        File::copyDirectory($customAuthPath, $authViewsPath);

        echo ucfirst($framework) . " custom auth views integrated." . PHP_EOL;
    }
    public static function integrateSpatieToUserModel()
    {
        $userModelPath = app_path('Models/User.php');
        if (file_exists($userModelPath)) {
            $userModelContent = file_get_contents($userModelPath);
            if (!str_contains($userModelContent, 'use Spatie\Permission\Traits\HasRoles;')) {
                $updatedContent = str_replace(
                    'use Illuminate\Notifications\Notifiable;',
                    "use Illuminate\Notifications\Notifiable;\nuse Spatie\Permission\Traits\HasRoles;",
                    $userModelContent
                );

                $updatedContent = str_replace(
                    'use Notifiable;',
                    'use Notifiable, HasRoles;',
                    $updatedContent
                );

                file_put_contents($userModelPath, $updatedContent);
            }
        }
    }

    public static function createModuleRoles($module)
    {
        echo "Creating roles and permissions for $module module..." . PHP_EOL;
        $roleName = $module . ' Manager';
        $role = Role::create(['name' => $roleName]);
        $permission = Permission::create(['name' => strtolower($module) . '.manage']);
        $role->givePermissionTo($permission);
        echo "$module roles and permissions created." . PHP_EOL;
    }

    public static function createDefaultUsers()
    {
        \App\Models\User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ])->assignRole('Admin');

        \App\Models\User::factory()->create([
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => bcrypt('password'),
        ])->assignRole('User');
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
}
