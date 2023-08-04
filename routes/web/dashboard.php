<?php

use App\Http\Controllers\Dashboard\ChapterController;
use App\Http\Controllers\Dashboard\DemoController;
use App\Http\Controllers\Dashboard\PlanController;
use App\Http\Controllers\Dashboard\QuickstartController;
use App\Http\Controllers\Dashboard\StoryController;
use Illuminate\Support\Facades\Route;

// Quickstart Quiz
Route::resource('quickstart', QuickstartController::class)
    ->only(['index', 'store']);

Route::middleware('user-configured')->group(function () {
    // Demo
    Route::get('demo', [DemoController::class, 'index'])
        ->name('demo')
        ->middleware(['subscribed:0']);

    // Stories
    Route::resource('stories', StoryController::class);
    Route::resource('stories.chapters', ChapterController::class);
    Route::get('/chapters/{chapter}/type', [ChapterController::class, 'type'])->name('chapters.type');

    // Test
    Route::inertia('/preview', 'Dashboard/Preview')
        ->name('preview')
        ->middleware(['password.confirm', 'subscribed']);

    // Subscription Plans
    Route::resource('plans', PlanController::class)
        ->only(['index', 'show', 'update'])
        ->middleware(['subscribed:0']);
});
