<?php

namespace Magicbox\LaraQuickKit\Providers;

use Illuminate\Support\ServiceProvider;
use Magicbox\LaraQuick\Console\Commands\SetupCommand;

class LaraQuickServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Register dependencies here if needed
    }

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                SetupCommand::class,
            ]);

            $this->publishes([
                __DIR__ . '/../database/migrations' => database_path('migrations'),
                __DIR__ . '/../resources/views' => resource_path('views/vendor/laraquick'),
            ], 'laraquick-resources');
        }

        // Load routes
        $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'laraquick');
    }
}
