<?php

namespace App\Http\Controllers\Invoices;

use App\Http\Controllers\Controller;
use App\Models\Invoices;
use App\Models\ProductsToInvoice;
use App\Models\StornoInvoices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StornoInvoiceFormController extends Controller
{
    const BLACKLIST_STATUSES = ['draft', 'anulled', 'storno'];

    private int $invoiceId;
    private ?Invoices $invoice;
    private ?Invoices $stornoInvoice;

    public function __construct(private Request $request)
    {
        if ($this->request->input('id')) {
            $this->invoiceId = (int) $this->request->input('id');
            $this->invoice = Invoices::find($this->invoiceId);
        }
    }

    public function __invoke()
    {
        if (blank($this->invoice)) {
            return redirect()->back()->with(['error' => 'Invoice not found.']);
        }

        if (in_array($this->invoice->status, self::BLACKLIST_STATUSES)) {
            return redirect()->back()->with(['error' => 'You cannot storno this invoice.']);
        }

        try {
            DB::beginTransaction();

            $this->createStornoInvoice();
            
            $products = $this->getInvoiceProducts();
            $this->addCancelledProducts($products);

            $this->cancelOriginalInvoice();

            DB::commit();
        }
        catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with(['error' => 'Failed to create storno invoice.']);
        }

        return redirect()->route('invoices.details', [
            'id' => $this->stornoInvoice->id
        ])->with(['success' => 'Storno invoice created successfully.']);
    }

    private function getInvoiceProducts(): array
    {
        return ProductsToInvoice::where('invoice_id', $this->invoiceId)
            ->get()
            ->toArray();
    }

    private function createStornoInvoice()
    {
        $this->stornoInvoice = $this->invoice->replicate();

        $this->stornoInvoice->status = 'storno';
        $this->stornoInvoice->created_by = $this->request->user()->id;

        $this->stornoInvoice->total = -$this->invoice->total;
        $this->stornoInvoice->total_no_vat = -$this->invoice->total_no_vat;
        $this->stornoInvoice->vat_amount = -$this->invoice->vat_amount;

        $this->stornoInvoice->save();

        StornoInvoices::create([
            'original_invoice_id' => $this->invoice->id,
            'storno_invoice_id'   => $this->stornoInvoice->id,
        ]);
    }

    private function addCancelledProducts(array $products): void
    {
        foreach ($products as $product) {
            $product['invoice_id'] = $this->stornoInvoice->id;
            $product['quantity'] = -$product['quantity'];
            $product['price'] = -$product['price'];
            $product['converted_price'] = -$product['converted_price'];
            $product['total'] = -$product['total'];
            $product['total_no_vat'] = -$product['total_no_vat'];
            $product['vat_amount'] = -$product['vat_amount'];

            ProductsToInvoice::create($product);
        }
    }

    private function cancelOriginalInvoice(): void
    {
        $this->invoice->status = 'anulled';
        $this->invoice->save();
    }
}
