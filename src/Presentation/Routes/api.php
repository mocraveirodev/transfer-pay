<?php

namespace Src\Presentation\Routes;

use Illuminate\Support\Facades\Route;
use Src\Presentation\Controllers\TransactionController;

Route::middleware(['auth:sanctum'])
    ->group(function () {
        Route::controller(TransactionController::class)
            ->group(function () {
                Route::post('/transaction', 'transaction')->name('transaction');
            });
    });
