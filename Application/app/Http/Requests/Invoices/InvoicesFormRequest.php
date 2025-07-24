<?php

namespace App\Http\Requests\Invoices;

use Illuminate\Foundation\Http\FormRequest;

class InvoicesFormRequest extends FormRequest
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
            'client_id' => 'required|integer|exists:clients,id',
            'currency' => 'required|string',
            'payment_deadline' => 'required|date',
            'products.*.name' => 'required|string|max:255',
            'products.*.price' => 'required|numeric',
            'products.*.currency' => 'required|string',
            'products.*.vat' => 'nullable|numeric|min:0|max:100',
            'products.*.quantity' => 'required|numeric',
            'products.*.unit' => 'required|string|max:50',
            'products.*.type' => 'required|string|in:product,service',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'client_id.required' => 'The client field is required.',
            'client_id' => 'The selected client is invalid.',
            'currency.required' => 'The currency field is required.',
            'currency' => 'The currency is invalid.',
            'payment_deadline.required' => 'The payment deadline field is required.',
            'payment_deadline' => 'The payment deadline must be a valid date.',
            'products.*.name' => 'The product name is invalid.',
            'products.*.price' => 'The product price is invalid.',
            'products.*.currency' => 'The product currency is invalid.',
            'products.*.quantity' => 'The product quantity is invalid.',
            'products.*.unit' => 'The product unit field is invalid.',
            'products.*.type' => 'The product type field is invalid.',
        ];
    }
}
