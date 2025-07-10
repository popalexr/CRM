<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Setting;
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
            'vatRates' => $settings['vatRates'],
            'companyTypes' => Setting::getCompanyTypes(),
            'formLabels' => Setting::getFormLabels(),
        ]);
    }

    /**
     * Get all settings from database organized by category.
     *
     * @return array
     */
    private function getSettings(): array
    {
        $allSettings = Setting::all()->keyBy('key');

        $companyInfo = [
            'company_name' => $allSettings->get('company_name')?->value ?? '',
            'company_type' => $allSettings->get('company_type')?->value ?? '',
            'address' => $allSettings->get('address')?->value ?? '',
            'city' => $allSettings->get('city')?->value ?? '',
            'county' => $allSettings->get('county')?->value ?? '',
            'country' => $allSettings->get('country')?->value ?? 'România',
            'email' => $allSettings->get('email')?->value ?? '',
            'phone' => $allSettings->get('phone')?->value ?? '',
            'iban' => $allSettings->get('iban')?->value ?? '',
            'bank' => $allSettings->get('bank')?->value ?? '',
            'swift' => $allSettings->get('swift')?->value ?? '',
            'vat_payer' => filter_var($allSettings->get('vat_payer')?->value ?? false, FILTER_VALIDATE_BOOLEAN),
        ];

        $vatRatesSettings = $allSettings->filter(function ($setting) {
            return str_starts_with($setting->key, 'vat_rate_') && !str_contains($setting->key, '_name');
        });

        $vatRates = [];
        foreach ($vatRatesSettings as $setting) {
            $rateId = str_replace('vat_rate_', '', $setting->key);
            $nameKey = 'vat_rate_' . $rateId . '_name';
            
            $vatRates[] = [
                'id' => $rateId,
                'rate' => floatval($setting->value),
                'name' => $allSettings->get($nameKey)?->value ?? 'TVA ' . $setting->value . '%',
            ];
        }

        if (empty($vatRates)) {
            $vatRates = Setting::getDefaultVatRates();
        }

        return [
            'companyInfo' => $companyInfo,
            'vatRates' => $vatRates,
        ];
    }
}
