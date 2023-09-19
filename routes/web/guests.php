<?php

// Setup

use App\Http\Controllers\Dashboard\ChapterController;
use App\Http\Controllers\Guests\SetupController;
use App\Http\Middleware\GuestConfigured;

// Setup
Route::controller(SetupController::class)->group(function () {
    Route::get('/setup', 'setup')->name('setup');
});

// Chapters
Route::resource('chapters', ChapterController::class)
    ->middleware(GuestConfigured::class);
