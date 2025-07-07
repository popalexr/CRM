<?php

use App\Http\Controllers\Dashboard\DashboardController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', DashboardController::class)->middleware(['auth', 'verified'])->name('home');

Route::get('/clients', function () {
    return Inertia::render('Clients/Index');
})->middleware(['auth', 'verified'])->name('clients.index');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
