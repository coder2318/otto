<?php

use App\Http\Controllers\Guests\ChapterController;
use App\Http\Controllers\Guests\SetupController;
use App\Http\Middleware\GuestConfigured;

// Setup
Route::controller(SetupController::class)->group(function () {
    Route::get('/setup', 'setup')->name('setup');
    Route::post('/setup', 'postSetup')->name('setup.post');
});

// Chapters
Route::resource('chapters', ChapterController::class)
    ->middleware(GuestConfigured::class);
