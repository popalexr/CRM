<?php

namespace App\Http\Controllers\Invoices;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invoices\InvoicesFormRequest;
use App\Models\Clients;
use App\Models\Invoices;
use App\Models\ProductsToInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class InvoicesFormController extends Controller
{
    private int $id;
    private ?Invoices $invoice = null;

    public function __construct(private Request $request)
    {
        $this->id = (int) $request->input('id', 0);

        if ($this->id) {
            $this->invoice = Invoices::find($this->id);
        }
    }

    public function __invoke()
    {
        if ($this->id && blank($this->invoice)) {
            return redirect()->route('invoices.index')->with(['error' => 'Invoice not found.']);
        }

        if (!$this->id) {
            return Inertia::render('Invoices/Create');
        }
    }

    public function post(InvoicesFormRequest $formRequest)
    {
        if ($this->id && blank($this->invoice)) {
            return redirect()->route('invoices.index')->with(['error' => 'Invoice not found.']);
        }

        if ($this->id < 1) {
            $this->handleCreateInvoice($formRequest);
            return redirect()->route('invoices.index')->with(['success' => 'Invoice created successfully.']);
        }

    }

    private function handleCreateInvoice(InvoicesFormRequest $formRequest)
    {
        try {
            DB::beginTransaction();

            $this->createInvoice($formRequest);
            $this->addProductsToInvoice($formRequest);

            $products = ProductsToInvoice::where('invoice_id', $this->invoice->id)->get();
            $total = 0;
            foreach ($products as $product) {
                $lineTotal = ($product->price ?? 0) * ($product->quantity ?? 1);
                $total += $lineTotal + ($product->vat ?? 0);
            }
            $this->invoice->total = $total;
            $this->invoice->save();

            DB::commit();
        }
        catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'Failed to create invoice.']);
        }
    }

    private function createInvoice(InvoicesFormRequest $formRequest)
    {
        $client = $this->getClientInfo($formRequest->input('client_id'));

        $invoiceData = [
            'client_id' => $client->id,
            'created_by' => request()->user()->id,
            'currency' => $formRequest->input('currency'),
            'vat_payer' => $client->client_tva,
            'payment_deadline' => $formRequest->input('payment_deadline'),
        ];

        $this->invoice = Invoices::create($invoiceData);
    }

    private function addProductsToInvoice(InvoicesFormRequest $formRequest)
    {
        $products = $formRequest->input('products', []);

        foreach ($products as $product) {
            $productElement = [
                'invoice_id' => $this->invoice->id,
                'product_name' => $product['name'],
                'product_type' => $product['type'],
                'price' => $product['price'],
                'currency' => $product['currency'],
                'converted_price' => 0.00,
                'converted_currency' => '',
                'quantity' => $product['quantity'],
                'unit' => $product['unit'],
                'vat' => $product['vat'] ?? 0.00,
                'total_no_vat' =>  0.00,
                'total' => 0.00,
            ];

            ProductsToInvoice::create($productElement);
        }
    }

    private function getClientInfo(int $clientId): Clients
    {
        $client = Clients::find($clientId);

        if (blank($client)) {
            throw new \Exception('Client not found.');
        }

        return $client;
    }
}
