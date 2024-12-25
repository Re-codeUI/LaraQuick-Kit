<?php

namespace Magicbox\LaraQuickKit\Console\Commands;

use Illuminate\Console\Command;
use Magicbox\LaraQuickKit\Console\Helpers\SetupHelper;

class SetupCommand extends Command
{
    protected $signature = 'laraquick:setup';
    protected $description = 'Interactive Setup For LaraQuick';

    public function handle()
    {
        // Step 1: Banner awal
        SetupHelper::displayMagicBanner();

        $this->info("Let's configure your Laravel application with LaraQuick!");

        // Step 2: Pilih Modul
        $modules = $this->choice(
            'Which modules would you like to enable?',
            ['Inventory', 'Sales', 'CRM'],
            0,
            null,
            true
        );
        $this->info('Selected Modules: ' . implode(', ', $modules));

        // Step 3: Pilih Framework UI
        $uiFramework = $this->choice(
            'Which UI framework would you like to use?',
            ['Bootstrap', 'Tailwind', 'Vue.js'],
            0
        );
        SetupHelper::installFrameworkUI($uiFramework);

        // Step 4: Setup Login System
        $this->info('Setting up custom authentication system...');
        Artisan::call('vendor:publish', ['--provider' => 'Spatie\Permission\PermissionServiceProvider']);
        Artisan::call('migrate:fresh');
        $this->info('Custom authentication system set up!');

        // Step 5: Seeders untuk Login
        $this->info('Seeding roles, permissions, and default users...');
        foreach ($modules as $module) {
            SetupHelper::createModuleRoles($module);
        }
        SetupHelper::createDefaultUsers();

        // Step 6: Menampilkan kredensial login
        $this->info('Here are the login credentials for default users:');
        $this->table(
            ['Name', 'Email', 'Password', 'Role'],
            [
                ['Admin', 'admin@example.com', 'password', 'Admin'],
                ['User', 'user@example.com', 'password', 'User'],
            ]
        );

        // Step 7: Karakter di akhir setup
        SetupHelper::displayThankYouCharacter();
    }
}
