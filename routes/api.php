<?php

use App\Http\Controllers\TestController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::controller(WebhookController::class)->name('webhook.')->group(function () {
    Route::post('/lulu', 'lulu')->name('lulu');
});

Route::post('/test-prompt', [TestController::class, 'prompt'])->name('test.prompt.submit');
