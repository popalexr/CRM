<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Products\DeleteProductController;

Route::middleware('auth')->prefix('products')->name('products.')->group(function () {
    Route::post('/delete', DeleteProductController::class)->name('products.destroy');
    
});