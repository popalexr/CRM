<?php

use App\Http\Controllers\Dashboard\DashboardController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestInvoiceDesignController;

Route::get('/', DashboardController::class)->middleware(['auth', 'verified'])->name('home');
Route::post('/dashboard/reminder', [DashboardController::class, 'saveReminder'])->middleware(['auth'])->name('dashboard.reminder');

Route::get('/invoice/design1', [TestInvoiceDesignController::class, 'design1']);
Route::get('/invoice/design2', [TestInvoiceDesignController::class, 'design2']);
Route::get('/invoice/design3', [TestInvoiceDesignController::class, 'design3']);

require __DIR__.'/profile.php';
require __DIR__.'/auth.php';
require __DIR__.'/clients.php';
require __DIR__.'/users.php';
require __DIR__.'/products.php';
require __DIR__.'/settings.php';
require __DIR__.'/upload.php';
require __DIR__.'/api.php';
require __DIR__.'/invoices.php';
