<?php

use App\Http\Controllers\Clients\ClientsController;
use App\Http\Controllers\Clients\ClientsDetailsController;
use App\Http\Controllers\Clients\ClientsFormController;
use App\Http\Controllers\Clients\ClientContactsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Clients\ClientDeleteController;


Route::middleware('auth')->group(function () {

    Route::prefix('clients')->group(function () {      
        Route::get('/', ClientsController::class)->name('clients.index');
        Route::get('/form', ClientsFormController::class)->name('clients.form');
        Route::get('/details', ClientsDetailsController::class)->name('clients.details');

        Route::post('/form/{id?}', [ClientsFormController::class, 'post'])->name('clients.form.post');
        Route::post('/delete', [ClientDeleteController::class, '__invoke'])->name('clients.delete');
        
        // Contact routes
        Route::prefix('{clientId}/contacts')->group(function () {
            Route::get('/', [ClientContactsController::class, 'index'])->name('clients.contacts.index');
            Route::post('/', [ClientContactsController::class, 'store'])->name('clients.contacts.store');
            Route::put('/{contactId}', [ClientContactsController::class, 'update'])->name('clients.contacts.update');
            Route::delete('/{contactId}', [ClientContactsController::class, 'destroy'])->name('clients.contacts.destroy');
            
        });
    });
});