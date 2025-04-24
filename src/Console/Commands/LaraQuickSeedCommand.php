<?php 
namespace MagicBox\LaraQuickKit\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use MagicBox\LaraQuickKit\Database\Seeders\RolePermissionSeeder;

class LaraQuickSeedCommand extends Command
{
    protected $signature = 'laraquick:seed {--class= : Seeder class to run (default: RolePermissionSeeder)}';
    protected $description = 'Run seeders from the LaraQuickKit package';

    public function handle()
    {
        $class = $this->option('class') ?: RolePermissionSeeder::class;

        if (!class_exists($class)) {
            $this->error("Seeder class {$class} not found.");
            return;
        }

        $this->info("Running seeder: {$class}");

        // Jalankan seeder dan kirimkan instance command
        App::make($class)->run($this);
    }
}