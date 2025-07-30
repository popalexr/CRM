<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\CompanySettingsRequest;
use App\Models\Settings;

class UpdateCompanySettingsController extends Controller
{
    /**
     * List of whitelisted keys for company settings.
     */
    private array $keysToTypes = [
        'company_name' => 'string',
        'company_type' => 'string',
        'cui'          => 'string',
        'registration_number' => 'string',
        'address'      => 'string',
        'city'         => 'string',
        'county'       => 'string',
        'country'      => 'string',
        'email'        => 'string',
        'phone'        => 'string',
        'iban'         => 'string',
        'bank'         => 'string',
        'swift'        => 'string',
        'vat_payer'    => 'boolean',
    ];

    public function __invoke(CompanySettingsRequest $request)
    {
        $settingsData = $request->validated();

        $this->saveCompanySettings($settingsData);

        return redirect()->route('settings.index')->with('success', 'Company settings updated successfully.');
    }

    /**
     * Save the company settings.
     */
    private function saveCompanySettings(array $data): void
    {
        foreach ($data as $key => $value) {
            if (! $this->isWhitelistedKey($key)) { // Skip keys that are not whitelisted
                continue;
            }

            $type = $this->getTypeByKey($key);

            Settings::updateOrCreate(
                ['key' => $key],
                ['value' => $value, 'type' => $type]
            );
        }
    }

    /**
     * Check if the provided key is whitelisted.
     */
    private function isWhitelistedKey(string $key): bool
    {
        return array_key_exists($key, $this->keysToTypes);
    }

    /**
     * Get the type of a setting by its key.
     * 
     * @return string|null - The type of the setting or null if not found
     */
    private function getTypeByKey(string $key): ?string
    {
        return $this->keysToTypes[$key] ?? null;
    }
}
