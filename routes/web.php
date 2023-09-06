<?php

use App\Features\BetaAccess;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\StaticController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

// Static Pages
Route::controller(StaticController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/preorder', 'postPreorder')->name('preorder.store')->middleware('anti-spam');

    Route::group(['middleware' => 'features:'.BetaAccess::class], function () {
        Route::get('/about', 'about')->name('about');
        Route::get('/privacy-policy', 'privacyPolicy')->name('privacy-policy');
        Route::get('/terms-and-conditions', 'termsAndConditions')->name('terms-and-conditions');
        Route::get('/faq', 'faq')->name('faq');
        Route::get('/contact', 'contact')->name('contact');
        Route::post('/contact', 'postContact')->name('contact.store')->middleware('anti-spam');
    });
});

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
    include_once __DIR__.'/web/dashboard.php';
})->middleware('features:beta-access');

// Admin Panel
Route::group(['middleware' => ['auth', 'verified'], 'name' => 'admin.'], function () {
    include_once __DIR__.'/web/admin.php';
})->middleware('features:beta-access');
