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
Route::resource('chapters', ChapterController::class)->except('destroy')->middleware(GuestConfigured::class);
Route::controller(ChapterController::class)->prefix('chapters/{chapter}')->name('chapters.')->middleware(GuestConfigured::class)->group(function () {
    Route::post('/resend', 'resend')->name('resend');
    Route::get('/write', 'write')->name('write');
    Route::get('/upload', 'upload')->name('upload');
    Route::get('/record', 'record')->name('record');
    Route::get('/enhance', 'enhance')->name('enhance');
    Route::get('/files', 'attachments')->name('attachments');
    Route::post('/files', 'transcribe')->name('attachments.transcribe');
    Route::delete('/files/{attachment}', 'deleteAttachments')->name('attachments.destroy');
    Route::get('/congratulation', 'congratulation')->name('congratulation');
    Route::get('/finish', 'finish')->name('finish');
    Route::delete('/image/{imageId}', 'removeImage')->name('image');

    Route::delete('/image/{imageId}', 'removeImage')->name('image');
    Route::post('/image', 'addImage')->name('image.add');
    Route::post('/attachments', 'uploadAttachments')->name('attachments.upload');
    Route::get('/enhance/stream', 'process')->name('enhance.stream');
    Route::get('/translate/stream', 'translate')->name('translate.stream');
});
