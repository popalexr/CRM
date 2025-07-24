<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Invoices\InvoicesController;

Route::get('/invoices', InvoicesController::class)
    ->middleware(['auth', 'verified'])
    ->name('invoices.index');
