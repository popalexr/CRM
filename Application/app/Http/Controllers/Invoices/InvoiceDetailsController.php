<?php

namespace App\Http\Controllers\Invoices;

use App\Http\Controllers\Controller;
use App\Models\Invoices;
use App\Models\ProductsToInvoice;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InvoiceDetailsController extends Controller
{
    private int $id;
    private ?Invoices $invoice;
    
    public function __construct(private Request $request)
    {
        $this->id = (int) $request->input('id', 0);

        $this->invoice = Invoices::find($this->id);
    }

    public function __invoke()
    {
        if (! $this->id || blank($this->invoice)) {
            return redirect()->route('invoices.index')->with(['error' => 'Invoice not found.']);
        }

        $invoiceData = $this->invoice->toArray();

        $products = ProductsToInvoice::where('invoice_id', $this->id)
            ->orderBy('id', 'asc')
            ->get()
            ->toArray();

        return Inertia::render('Invoices/Show', [
            'invoice' => $invoiceData,
            'products' => $products,
        ]);
    }
}
