<?php

use App\Http\Controllers\Clients\ClientsController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::prefix('clients')->group(function () {
        Route::get('/', [ClientsController::class, 'index'])->name('clients.index');
        Route::post('/', [ClientsController::class, 'store'])->name('clients.store');
        Route::get('/{client}', [ClientsController::class, 'show'])->name('clients.show');
        Route::get('/{client}/edit', [ClientsController::class, 'edit'])->name('clients.edit');
        Route::put('/{client}', [ClientsController::class, 'update'])->name('clients.update');
        Route::delete('/{client}', [ClientsController::class, 'destroy'])->name('clients.destroy');
        
    });
});