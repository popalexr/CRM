<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users\UsersController;
use App\Http\Controllers\Users\UsersFormController;
use App\Http\Controllers\Users\DeleteUserController;
use App\Http\Controllers\Users\UserDetailsController;
Route::middleware('auth')->prefix('users')->group(function () {
    Route::get('/', UsersController::class)->name('users.index');
    Route::get('/form', UsersFormController::class)->name('users.form');
<<<<<<< HEAD
    Route::post('/form', UsersFormController::class)->name('users.form.post');
     Route::get('/{user}', UserDetailsController::class)->name('users.show'); 
    Route::delete('/', DeleteUserController::class)->name('users.destroy');
=======
    
    Route::post('/form', [UsersFormController::class, 'post'])->name('users.form.post');
    Route::post('/', DeleteUserController::class)->name('users.destroy');
>>>>>>> a4db04d8ef8b1cf26b53fac209557085e8dfed5e
});