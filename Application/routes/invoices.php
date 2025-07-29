<?php

use App\Http\Controllers\Invoices\InvoiceDetailsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Invoices\InvoicesController;
use App\Http\Controllers\Invoices\DeleteInvoiceController;
use App\Http\Controllers\Invoices\InvoicesFormController;
use App\Http\Controllers\Invoices\InvoiceStatusFormController;
use App\Http\Controllers\Invoices\Payments\InvoicePaymentsFormController;
use App\Http\Controllers\Invoices\SendInvoiceToClientController;
use App\Http\Controllers\Invoices\StornoInvoiceFormController;

Route::middleware('auth')->prefix('invoices')->name('invoices.')->group(function () {
    Route::get('/', InvoicesController::class)->name('index');
    Route::get('/details', InvoiceDetailsController::class)->name('details');
    Route::get('/form', InvoicesFormController::class)->name('form');

    Route::post('/sendInvoice', [SendInvoiceToClientController::class, '__invoke'])->name('send_invoice');
    Route::post('/form', [InvoicesFormController::class, 'post'])->name('form.post');
    Route::post('/status', InvoiceStatusFormController::class)->name('change_status');
    Route::post('/delete', DeleteInvoiceController::class)->name('delete');

    Route::post('/storno', StornoInvoiceFormController::class)->name('storno');

    Route::prefix('payments')->name('payments.')->group(function () {
        Route::post('/form', InvoicePaymentsFormController::class)->name('form.post');
    });
});

