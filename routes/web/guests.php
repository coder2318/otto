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
Route::post('/chapters/{chapter}/resend', [ChapterController::class, 'resend'])->name('chapters.resend');
Route::resource('chapters', ChapterController::class)
    ->middleware(GuestConfigured::class);
