<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Products\ProductDetailsController;
use App\Http\Controllers\Products\DeleteProductController;
use App\Http\Controllers\Products\HandleProductStockController;
use App\Http\Controllers\Products\ProductsController;
use App\Http\Controllers\Products\ProductsFormController;

Route::middleware('auth')->prefix('products')->name('products.')->group(function () {
    Route::get('/', ProductsController::class)->name('index')->can('products-view');
    Route::get('/details', ProductDetailsController::class)->name('details')->can('products-view');
    Route::get('/form', ProductsFormController::class)->name('form')->can('products-form');

    Route::post('/form', [ProductsFormController::class, 'post'])->name('form.post')->can('products-form');
    Route::post('/delete', DeleteProductController::class)->name('delete')->can('products-delete');
    Route::post('/stock/{id}', HandleProductStockController::class)->name('stock.handle')->can('products-form');
});