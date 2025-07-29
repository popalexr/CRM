<?php

namespace App\Http\Requests\Invoices;

use Illuminate\Foundation\Http\FormRequest;

class PaymentsFormRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'amount_paid' => 'required|numeric|min:0.01',
            'payment_date' => 'required|date',
            'currency' => 'required|string|in:' . implode(',', array_keys(config('currencies'))),
        ];
    }

    public function messages()
    {
        return [
            'amount_paid.required'  => 'The amount paid is required.',
            'amount_paid'           => 'Invalid amount paid.',
            'payment_date.required' => 'The payment date is required.',
            'payment_date'          => 'The payment date is invalid.',
            'currency.required'     => 'The currency is required.',
            'currency'              => 'The currency is invalid.',
        ];
    }
}
