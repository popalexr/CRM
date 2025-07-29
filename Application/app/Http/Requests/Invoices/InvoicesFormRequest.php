<?php

namespace App\Http\Requests\Invoices;

use App\Rules\Invoices\EnoughStockRule;
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
            'currency' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (!in_array($value, array_keys(config('currencies')))) {
                        $fail('The selected currency is invalid.');
                    }
                },
            ],
            'payment_deadline' => 'required|date',
            'total' => 'required|numeric',
            'total_no_vat' => 'required|numeric',
            'vat_amount' => 'required|numeric',
            'products.*.id' => 'nullable|integer|exists:products,id',
            'products.*.name' => 'required|string|max:255',
            'products.*.price' => 'required|numeric',
            'products.*.currency' => 'required|string',
            'products.*.converted_price' => 'nullable|numeric',
            'products.*.converted_currency' => 'nullable|string',
            'products.*.vat' => 'nullable|numeric|min:0|max:100',
            'products.*.vat_amount' => 'nullable|numeric',
            'products.*.quantity' => [
                'required',
                'numeric',
                new EnoughStockRule,
            ],
            'products.*.unit' => 'required|string|max:50',
            'products.*.type' => 'required|string|in:product,service',
            'products.*.total_no_vat' => 'nullable|numeric',
            'products.*.total' => 'nullable|numeric',
            
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
            'total' => 'The total field is invalid.',
            'total_no_vat' => 'The total without VAT field is invalid.',
            'vat_amount' => 'The VAT amount field is invalid.',
            'products.*.id' => 'The product ID is invalid.',
            'products.*.name' => 'The product name is invalid.',
            'products.*.price' => 'The product price is invalid.',
            'products.*.currency' => 'The product currency is invalid.',
            'products.*.converted_price' => 'The converted price field is invalid.',
            'products.*.converted_currency' => 'The converted currency field is invalid.',
            'products.*.quantity.required' => 'The product quantity is invalid.',
            'products.*.quantity.numeric' => 'The product quantity is invalid.',
            'products.*.unit' => 'The product unit field is invalid.',
            'products.*.type' => 'The product type field is invalid.',
            'products.*.vat' => 'The VAT field is invalid.',
            'products.*.vat_amount' => 'The VAT amount field is invalid.',
            'products.*.total_no_vat' => 'The total without VAT field is invalid.',
            'products.*.total' => 'The total field is invalid.',
            
        ];
    }
}
