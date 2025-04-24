<?php

namespace MagicBox\LaraQuickKit\Providers;

use Illuminate\Support\ServiceProvider;
use MagicBox\LaraQuickKit\Console\Commands\LaraQuickPublishCommand;
use MagicBox\LaraQuickKit\Console\Commands\AddMiddlewareCommand;
use MagicBox\LaraQuickKit\Console\Commands\LaraQuickSeedCommand;

class LaraQuickServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->commands([
            LaraQuickSeedCommand::class,
        ]);
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            // Daftarkan semua command di boot untuk menghindari binding error
            $this->commands([
                LaraQuickPublishCommand::class,
                AddMiddlewareCommand::class,
            ]);

            // Publikasi file config
            $this->publishes([
                __DIR__ . '/../Config/laraquick.php' => config_path('laraquick.php'),
            ], 'laraquick-config');

            // Publikasi file migration
            $this->publishes([
                __DIR__ . '/../Database/Migrations/' => database_path('migrations'),
            ], 'laraquick-migrations');

            // Publikasi view
            $this->publishes([
                __DIR__ . '/../Resources/views/auth' => resource_path('views/auth'),
                __DIR__ . '/../Resources/views/layouts' => resource_path('views/layouts'),
                __DIR__ . '/../Resources/views/home.blade.php' => resource_path('views/home.blade.php'),
            ], 'laraquick-views');
            $this->publishes([
                __DIR__.'/../Config/laraquick.php' => config_path('laraquick.php'),
            ], 'laraquick-config');
            
        }

        // Load route setelah boot selesai
        $this->app->booted(function () {
            $this->loadRoutesFrom(__DIR__ . '/../Routes/web.php');
        });

        // Register RouteServiceProvider
        $this->app->register(RouteServiceProvider::class);
    }
}
