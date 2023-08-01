<?php

use App\Http\Controllers\Dashboard\DemoController;
use App\Http\Controllers\Dashboard\PlanController;
use App\Http\Controllers\Dashboard\QuickstartController;
use App\Http\Controllers\Dashboard\StoryController;
use Illuminate\Support\Facades\Route;

// Quickstart Quiz
Route::resource('quickstart', QuickstartController::class)
    ->only(['index', 'store']);

Route::middleware('user-configured')->group(function () {
    // Dashboard home
    Route::inertia('dashboard', 'Dashboard/Index')->name('home');

    // Demo
    Route::get('demo', [DemoController::class, 'index'])
        ->name('demo')
        ->middleware(['subscribed:0']);

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
        ->middleware(['subscribed:0']);
});
