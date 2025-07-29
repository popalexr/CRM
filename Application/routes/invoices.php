<?php

use App\Http\Controllers\Invoices\InvoiceDetailsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Invoices\InvoicesController;
use App\Http\Controllers\Invoices\DeleteInvoiceController;
use App\Http\Controllers\Invoices\InvoicesFormController;
use App\Http\Controllers\Invoices\InvoicePaymentsController;


Route::middleware('auth')->prefix('invoices')->name('invoices.')->group(function () {
    Route::get('/', InvoicesController::class)->name('index');
    Route::get('/{invoice}/edit', [InvoicesController::class, 'edit'])->name('edit');
    Route::put('/{invoice}', [InvoicesController::class, 'update'])->name('update');
    Route::get('/details', InvoiceDetailsController::class)->name('details');
    Route::get('/form', InvoicesFormController::class)->name('form');
    Route::post('/{invoice}/payments', [InvoicePaymentsController::class, 'store'])->name('payments.store');

    Route::post('/form', [InvoicesFormController::class, 'post'])->name('form.post');
    Route::post('/delete', DeleteInvoiceController::class)->name('delete');
    Route::post('/invoicePayments', [InvoicePaymentsFormController::class, '__invoke'])->name('invoice_payments');
});

