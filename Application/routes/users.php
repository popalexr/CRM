<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users\UsersController;
use App\Http\Controllers\Users\UsersFormController;

Route::middleware('auth')->prefix('users')->group(function () {
    Route::get('/', UsersController::class)->name('users.index');
    Route::get('/form', UsersFormController::class)->name('users.form');
    Route::post('/form', UsersFormController::class)->name('users.form.post');
});