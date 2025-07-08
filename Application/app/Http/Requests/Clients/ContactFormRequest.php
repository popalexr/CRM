<?php

namespace App\Http\Requests\Clients;

use Illuminate\Foundation\Http\FormRequest;

class ContactFormRequest extends FormRequest
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
        return [
            'contact_name' => 'required|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'contact_position' => 'nullable|string|max:255',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'contact_name.required' => 'Contact name is required.',
            'contact_name.string' => 'Contact name must be a string.',
            'contact_name.max' => 'Contact name may not be greater than 255 characters.',
            'contact_email.email' => 'Contact email must be a valid email address.',
            'contact_email.max' => 'Contact email may not be greater than 255 characters.',
            'contact_phone.string' => 'Contact phone must be a string.',
            'contact_phone.max' => 'Contact phone may not be greater than 20 characters.',
            'contact_position.string' => 'Contact position must be a string.',
            'contact_position.max' => 'Contact position may not be greater than 255 characters.',
        ];
    }
}
