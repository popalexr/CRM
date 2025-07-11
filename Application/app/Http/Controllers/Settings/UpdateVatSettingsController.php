<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use App\Http\Requests\Settings\VatSettingsRequest;
use App\Models\VAT;

class UpdateVatSettingsController extends Controller
{
    public function __invoke(VatSettingsRequest $request)
    {
        $validatedData = $request->validated();

        if (array_key_exists('id', $validatedData)) {
            // Update existing VAT settings
            $this->updateVatSettings($validatedData);
        } else {
            // Create new VAT settings
            $this->saveVatSettings($validatedData);
        }

        return redirect()->route('settings.vat')->with('success', 'VAT list updated successfully.');
    }

    /**
     * Save the VAT settings to the database.
     */
    private function saveVatSettings(array $data): void
    {
        VAT::create([
            'name' => $data['name'],
            'rate' => $data['rate'],
        ]);
    }

    /**
     * Update the VAT settings in the database.
     */
    private function updateVatSettings(array $data): void
    {
        $vat = VAT::find($data['id']);

        if ($vat) {
            $vat->update([
                'name' => $data['name'],
                'rate' => $data['rate'],
            ]);
        }
    }
}
