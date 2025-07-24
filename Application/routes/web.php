<?php

use App\Http\Controllers\Dashboard\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', DashboardController::class)->middleware(['auth', 'verified'])->name('home');


require __DIR__.'/profile.php';
require __DIR__.'/auth.php';
require __DIR__.'/clients.php';
require __DIR__.'/users.php';
require __DIR__.'/products.php';
require __DIR__.'/settings.php';
require __DIR__.'/upload.php';
require __DIR__.'/api.php';
require __DIR__.'/invoices.php';
