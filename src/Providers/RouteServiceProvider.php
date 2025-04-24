<?php

namespace MagicBox\LaraQuickKit\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use MagicBox\LaraQuickKit\Http\Middleware\EnsureUserHasPermission;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Tentukan namespace controllers untuk package ini.
     */
    protected $namespace = 'MagicBox\LaraQuickKit\Http\Controllers';

    /**
     * Boot method untuk registrasi routes.
     */
    public function boot()
    {
        parent::boot();
        Route::aliasMiddleware('has_permission', EnsureUserHasPermission::class);
    }

    /**
     * Daftarkan routes untuk aplikasi.
     */
    public function map()
    {
        // Load route authentication global
        $this->mapAuthRoutes();

        // Load routes modular berdasarkan pilihan user
        $this->mapModuleRoutes();
    }

    /**
     * Register routes autentikasi global
     */
    protected function mapAuthRoutes()
    {
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(__DIR__ . '/../Routes/web.php');
    }

    /**
     * Register routes berdasarkan module yang dipilih
     */
    protected function mapModuleRoutes()
    {
        $modules = config('laraquick.modules', []);

        foreach ($modules as $module => $config) {
            if (!empty($config['enabled'])) {
                $moduleRoutePath = __DIR__ . "/../Routes/modules/{$module}.php";
                if (file_exists($moduleRoutePath)) {
                    Route::prefix($module)
                        ->middleware(['web', 'auth'])
                        ->namespace($this->namespace . '\\' . ucfirst($module))
                        ->name($module . '.')
                        ->group($moduleRoutePath);
                }
            }
        }
    }
}
