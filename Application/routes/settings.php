<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Settings\SettingsController;
use App\Http\Controllers\Settings\UpdateCompanySettingsController;
use App\Http\Controllers\Settings\UpdateVatSettingsController;
use App\Http\Controllers\Settings\CompanyLogoController;
use \App\Http\Controllers\Settings\DeleteVatController;
use App\Http\Controllers\Settings\UpdateCompanyLogoController;
use \App\Http\Controllers\Settings\VatSettingsController;


Route::middleware('auth')->prefix('settings')->name('settings.')->group(function () {
    Route::get('/', SettingsController::class)->name('index');
    Route::get('/vat', VatSettingsController::class)->name('vat');
    Route::get('/updateLogo', CompanyLogoController::class)->name('logo');

    Route::post('/updateCompany', UpdateCompanySettingsController::class)->name('company.update');
    Route::post('/updateVat', UpdateVatSettingsController::class)->name('vat.update');
    
    Route::post('/updateLogo', UpdateCompanyLogoController::class)->name('logo.update');

    Route::post('/deleteVat', DeleteVatController::class)->name('vat.delete');
});
