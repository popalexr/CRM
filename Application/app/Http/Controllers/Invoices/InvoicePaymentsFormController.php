<?php

namespace App\Http\Controllers\Invoices;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invoices\PaymentsFormRequest;
use App\Models\Invoices;
use App\Models\InvoicesPayments;
use App\Services\CurrencyConverter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InvoicePaymentsFormController extends Controller
{
    public function __invoke(PaymentsFormRequest $request)
    {
        $validated = $request->validated();
        $invoiceId = $validated['invoice_id'];
        $amountPaid = $validated['amount_paid'];
        $currency = $validated['currency'];
        $id = $request->input('id', 0);

        $invoice = Invoices::findOrFail($invoiceId);
        $convertedAmount = $amountPaid;
        $convertedCurrency = $invoice->currency;

        if ($currency !== $convertedCurrency) {
            $convertedAmount = app(CurrencyConverter::class)->convert($amountPaid, $currency, $convertedCurrency);
        }

        if ($id !== 0) {
            $payment = InvoicesPayments::findOrFail($id);
            $payment->update([
                'invoice_id' => $invoiceId,
                'amount_paid' => $amountPaid,
                'currency' => $currency,
                'converted_amount_paid' => $convertedAmount,
                'converted_currency' => $convertedCurrency,
            ]);
        } else {
            InvoicesPayments::create([
                'invoice_id' => $invoiceId,
                'amount_paid' => $amountPaid,
                'currency' => $currency,
                'converted_amount_paid' => $convertedAmount,
                'converted_currency' => $convertedCurrency,
            ]);
        }

        return response()->json(['success' => true]);
    }
}
