<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Clients\ClientsController;
use App\Http\Controllers\Clients\ClientsDetailsController;
use App\Http\Controllers\Clients\ClientsFormController;
use App\Http\Controllers\Clients\ClientDeleteController;

Route::middleware('auth')->prefix('clients')->name('clients.')->group(function () {
    Route::get('/', ClientsController::class)->name('index')->can('clients-view');
    Route::get('/form', ClientsFormController::class)->name('form')->can('clients-form');
    Route::get('/details', ClientsDetailsController::class)->name('details')->can('clients-view');

    Route::post('/form', [ClientsFormController::class, 'post'])->name('form.post')->can('clients-form');
    Route::post('/delete', [ClientDeleteController::class, '__invoke'])->name('delete')->can('clients-delete');
});