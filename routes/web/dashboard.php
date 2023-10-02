<?php

use App\Features\BetaAccess;
use App\Http\Controllers\Dashboard\BookController;
use App\Http\Controllers\Dashboard\ChapterController;
use App\Http\Controllers\Dashboard\DemoController;
use App\Http\Controllers\Dashboard\NotificationController;
use App\Http\Controllers\Dashboard\PlanController;
use App\Http\Controllers\Dashboard\QuickstartController;
use App\Http\Controllers\Dashboard\SettingsController;
use App\Http\Controllers\Dashboard\StoryController;
use App\Http\Controllers\Dashboard\UserController;
use App\Http\Controllers\Guests\ChapterController as GuestsChapterController;
use App\Http\Controllers\InvoiceController;
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

    // Users
    Route::controller(UserController::class)->middleware('features:'.BetaAccess::class)->name('users.')->group(function () {
        Route::get('/u/{user}', 'show')->name('show');
        Route::get('/profile', 'edit')->name('edit');
        Route::match(['put', 'patch'], '/profile', 'update')->name('update');
    });

    // Notifications
    Route::controller(NotificationController::class)->prefix('/notifications')->middleware('auth')->name('notifications.')->group(function () {
        Route::post('/read/{notification?}', 'read')->name('read');
    });

    // Books
    Route::resource('books', BookController::class)->middleware('features:'.BetaAccess::class)->only('show');

    // Settings
    Route::controller(SettingsController::class)->prefix('settings')->middleware('features:'.BetaAccess::class)->name('settings.')->group(function () {
        Route::redirect('/', '/settings/notifications', 303);

        Route::get('/notifications', 'notifications')->name('notifications');
        Route::get('/integrations', 'integrations')->name('integrations');
        Route::get('/password', 'password')->name('password');
        Route::get('/billing', 'billing')->name('billing');

        Route::put('/password', 'updatePassword')->name('password.update');
        Route::post('/billing', 'postBilling')->name('billing.post');
        Route::put('/billing', 'putBilling')->name('billing.put');
    });

    // Invoices
    Route::get('/user/invoice/{invoice}', InvoiceController::class)->name('invoice.show');

    // Subscription Plans
    Route::resource('plans', PlanController::class)
        ->only(['index', 'show', 'update'])
        ->middleware(['subscribed:0']);

    Route::middleware(['subscribed:1'])->group(function () {
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
            Route::post('/order', 'orderPurchase')->name('order.purchase');
        });
        Route::resource('stories.chapters', ChapterController::class)->shallow();
        Route::resource('stories.questions.chapters', ChapterController::class)->shallow()->only('create');
        Route::controller(ChapterController::class)->prefix('chapters/{chapter}')->name('chapters.')->group(function () {
            Route::get('/write', 'write')->name('write');
            Route::get('/upload', 'upload')->name('upload');
            Route::get('/record', 'record')->name('record');
            Route::get('/files', 'attachments')->name('attachments');
            Route::post('/files', 'transcribe')->name('attachments.transcribe');
            Route::delete('/files/{attachment}', 'deleteAttachments')->name('attachments.destroy');
            Route::get('/enhance', 'enhance')->name('enhance');
            Route::post('/enhance', 'process')->name('enhance.post');
            Route::get('/enhance/stream', 'processStreamed')->name('enhance.stream');
            Route::get('/finish', 'finish')->name('finish');
        });

        // Guest features
        Route::post('/stories/{story}/questions/{question}/invite', [GuestsChapterController::class, 'invite'])->name('guest.chapters.invite');
    });
});
