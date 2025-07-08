<?php

use App\Http\Controllers\Dashboard\DashboardController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', DashboardController::class)->middleware(['auth', 'verified'])->name('home');


require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
require __DIR__.'/clients.php';