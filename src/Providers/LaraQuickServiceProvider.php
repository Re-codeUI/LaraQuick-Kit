<?php

namespace MagicBox\LaraQuickKit\Providers;

use Illuminate\Support\ServiceProvider;
use MagicBox\LaraQuickKit\Console\Commands\LaraQuickPublishCommand;
use MagicBox\LaraQuickKit\Console\Commands\AddMiddlewareCommand;
use MagicBox\LaraQuickKit\Console\Commands\LaraQuickSeedCommand;
use MagicBox\LaraQuickKit\Console\Commands\SetupRolePermissionCommand;

class LaraQuickServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Command yang bisa dipanggil secara umum
        $this->commands([
            LaraQuickSeedCommand::class,
            LaraQuickPublishCommand::class,
            AddMiddlewareCommand::class,
            SetupRolePermissionCommand::class,
        ]);

        // Register RouteServiceProvider agar semua route terdaftar
        $this->app->register(RouteServiceProvider::class);
    }

    public function boot()
    {
        // Publikasi konfigurasi
        $this->publishes([
            __DIR__ . '/../Config/laraquick.php' => config_path('laraquick.php'),
        ], 'laraquick-config');

        // Publikasi migrasi
        $this->publishes([
            __DIR__ . '/../Database/Migrations/' => database_path('migrations'),
        ], 'laraquick-migrations');

        // Publikasi views
        $this->publishes([
            __DIR__ . '/../Resources/views/auth' => resource_path('views/auth'),
            __DIR__ . '/../Resources/views/layouts' => resource_path('views/layouts'),
            __DIR__ . '/../Resources/views/home.blade.php' => resource_path('views/home.blade.php'),
        ], 'laraquick-views');

        // Load route dari package setelah aplikasi selesai booting
        $this->app->booted(function () {
            $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
        });
    }
}
