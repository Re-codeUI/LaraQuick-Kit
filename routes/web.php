<?php
use Illuminate\Support\Facades\Route;
use Magic\Laraquick\Http\Controllers\AuthController;

//Group Authentication routes
Route::group(['prefix' => 'Authenticate'], function(){
    route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    route::post('login', [AuthController::class, 'login']);
    route::post('logout', [AuthController::class, 'logout'])->name('logout');

    route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
    route::post('register', [AuthController::class, 'register']);
});

//Home Route
Route::get('/home', function(){
    return view('laraquick::home');
})->middleware('auth')->name('home');