<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes (LaraQuickKit)
|--------------------------------------------------------------------------
|
| File ini digunakan untuk mendefinisikan route global yang digunakan oleh
| LaraQuickKit. Semua route utama yang dibutuhkan aplikasi ini berada di sini.
|
*/

// Halaman utama (pastikan bisa diakses tanpa login)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Route untuk autentikasi Laravel bawaan
Auth::routes([
    'register' => false, // Matikan pendaftaran jika tidak ingin user bisa register sendiri
]);

// Route yang memerlukan autentikasi
Route::middleware(['auth'])->group(function () {
    
    // Dashboard setelah login
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware('permission:view dashboard');
    

    // Muat route dari modul yang dipilih pengguna
    $modules = config('laraquick.modules', []);
    
    if (is_array($modules)) {
        foreach ($modules as $module => $settings) {
            if (!empty($settings['enabled']) && $settings['enabled'] === true) {
                $moduleRoutePath = base_path("routes/modules/{$module}.php");
                if (file_exists($moduleRoutePath)) {
                    require $moduleRoutePath;
                }
            }
        }
    }
});
