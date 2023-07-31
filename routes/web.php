<?php

use App\Http\Controllers\SocialAuthController;
use Illuminate\Support\Facades\Route;

// Static Pages
Route::inertia('/', 'Index', [
    'laravelVersion' => \Illuminate\Foundation\Application::VERSION,
    'phpVersion' => PHP_VERSION,
])->name('index');

// Auth
Route::controller(SocialAuthController::class)
    ->middleware('guest')
    ->prefix('/login/{provider}')
    ->group(function () {
        Route::get('/', 'login')->name('login.socialite');
        Route::get('/redirect', 'redirect')->name('login.socialite.redirect');
    });

// User Dashboard
Route::group(['middleware' => ['auth', 'verified'], 'name' => 'dashboard.'], function () {
    require_once __DIR__.'/web/dashboard.php';
});

// Admin Panel
Route::group(['middleware' => ['auth', 'verified'], 'name' => 'admin.'], function () {
    require_once __DIR__.'/web/admin.php';
});
