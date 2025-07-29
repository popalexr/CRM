<?php

namespace App\Http\Controllers\Invoices;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invoices\InvoiceStatusFormRequest;
use App\Models\Invoices;
use Illuminate\Http\Request;

class InvoiceStatusFormController extends Controller
{
    const BLACKLIST_STATUSES = [
        'storno'
    ];

    private int $invoiceId;
    private ?Invoices $invoice;

    public function __construct(private Request $request)
    {
        $this->invoiceId = (int) $request->input('id', 0);
        $this->invoice = Invoices::find($this->invoiceId);
    }

    /**
     * Handle the POST request to update the invoice status.
     */
    public function __invoke(InvoiceStatusFormRequest $formRequest)
    {
        if (blank($this->invoiceId) || blank($this->invoice)) {
            return redirect()->back()->with(['error' => 'Invoice not found.']);
        }

        if (in_array($this->invoice->status, self::BLACKLIST_STATUSES)) {
            return redirect()->back()->with(['error' => 'You cannot change the status of this invoice.']);
        }

        $this->invoice->status = $formRequest->input('status');
        $this->invoice->save();

        return redirect()->back()->with(['success' => 'Invoice status updated successfully.']);
    }
}
