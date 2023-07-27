<?php

use App\Http\Controllers\PlanController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\StoryController;
use Illuminate\Support\Facades\Route;

// Index
Route::inertia('/', 'Index', [
    'laravelVersion' => \Illuminate\Foundation\Application::VERSION,
    'phpVersion' => PHP_VERSION,
])->name('index');

// Auth
Route::controller(SocialAuthController::class)
    ->middleware('guest')
    ->prefix('/login/{provider}')
    ->group(function () {
        Route::get('/', 'login')->where('provider', 'facebook|google')->name('login.socialite');
        Route::get('/redirect', 'redirect')->where('provider', 'facebook|google')->name('login.socialite.redirect');
    });

// Subscription Plans
Route::resource('plans', PlanController::class)
    ->only(['index', 'show', 'update'])
    ->middleware(['auth', 'subscribed:0']);

// Home
Route::inertia('/home', 'Home')
    ->name('home')
    ->middleware(['auth', 'verified']);

// Stories
Route::resource('stories', StoryController::class)
    ->middleware(['auth', 'verified']);

// Test
Route::inertia('/preview', 'Preview')
    ->name('preview')
    ->middleware(['auth', 'verified', 'password.confirm', 'subscribed']);
