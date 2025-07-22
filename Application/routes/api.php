<?php

use App\Http\Controllers\API\GetVatController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('api')->name('api.')->group(function () {
    Route::get('/getVAT', GetVatController::class)->name('getVAT');
});