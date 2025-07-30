<?php

namespace App\Http\Requests\Settings;

use Illuminate\Foundation\Http\FormRequest;

class CompanySettingsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $companyTypes = $this->getCompanyTypes();

        return [
            'company_name' => 'required|string|max:255',
            'company_type' => 'required|string|in:' . $companyTypes,
            'cui'          => 'nullable|string|max:32',
            'registration_number' => 'nullable|string|max:32',
            'address'      => 'required|string|max:255',
            'city'         => 'required|string|max:100',
            'county'       => 'required|string|max:100',
            'country'      => 'required|string|max:100',
            'email'        => 'required|email|max:255',
            'phone'        => 'required|string|max:20',
            'iban'         => 'nullable|string|max:34',
            'bank'         => 'nullable|string|max:100',
            'swift'        => 'nullable|string|max:11',
            'vat_payer'    => 'boolean',
        ];
    }

    /**
     * Get the list of company types from config for validation.
     */
    private function getCompanyTypes(): string
    {
        return implode(',', array_column(config('application_settings.company_types'), 'value'));
    }
}
