<?php

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

Route::group(['middleware' => ['auth']], function () {
    Route::get('/quickstart', [UserController::class, 'quickstart'])
        ->name('quickstart')
        ->middleware('user-access:home,true');

    Route::post('/quickstart', [UserController::class, 'saveProfileDetails'])
        ->middleware('user-access:home,true');

    Route::group(['middleware' => ['user-access:quickstart']], function () {
        Route::inertia('/preview', 'Preview')->name('preview');
    });
});
