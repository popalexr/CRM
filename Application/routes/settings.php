<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Settings\SettingsController;
use App\Http\Controllers\Settings\UpdateCompanySettingsController;
use App\Http\Controllers\Settings\UpdateVatSettingsController;

Route::middleware('auth')->prefix('settings')->name('settings.')->group(function () {
    Route::get('/', SettingsController::class)->name('index');
    Route::get('/vat', \App\Http\Controllers\Settings\VatSettingsController::class)->name('vat');

    Route::post('/updateCompany', UpdateCompanySettingsController::class)->name('company.update');
    Route::post('/updateVat', UpdateVatSettingsController::class)->name('vat.update');

    Route::post('/deleteVat', \App\Http\Controllers\Settings\DeleteVatController::class)->name('vat.delete');
});
