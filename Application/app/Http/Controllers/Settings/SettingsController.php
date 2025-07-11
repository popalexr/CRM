<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Settings;
use Inertia\Inertia;

class SettingsController extends Controller
{
    /**
     * Display the settings page.
     */
    public function __invoke()
    {
        $settings = $this->getSettings();

        return Inertia::render('Settings/Index', [
            'companyInfo' => $settings['companyInfo'],
            'companyTypes' => config('application_settings.company_types'),
        ]);
    }

    /**
     * Get all settings from database organized by category.
     *
     * @return array
     */
    private function getSettings(): array
    {
        $allSettings = Settings::all()->keyBy('key');

        $companyInfo = [
            'company_name' => $allSettings->get('company_name')?->value ?? '',
            'company_type' => $allSettings->get('company_type')?->value ?? '',
            'address' => $allSettings->get('address')?->value ?? '',
            'city' => $allSettings->get('city')?->value ?? '',
            'county' => $allSettings->get('county')?->value ?? '',
            'country' => $allSettings->get('country')?->value ?? '',
            'email' => $allSettings->get('email')?->value ?? '',
            'phone' => $allSettings->get('phone')?->value ?? '',
            'iban' => $allSettings->get('iban')?->value ?? '',
            'bank' => $allSettings->get('bank')?->value ?? '',
            'swift' => $allSettings->get('swift')?->value ?? '',
            'vat_payer' => filter_var($allSettings->get('vat_payer')?->value ?? false, FILTER_VALIDATE_BOOLEAN),
        ];

        return [
            'companyInfo' => $companyInfo,
        ];
    }
}
