<?php

namespace App\Http\Requests\Clients;

use Illuminate\Foundation\Http\FormRequest;

class ClientsFormRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // TODO: Future implementation for permissions

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'client_name'               => 'required|string|max:255',
            'client_type'               => 'required|in:individual,business',
            'cui'                       => 'nullable|string|max:255',
            'registrationNumber'        => 'nullable|string|max:255',
            'cnp'                       => 'nullable|string|max:255',
            'email'                     => 'nullable|email|max:255',
            'phone'                     => 'nullable|string|max:255',
            'address'                   => 'nullable|string|max:255',
            'country'                   => 'nullable|string|max:255',
            'county'                    => 'nullable|string|max:255',
            'city'                      => 'nullable|string|max:255',
            'iban'                      => 'nullable|string|max:255',
            'swift'                     => 'nullable|string|max:255',
            'bank'                      => 'nullable|string|max:255',
            'currency'                  => 'nullable|string|max:255',
            'notes'                     => 'nullable|string|max:1000',
            'logo_file_id'              => 'nullable|integer|exists:temporary_files,id',
            'contactPersons.*.name'     => 'required|string|max:255',
            'contactPersons.*.email'    => 'nullable|email|max:255',
            'contactPersons.*.phone'    => 'nullable|string|max:255',
            'contactPersons.*.position' => 'nullable|string|max:255',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'client_name.required'           => 'The client name is required.',
            'client_name'                    => 'The client name is invalid.',
            'clientType.required'            => 'The client type is required.',
            'clientType.in'                  => 'The client type must be either individual or business.',
            'cui'                            => 'The CUI is invalid.',
            'registrationNumber'             => 'The registration number is invalid.',
            'cnp'                            => 'The CNP is invalid.',
            'email'                          => 'The email address is invalid.',
            'phone'                          => 'The phone number is invalid.',
            'address'                        => 'The address is invalid.',
            'country'                        => 'The country is invalid.',
            'county'                         => 'The county is invalid.',
            'city'                           => 'The city is invalid.',
            'iban'                           => 'The IBAN is invalid.',
            'swift'                          => 'The SWIFT code is invalid.',
            'bank'                           => 'The bank name is invalid.',
            'currency'                       => 'The currency is invalid.',
            'notes'                          => 'The notes are invalid.',
            'logo_file_id'                   => 'The uploaded logo file is invalid.',
            'contactPersons.*.name.required' => 'The contact person name is required.',
            'contactPersons.*.name'          => 'The contact person name is invalid.',
            'contactPersons.*.email'         => 'The contact person email address is invalid.',
            'contactPersons.*.phone'         => 'The contact person phone number is invalid.',
            'contactPersons.*.function'      => 'The contact person function is invalid.',
        ];
    }
}
