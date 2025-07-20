<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('products')->name('products.')->group(function () {
    //
});