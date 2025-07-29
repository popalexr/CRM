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
            'id' => 'nullable|integer',
            'invoice_id' => 'required|integer|exists:invoices,id',
            'amount_paid' => 'required|numeric|min:0.01',
            'currency' => 'required|string|max:3',
        ];
    }
}
