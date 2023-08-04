<?php

use App\Http\Controllers\SocialAuthController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

// Static Pages
Route::inertia('/', 'Index', [
    'laravelVersion' => \Illuminate\Foundation\Application::VERSION,
    'phpVersion' => PHP_VERSION,
])->name('index');

Route::get('/about', 'About')->name('about');
Route::get('/privacy-policy', 'PrivacyPolicy')->name('privacy-policy');
Route::get('/terms-and-conditions', 'TermsAndConditions')->name('terms-and-conditions');
Route::get('/faq', 'FAQ')->name('faq');
Route::get('/contact', 'Contact')->name('contact');

// Auth
Route::controller(SocialAuthController::class)
    ->middleware('guest')
    ->prefix('/login/{provider}')
    ->group(function (Router $router) {
        $router->get('/', 'login')->name('login.socialite');
        $router->get('/redirect', 'redirect')->name('login.socialite.redirect');
    });

// User Dashboard
Route::group(['middleware' => ['auth', 'verified'], 'name' => 'dashboard.'], function () {
    require_once __DIR__.'/web/dashboard.php';
});

// Admin Panel
Route::group(['middleware' => ['auth', 'verified'], 'name' => 'admin.'], function () {
    require_once __DIR__.'/web/admin.php';
});
