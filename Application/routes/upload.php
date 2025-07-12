<?php

use App\Http\Controllers\FileUploader\ImageUploaderController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->prefix('upload')->group(function () {
    Route::post('/image', ImageUploaderController::class)->name('upload.image');
});
