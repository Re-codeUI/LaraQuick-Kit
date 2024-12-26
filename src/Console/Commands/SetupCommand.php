<?php
namespace Magicbox\LaraQuickKit\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Magicbox\LaraQuickKit\Console\Helpers\SetupHelper;

class SetupCommand extends Command
{
    protected $signature = 'laraquick:setup';
    protected $description = 'Interactive Setup for LaraQuick';

    public function handle()
    {
        // Step 1: Display Banner
        SetupHelper::displayMagicBanner();

        $this->info("Welcome to LaraQuickKit setup!");

        // Step 2: Select Modules
        $modules = $this->choice(
            'Which modules would you like to enable?',
            ['Inventory', 'Sales', 'CRM'],
            0,
            null,
            true
        );
        $this->info('Selected Modules: ' . implode(', ', $modules));

        // Step 3: Setup Login System
        $this->info('Setting up authentication system...');
        Artisan::call('vendor:publish', ['--provider' => 'Spatie\Permission\PermissionServiceProvider']);
        Artisan::call('migrate:fresh');
        $this->info('Authentication system set up successfully!');
        
        // Step 4: Replace permission from package to config/ fresh projeck

        $this->info('Replacing Spatie permission config file...');
        SetupHelper::copyPermissionConfig();
        
        // Step 5: Seed Roles and Permissions
        $this->info('Seeding roles, permissions, and default users...');
        foreach ($modules as $module) {
            SetupHelper::createModuleRolesAndPermissions($module);
        }
        SetupHelper::createDefaultUsers();

        // Step 6: Display Login Credentials
        $this->info('Here are the login credentials for the default users:');
        $this->table(
            ['Name', 'Email', 'Password', 'Role'],
            SetupHelper::getDefaultUserCredentials()
        );

        // Step 7: Display Thank You Character
        SetupHelper::displayThankYouCharacter();
    }
}
