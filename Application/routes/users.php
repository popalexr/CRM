<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users\UsersController;
use App\Http\Controllers\Users\UsersFormController;
use App\Http\Controllers\Users\DeleteUserController;
Route::middleware('auth')->prefix('users')->group(function () {
    Route::get('/', UsersController::class)->name('users.index');
    Route::get('/form', UsersFormController::class)->name('users.form');
    
    Route::post('/form', [UsersFormController::class, 'post'])->name('users.form.post');
    Route::post('/', DeleteUserController::class)->name('users.destroy');
});