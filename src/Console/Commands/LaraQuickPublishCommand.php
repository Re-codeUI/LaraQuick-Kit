<?php

namespace MagicBox\LaraQuickKit\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;

class LaraQuickPublishCommand extends Command
{
    protected $signature = 'laraquick:publish 
        {--force : Overwrite existing files} 
        {--views : Publish only views}
        {--config : Publish only config}
        {--migrations : Publish only migrations}
        {--all : Publish all (views, config, migrations)}';

    protected $description = 'Publish resources from the LaraQuickKit package to your Laravel app';

    protected Filesystem $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle(): void
    {
        if ($this->option('all') || $this->option('views')) {
            $this->publishViews();
        }

        if ($this->option('all') || $this->option('config')) {
            $this->publishConfig();
        }

        if ($this->option('all') || $this->option('migrations')) {
            $this->publishMigrations();
        }

        if (! $this->option('all') && ! $this->option('views') && ! $this->option('config') && ! $this->option('migrations')) {
            $this->warn('⚠️  No option selected. Use --all or specify what to publish (e.g. --views, --config, --migrations).');
        }
    }

    protected function publishViews(): void
    {
        $base = __DIR__ . '/../../Resources/views/';

        $this->copyDirectory($base . 'auth', resource_path('views/auth'), 'Views: Auth');
        $this->copyDirectory($base . 'layouts', resource_path('views/layouts'), 'Views: Layouts');
        $this->copyFile($base . 'home.blade.php', resource_path('views/home.blade.php'), 'Views: Home');
    }

    protected function publishConfig(): void
    {
        $this->copyFile(
            __DIR__ . '/../../Config/laraquick.php',
            config_path('laraquick.php'),
            'Config file'
        );
    }

    protected function publishMigrations(): void
    {
        $this->copyDirectory(
            __DIR__ . '/../../Database/Migrations/',
            database_path('migrations'),
            'Migrations'
        );
    }

    protected function copyFile(string $source, string $destination, string $label): void
    {
        if (! $this->files->exists($source)) {
            $this->warn("⚠️ Source {$label} not found.");
            return;
        }

        if ($this->files->exists($destination) && ! $this->option('force')) {
            $this->warn("⚠️ {$label} already exists. Use --force to overwrite.");
            return;
        }

        $this->makeDirectory(dirname($destination));
        $this->files->copy($source, $destination);
        $this->info("📄 Published: {$label}");
    }

    protected function copyDirectory(string $source, string $destination, string $label): void
    {
        if (! $this->files->isDirectory($source)) {
            $this->warn("⚠️ Source {$label} directory not found.");
            return;
        }

        $this->makeDirectory($destination);
        $this->files->copyDirectory($source, $destination);
        $this->info("📁 Published: {$label}");
    }

    protected function makeDirectory(string $path): void
    {
        if (! $this->files->isDirectory($path)) {
            $this->files->makeDirectory($path, 0755, true);
        }
    }
}
