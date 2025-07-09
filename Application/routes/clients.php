<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Clients\ClientsController;
use App\Http\Controllers\Clients\ClientsDetailsController;
use App\Http\Controllers\Clients\ClientsFormController;
use App\Http\Controllers\Clients\ClientDeleteController;

Route::middleware('auth')->prefix('clients')->name('clients.')->group(function () {
    Route::get('/', ClientsController::class)->name('index');
    Route::get('/form', ClientsFormController::class)->name('form');
    Route::get('/details', ClientsDetailsController::class)->name('details');

    Route::post('/form', [ClientsFormController::class, 'post'])->name('form.post');
    Route::post('/delete', [ClientDeleteController::class, '__invoke'])->name('delete');
});