<?php

use App\Http\Controllers\Clients\ClientsController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::prefix('clients')->group(function () {
        Route::get('/', ClientsController::class)->name('clients.index');
    });
});