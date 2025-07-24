<?php

use App\Http\Controllers\Invoices\InvoiceDetailsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Invoices\InvoicesController;
use App\Http\Controllers\Invoices\DeleteInvoiceController;

use App\Http\Controllers\Invoices\InvoicesFormController;

Route::middleware('auth')->prefix('invoices')->name('invoices.')->group(function () {
    Route::get('/', InvoicesController::class)->name('index');
    Route::get('/details', InvoiceDetailsController::class)->name('details');
    Route::get('/form', InvoicesFormController::class)->name('form');

    Route::post('/form', [InvoicesFormController::class, 'post'])->name('form.post');
    Route::post('/delete', DeleteInvoiceController::class)->name('delete');
});

