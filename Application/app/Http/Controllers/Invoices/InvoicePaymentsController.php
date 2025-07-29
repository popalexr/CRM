<?php
namespace App\Http\Controllers\Invoices;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoices;
use Illuminate\Support\Facades\DB;
use App\Models\InvoicesPayments;

class InvoicePaymentsController extends Controller
{
    public function store(Request $request, $invoiceId)
    {
        if ($request->input('_method') === 'delete') {
            $paymentId = $request->input('payment_id');
            if ($paymentId) {
                \App\Models\InvoicesPayments::where('id', $paymentId)->where('invoice_id', $invoiceId)->delete();
            }
            return redirect()->route('invoices.details', ['id' => $invoiceId]);
        }

       $validated= $request->validate([
            'amount_paid' => 'required|numeric|min:0.01',
            'currency' => 'required|string|max:3',
            'paid_at' => 'required|date',
        ]);
         $convertedAmount = $validated['amount_paid'];


        \App\Models\InvoicesPayments::create([
            'invoice_id' => $invoiceId,
            'amount_paid' => $request->input('amount_paid'),
            'currency' => $request->input('currency'),
            'paid_at' => $request->input('paid_at'),
            'converted_amount_paid' => $convertedAmount, 
            'converted_currency' => 'RON',
        ]);

        return redirect()->route('invoices.details', ['id' => $invoiceId]);
    }
}
