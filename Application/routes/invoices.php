<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Invoices\InvoicesController;
use App\Http\Controllers\Invoices\DeleteInvoiceController;

Route::get('/invoices', InvoicesController::class)
    ->middleware(['auth', 'verified'])
    ->name('invoices.index');
Route::post('/{invoice}/delete', DeleteInvoiceController::class)->name('delete');
