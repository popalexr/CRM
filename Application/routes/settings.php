<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Settings\SettingsController;
use App\Http\Controllers\Settings\UpdateCompanySettingsController;
use App\Http\Controllers\Settings\UpdateVatSettingsController;
use App\Http\Controllers\Settings\CompanyLogoController;


Route::middleware('auth')->prefix('settings')->name('settings.')->group(function () {
    Route::get('/', SettingsController::class)->name('index');
    Route::get('/vat', \App\Http\Controllers\Settings\VatSettingsController::class)->name('vat');

    Route::post('/updateCompany', UpdateCompanySettingsController::class)->name('company.update');
    Route::post('/updateVat', UpdateVatSettingsController::class)->name('vat.update');
    
    Route::get('/updateLogo', [CompanyLogoController::class, 'show'])->name('logo.show');
    Route::post('/updateLogo', [CompanyLogoController::class, 'store'])->name('logo.store');
    Route::post('/settings/deleteLogo', [CompanyLogoController::class, 'destroy'])->name('settings.logo.destroy');


    Route::post('/deleteVat', \App\Http\Controllers\Settings\DeleteVatController::class)->name('vat.delete');
});
