<?php

namespace MagicBox\LaraQuickKit\Console\Commands;

use Illuminate\Console\Command;
use MagicBox\LaraQuickKit\Database\Seeders\RolePermissionSeeder;

class SetupRolePermissionCommand extends Command
{
    protected $signature = 'laraquick:setup-roles-permissions';
    protected $description = 'Setup roles and permissions for the application based on the configuration.';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Setting up roles and permissions...');

        // Jalankan seeder RolePermissionSeeder
        $seeder = new RolePermissionSeeder();
        $seeder->run($this);

        $this->info('Roles and permissions have been successfully set up.');
    }
}
