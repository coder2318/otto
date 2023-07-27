<?php

use App\Http\Controllers\Dashboard\PlanController;
use App\Http\Controllers\Dashboard\StoryController;
use Illuminate\Support\Facades\Route;

// // Home
// Route::inertia('/stories', 'Dashboard/stories')->name('home');

// Stories
Route::resource('stories', StoryController::class);
Route::get('stories/{story}/write', [StoryController::class, 'write'])->name('stories.write');

// Test
Route::inertia('/preview', 'Dashboard/Preview')
    ->name('preview')
    ->middleware(['password.confirm', 'subscribed']);

// Subscription Plans
Route::resource('plans', PlanController::class)
    ->only(['index', 'show', 'update'])
    ->middleware(['auth', 'subscribed:0']);
