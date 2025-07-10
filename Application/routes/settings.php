<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Settings\SettingsController;
use App\Http\Controllers\Settings\CompanyInfoController;
use App\Http\Controllers\Settings\VatRateController;

Route::middleware('auth')->prefix('settings')->name('settings.')->group(function () {
    Route::get('/', SettingsController::class)->name('index');
    Route::post('/company', CompanyInfoController::class)->name('company.update');
    Route::post('/vat', [VatRateController::class, 'store'])->name('vat.store');
    Route::delete('/vat/{id}', [VatRateController::class, 'destroy'])->name('vat.destroy');
});
