<?php

use App\Http\Controllers\Api\TranslationController;
use App\Http\Middleware\AuthenticateApiKey;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| JanBhasha REST API Routes
|--------------------------------------------------------------------------
| All routes here are prefixed with /api/v1 and authenticated via
| the X-API-Key header (organisation-level API key).
*/

Route::prefix('v1')
    ->middleware(AuthenticateApiKey::class)
    ->group(function () {

        // Translate text
        Route::post('/translate', [TranslationController::class, 'store'])
            ->name('api.translate');

        // Translation history
        Route::get('/history', [TranslationController::class, 'index'])
            ->name('api.history');

        // Monthly usage / quota
        Route::get('/usage', [TranslationController::class, 'usage'])
            ->name('api.usage');
    });
