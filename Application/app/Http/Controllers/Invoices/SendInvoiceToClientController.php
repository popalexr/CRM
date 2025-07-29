<?php

namespace App\Http\Controllers\Invoices;

use App\Http\Controllers\Controller;
use App\Models\Clients;
use App\Models\Invoices;
use App\Services\SendInvoice;
use Illuminate\Http\Request;

class SendInvoiceToClientController extends Controller
{
    private int $invoiceId;

    private ?Invoices $invoice = null;
    private ?Clients $client = null;

    public function __construct(private Request $request)
    {
        if ($this->request->input('id'))
        {
            $this->invoiceId = (int) $this->request->input('id');
            $this->invoice = Invoices::find($this->invoiceId);
            $this->client = Clients::find($this->invoice->client_id);
        }
    }

    public function __invoke()
    {
        if (blank($this->invoice)) {
            return redirect()->back()->with(['error' => 'Invoice not found.']);
        }

        if ($this->invoice->status !== 'draft') {
            return redirect()->back()->with(['error' => 'You can only send draft invoices.']);
        }

        // handle file ID

        $fileId = 1;

        $sent = SendInvoice::send($fileId, $this->client->id, $this->invoiceId);

        if ($sent) {
            $this->markInvoiceAsSubmitted();

            return redirect()->back()->with(['success' => 'Invoice sent successfully.']);
        } else {
            return redirect()->back()->with(['error' => 'Failed to send the invoice.']);
        }
    }

    private function markInvoiceAsSubmitted(): void
    {
        $this->invoice->status = 'submitted';
        $this->invoice->save();
    }
}
