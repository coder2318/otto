<?php

use App\Http\Controllers\Dashboard\StoryController;
use App\Http\Controllers\Dashboard\PlanController;
use Illuminate\Support\Facades\Route;


// Home
Route::inertia('/home', 'Dashboard/Home')->name('home');

// Stories
Route::resource('stories', StoryController::class);

// Test
Route::inertia('/preview', 'Dashboard/Preview')
    ->name('preview')
    ->middleware(['password.confirm', 'subscribed']);

// Subscription Plans
Route::resource('plans', PlanController::class)
    ->only(['index', 'show', 'update'])
    ->middleware(['auth', 'subscribed:0']);
