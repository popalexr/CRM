<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Products\ProductDetailsController;
use App\Http\Controllers\Products\DeleteProductController;
use App\Http\Controllers\Products\ProductsController;
use App\Http\Controllers\Products\ProductsFormController;

Route::middleware('auth')->prefix('products')->name('products.')->group(function () {
    Route::get('/', ProductsController::class)->name('index');
    Route::get('/details', ProductDetailsController::class)->name('details');
    Route::get('/form', ProductsFormController::class)->name('form');
    Route::get('/edit/{product}', ProductsFormController::class)->name('edit')->can('products-edit');
    
    Route::post('/delete', DeleteProductController::class)->name('delete');
});