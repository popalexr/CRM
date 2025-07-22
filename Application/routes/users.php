<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users\UsersController;
use App\Http\Controllers\Users\UsersFormController;
use App\Http\Controllers\Users\DeleteUserController;
use App\Http\Controllers\Users\UserDetailsController;
use App\Http\Controllers\Users\UserPreferencesController;
Route::middleware('auth')->prefix('users')->name('users.')->group(function () {
    Route::get('/', UsersController::class)->name('index');
    Route::get('/form', UsersFormController::class)->name('form');
    Route::get('/details', UserDetailsController::class)->name('details'); 

    Route::post('/save-preference', UserPreferencesController::class)->name('save-preference');
    Route::post('/form', [UsersFormController::class, 'post'])->name('form.post');
    Route::post('/', DeleteUserController::class)->name('delete');
});