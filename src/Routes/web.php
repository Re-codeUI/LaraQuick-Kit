<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes (LaraQuickKit)
|--------------------------------------------------------------------------
|
| File ini memuat semua rute global utama untuk LaraQuickKit, termasuk
| autentikasi, halaman utama, dan pemuatan modul dinamis.
|
*/

// Halaman utama (tanpa login)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Rute bawaan autentikasi Laravel (registrasi dimatikan)
Auth::routes([
    'register' => false,
]);

// Rute-rute yang membutuhkan autentikasi
Route::middleware(['auth'])->group(function () {

    // Dashboard utama (gunakan permission dari middleware kustom)
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware('has_permission:view dashboard')->name('dashboard');

    // Pemuatan modul dinamis dari config/laraquick.php
    $modules = config('laraquick.modules', []);

    if (is_array($modules)) {
        foreach ($modules as $module => $settings) {
            if (!empty($settings['enabled']) && $settings['enabled'] === true) {
                $customPath = base_path("routes/modules/{$module}.php");
                if (file_exists($customPath)) {
                    require $customPath;
                }
            }
        }
    }
});
