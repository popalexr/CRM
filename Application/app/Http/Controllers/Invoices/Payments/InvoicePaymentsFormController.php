<?php

namespace App\Http\Controllers\Invoices\Payments;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invoices\PaymentsFormRequest;
use App\Models\Invoices;
use App\Models\InvoicesPayments;
use App\Services\CurrencyConverter;
use Illuminate\Http\Request;

class InvoicePaymentsFormController extends Controller
{
    const BLACKLIST_STATUSES = [
        'draft',
        'anulled',
        'storno'
    ];

    private int $invoiceId;
    private int $invoicePaymentId;
    private ?Invoices $invoice;
    private ?InvoicesPayments $invoicePayment;

    public function __construct(private Request $request)
    {
        $this->invoiceId = (int) $request->input('id', 0);
        $this->invoice = Invoices::find($this->invoiceId);
        $this->invoicePaymentId = (int) $request->input('payment_id', 0);
        $this->invoicePayment = InvoicesPayments::find($this->invoicePaymentId);
    }

    /**
     * Handle the POST request to insert or edit invoice payments.
     */
    public function __invoke(PaymentsFormRequest $formRequest)
    {
        if (blank($this->invoiceId)) {
            return response()->json(['error' => 'Invoice ID is required.'], 400);
        }

        if (blank($this->invoice)) {
            return response()->json(['error' => 'Invoice not found.'], 400);
        }

        if (in_array($this->invoice->status, self::BLACKLIST_STATUSES)) {
            return response()->json(['error' => 'You cannot add payments for this invoice.'], 400);
        }

        if ($this->invoicePaymentId && blank($this->invoicePayment)) {
            return response()->json(['error' => 'Payment not found.'], 400);
        }

        if ($this->invoicePaymentId) {
            $this->handleUpdatePayment($formRequest);

            return response()->json(['success' => 'Payment updated successfully.'], 200);
        } else {
            $this->handleCreatePayment($formRequest);

            return response()->json(['success' => 'Payment created successfully.'], 200);
        }
    }

    private function handleUpdatePayment(PaymentsFormRequest $formRequest)
    {
        $this->invoicePayment->amount_paid = $formRequest->input('amount_paid');
        $this->invoicePayment->paid_at = $formRequest->input('payment_date');
        $this->invoicePayment->currency = $formRequest->input('currency');

        $this->invoicePayment->converted_amount_paid = CurrencyConverter::convert(
            value: $formRequest->input('amount_paid'),
            currency: $formRequest->input('currency'),
            to_currency: $this->invoice->currency
        );
        
        $this->invoicePayment->converted_currency = $this->invoice->currency;

        $this->invoicePayment->save();
    }

    private function handleCreatePayment(PaymentsFormRequest $formRequest)
    {
        $payment = new InvoicesPayments();
        $payment->invoice_id = $this->invoiceId;
        $payment->amount_paid = $formRequest->input('amount_paid');
        $payment->paid_at = $formRequest->input('payment_date');
        $payment->currency = $formRequest->input('currency');

        $payment->converted_amount_paid = CurrencyConverter::convert(
            value: $formRequest->input('amount_paid'),
            currency: $formRequest->input('currency'),
            to_currency: $this->invoice->currency
        );
        
        $payment->converted_currency = $this->invoice->currency;

        $payment->save();
    }
}
