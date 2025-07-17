<?php

namespace App\Http\Requests\Users;

use Illuminate\Foundation\Http\FormRequest;

class UsersFormRequest extends FormRequest
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
            'name'              => 'required|string|max:255',
            'email'             => 'required|email|max:255|unique:users,email',
            'phone'             => 'nullable|string|max:255',
            'birth_date'        => 'nullable|date',
            'address'           => 'nullable|string|max:255',
            'city'              => 'nullable|string|max:255',
            'county'            => 'nullable|string|max:255',
            'country'           => 'nullable|string|max:255',
            'avatar_file_id'    => 'nullable|integer|exists:temporary_files,id',
            'permissions'       => 'array',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required'         => 'The name is required.',
            'name'                  => 'The name is invalid.',
            'email.required'        => 'The email is required.',
            'email.email'           => 'The email must be a valid email address.',
            'email.unique'          => 'The email has already been taken.',
            'phone'                 => 'The phone number is invalid.',
            'birth_date'            => 'The birth date must be a valid date.',
            'address'               => 'The address is invalid.',
            'city'                  => 'The city is invalid.',
            'county'                => 'The county is invalid.',
            'country'               => 'The country is invalid.',
        ];
    }
}
