<?php

use App\Features\BetaAccess;
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
    Route::controller(DemoController::class)->middleware(['subscribed:0'])->prefix('/demo')->name('demo.')->group(function () {
        Route::get('/', 'index')->name('index')->middleware('demo:pending,published');
        Route::get('/write', 'write')->name('write')->middleware('demo:published');
        Route::get('/record', 'record')->name('record')->middleware('demo:published');
        Route::get('/files', 'attachments')->name('attachments')->middleware('demo:published');
        Route::get('/enhance', 'enhance')->name('enhance')->middleware('demo:published');
        Route::get('/finish', 'finish')->name('finish');
        Route::get('/book', 'book')->name('book');
        Route::post('/', 'store')->name('store')->middleware('demo:pending');
        Route::post('/files', 'transcribe')->name('attachments.transcribe')->middleware('demo:published');
        Route::put('/', 'update')->name('update')->middleware('demo:published');
        Route::delete('/files/{attachment}', 'deleteAttachments')->name('attachments.destroy')->middleware('demo:published');
    });

    // Subscription Plans
    Route::resource('plans', PlanController::class)
        ->only(['index', 'show', 'update'])
        ->middleware(['subscribed:0', 'features:'.BetaAccess::class]);

    Route::middleware(['subscribed:1', 'features:'.BetaAccess::class])->group(function () {
        // Stories
        Route::resource('stories', StoryController::class);
        Route::controller(StoryController::class)->prefix('stories/{story}')->name('stories.')->group(function () {
            Route::get('/covers', 'covers')->name('covers');
            Route::get('/cover', 'cover')->name('cover');
            Route::post('/contents', 'saveContents')->name('contents.save');
            Route::get('/preview', 'preview')->name('preview');
            Route::get('/book', 'book')->name('book');
            Route::get('/book-cover', 'bookCover')->name('book-cover');
            Route::get('/order', 'order')->name('order');
            Route::patch('/order', 'orderCost')->name('order.cost');
        });
        Route::resource('stories.chapters', ChapterController::class)->shallow();
        Route::controller(ChapterController::class)->prefix('chapters/{chapter}')->name('chapters.')->group(function () {
            Route::get('/write', 'write')->name('write');
            Route::get('/upload', 'upload')->name('upload');
            Route::get('/record', 'record')->name('record');
            Route::get('/files', 'attachments')->name('attachments');
            Route::post('/files', 'transcribe')->name('attachments.transcribe');
            Route::delete('/files/{attachment}', 'deleteAttachments')->name('attachments.destroy');
            Route::get('/enhance', 'enhance')->name('enhance');
            Route::get('/finish', 'finish')->name('finish');
        });
    });
});
