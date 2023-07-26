<?php

use App\Http\Controllers\PlanController;
use App\Http\Controllers\SocialAuthController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::controller(SocialAuthController::class)
    ->middleware('guest')
    ->prefix('/login/{provider}')
    ->group(function () {
        Route::get('/', 'login')->where('provider', 'facebook|google')->name('login.socialite');
        Route::get('/redirect', 'redirect')->where('provider', 'facebook|google')->name('login.socialite.redirect');
    });

Route::inertia('/', 'Home', [
    'laravelVersion' => \Illuminate\Foundation\Application::VERSION,
    'phpVersion' => PHP_VERSION,
])->name('home');

// Subscription Plans
Route::resource('plans', PlanController::class)
    ->only(['index', 'show', 'update'])
    ->names('plans')
    ->middleware(['auth', 'subscribed:0']);

Route::inertia('/preview', 'Preview')
    ->name('preview')
    ->middleware(['auth', 'verified', 'password.confirm', 'subscribed']);
