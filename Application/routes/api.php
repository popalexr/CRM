<?php

use App\Http\Controllers\API\GetClientsController;
use App\Http\Controllers\API\GetProductsController;
use App\Http\Controllers\API\GetVatController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('api')->name('api.')->group(function () {
    Route::get('/getVAT', GetVatController::class)->name('getVAT');
    Route::get('/getClients', GetClientsController::class)->name('getClients');
    Route::get('/getProducts', GetProductsController::class)->name('getProducts');

    Route::get('/currencyRate', \App\Http\Controllers\API\CurrencyRateController::class)->name('currencyRate');
});