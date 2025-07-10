<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanyInfoController extends Controller
{
    /**
     * Update company information settings.
     */
    public function __invoke(Request $request)
    {
        $companyTypes = collect(Setting::getCompanyTypes())->pluck('value')->implode(',');
        
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string|max:255',
            'company_type' => 'required|string|in:' . $companyTypes,
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'county' => 'required|string|max:100',
            'country' => 'required|string|max:100',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'iban' => 'nullable|string|max:34',
            'bank' => 'nullable|string|max:100',
            'swift' => 'nullable|string|max:11',
            'vat_payer' => 'boolean',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'type' => $this->getSettingType($key, $value),
                    'value' => $this->formatSettingValue($value)
                ]
            );
        }

        return back()->with('success', 'Informațiile companiei au fost actualizate cu succes.');
    }

    /**
     * Determine the setting type based on the key and value.
     */
    private function getSettingType(string $key, mixed $value): string
    {
        if ($key === 'vat_payer') {
            return 'boolean';
        }

        if ($key === 'email') {
            return 'email';
        }

        return 'string';
    }

    /**
     * Format the setting value for storage.
     */
    private function formatSettingValue(mixed $value): string
    {
        if (is_bool($value)) {
            return $value ? '1' : '0';
        }

        return (string) $value;
    }
}
