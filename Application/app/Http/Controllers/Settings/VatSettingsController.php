<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\VAT;

class VatSettingsController extends Controller
{
    /**
     * Display the VAT settings page.
     */
    public function __invoke()
    {
        return inertia('Settings/VAT', [
            'vatRates' => $this->getVatRates(),
        ]);
    }

    /**
     * Retrieve the VAT rates from the database or configuration.
     */
    private function getVatRates(): array
    {
        $rates = VAT::whereNull('deleted_at')
            ->orderBy('rate', 'asc')
            ->get();

        return $rates->map(function ($rate) {
            return [
                'id'   => $rate->id,
                'name' => $rate->name,
                'rate' => $rate->rate,
            ];
        })->toArray();
    }
}
