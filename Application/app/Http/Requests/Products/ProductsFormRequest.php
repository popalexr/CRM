<?php

namespace App\Http\Requests\Products;

use Illuminate\Foundation\Http\FormRequest;

class ProductsFormRequest extends FormRequest
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
            'name'          => 'required|string|max:255',
            'type'          => 'required|in:' . $this->getProductTypes(),
            'price'         => 'required|numeric',
            'currency'      => 'required|string|max:10',
            'vat_id'        => 'required|integer|exists:vat,id',
            'unit'          => 'nullable|string|max:50',
            'quantity'      => 'nullable|numeric',
            'description'   => 'nullable|string',
            'image_file_id' => 'nullable|integer|exists:temporary_files,id',
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required'     => 'The product name is required.',
            'name'              => 'The product name is invalid.',
            'type.required'     => 'The product type is required.',
            'type'              => 'The product type is invalid.',
            'price.required'    => 'The product price is required.',
            'price'             => 'The product price is invalid.',
            'currency.required' => 'The product currency is required.',
            'currency'          => 'The product currency is invalid.',
            'vat_id.required'   => 'The VAT is required.',
            'vat_id'            => 'The selected VAT is invalid.',
            'unit'              => 'The product unit is invalid.',
            'quantity'          => 'The product quantity is invalid.',
            'description'       => 'The product description is invalid.',
            'image_file_id'     => 'The product image is invalid.',
        ];
    }

    /**
     * Get product types for validation.
     */
    private function getProductTypes(): string
    {
        return implode(
            separator: ',',
            array: array_keys(
                config('products.product_types')
            )
        );
    }
}
