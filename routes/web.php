<?php

use App\Http\Controllers\Dashboard\StoryController;
use App\Http\Controllers\GlobalSearchController;
use App\Http\Controllers\Guests\AuthController as GuestAuthController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\StaticController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

// Static Pages
Route::controller(StaticController::class)->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/preorder', 'postPreorder')->name('preorder.store')->middleware('anti-spam');
    Route::get('/about', 'about')->name('about');
    Route::get('/privacy-policy', 'privacyPolicy')->name('privacy-policy');
    Route::get('/terms-and-conditions', 'termsAndConditions')->name('terms-and-conditions');
    Route::get('/faq', 'faq')->name('faq');
    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'postContact')->name('contact.store')->middleware('anti-spam');
});

// Search
Route::post('/search', GlobalSearchController::class)->name('search')->middleware('auth');

// Book contents
Route::controller(StoryController::class)->prefix('/stories/{story}')->group(function () {
    Route::get('/book', 'book')->name('book');
    Route::get('/book-cover', 'bookCover')->name('book-cover');
});

// Auth
Route::controller(SocialAuthController::class)
    ->middleware('guest')
    ->prefix('/login/{provider}')
    ->group(function (Router $router) {
        $router->get('/', 'login')->name('login.socialite');
        $router->get('/redirect', 'redirect')->name('login.socialite.redirect');
    });
Route::get('/guests/{guest:sqid}/login', GuestAuthController::class)
    ->name('login.guests')
    ->middleware(['signed']);

// Guests Dashboard
Route::group(['middleware' => ['auth:web,web-guest'], 'as' => 'guests.', 'prefix' => 'guests'], function () {
    include __DIR__.'/web/guests.php';
});

// User Dashboard
Route::group(['middleware' => ['auth', 'verified'], 'as' => 'dashboard.'], function () {
    include __DIR__.'/web/dashboard.php';
})->middleware('features:beta-access');
