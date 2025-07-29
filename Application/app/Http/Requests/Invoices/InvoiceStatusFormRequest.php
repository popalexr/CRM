<?php

namespace App\Http\Requests\Invoices;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceStatusFormRequest extends FormRequest
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
            'status' => 'required|string|in:draft,submitted,paid,not_paid,anulled,storno',
        ];
    }

    /**
     * Get the custom messages for the validation rules.
     */
    public function messages(): array
    {
        return [
            'status.required' => 'The status is required.',
            'status'          => 'The selected status is invalid.',
        ];
    }
}
