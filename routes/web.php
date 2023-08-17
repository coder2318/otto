<?php

use App\Http\Controllers\PreorderController;
use App\Http\Controllers\SocialAuthController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

// Static Pages
Route::inertia('/', 'Index', [
    'laravelVersion' => \Illuminate\Foundation\Application::VERSION,
    'phpVersion' => PHP_VERSION,
])->name('index');

Route::inertia('/about', 'About')->name('about');
Route::inertia('/privacy-policy', 'PrivacyPolicy')->name('privacy-policy');
Route::inertia('/terms-and-conditions', 'TermsAndConditions')->name('terms-and-conditions');
Route::inertia('/faq', 'FAQ')->name('faq');
Route::inertia('/contact', 'Contact')->name('contact');

Route::post('/preorder', [PreorderController::class, 'store'])->name('preorder.store')->middleware('anti-spam');

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
