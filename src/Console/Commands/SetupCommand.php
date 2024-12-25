<?php

namespace Magicbox\LaraQuick\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class SetupCommand extends Command {
    
    protected $signature = 'laraquick:setup';
    protected $description = 'Interactive Setup For LaraQuick';

    public function handle() {
        $this->info('Welcome to LaraQuick Setup!');

        // Step 1: Pilih Modul
        $modules = $this->choice(
            'Which modules would you like to enable?',
            ['Inventory', 'Sales', 'CRM', 'None'],
            0,
            null,
            true
        );

        $this->info('Selected Modules: ' . implode(',', $modules));

        // Step 2: Pilih Framework UI
        $uiFramework = $this->choice(
            'Which UI framework would you like to use?',
            ['Bootstrap', 'Tailwind', 'Vue.js'],
            0
        );

        $this->info('Selected UI Framework: ' . $uiFramework);

        // Step 3: Pilih login system and roles
        $enableLogin = $this->confirm('Would you like to enable authentication with user roles?', true);

        if ($enableLogin) {
            $this->info('Configuring authentication system...');
            Artisan::call('make:auth'); // Membuat auth jika belum tersedia
            $this->info('Authentication system created!');

            // Pilih roles dan permissions
            if ($this->confirm('Would you like to set up roles and permissions?', true)) {
                $this->info('Setting up roles and permissions...');
                Artisan::call('vendor:publish', ['--provider' => 'Spatie\Permission\PermissionServiceProvider']);
                Artisan::call('migrate');
                $this->info('Roles and permissions have been set up!');
            }

            // Step 4: Pilih roles yang ingin ditambahkan
            $roles = $this->choice(
                'Choose the roles you want to create for the system (Admin, User, etc.)',
                ['Admin', 'User', 'Editor'],
                0,
                null,
                true
            );

            $this->info('Roles: ' . implode(',', $roles));
            $this->call('db:seed', ['--class' => 'RoleSeeder']);
        }

        // Step 5: Tambahkan data dummy
        $addDummyData = $this->confirm('Would you like to generate some dummy data?', true);

        if ($addDummyData) {
            $this->info('Generating dummy data...');
            Artisan::call('db:seed'); 
            $this->info('Dummy data generated!');
        }

        // Step 6: Membuat database structure
        $this->info('Setting up database structure...');

        if (in_array('Inventory', $modules)) {
            $this->call('migrate', ['--path' => 'database/migrations/inventory']);
        }

        if (in_array('Sales', $modules)) {
            $this->call('migrate', ['--path' => 'database/migrations/sales']);
        }

        if (in_array('CRM', $modules)) {
            $this->call('migrate', ['--path' => 'database/migrations/crm']);
        }

        if (in_array('Company Profile', $modules)) {
            $this->call('migrate', ['--path' => 'database/migrations/company']);
        }

        // Menyelesaikan proses setup
        $this->info('Setup Complete!');
    }
}
