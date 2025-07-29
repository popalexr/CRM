<?php

namespace App\Http\Controllers\Invoices;

use App\Http\Controllers\Controller;
use App\Http\Requests\Invoices\InvoicesFormRequest;
use App\Models\Clients;
use App\Models\Invoices;
use App\Models\Products;
use App\Models\ProductsToInvoice;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

        if ($this->invoice && $this->invoice->status !== 'draft') {
            return redirect()->route('invoices.index')->with(['error' => 'You can only edit draft invoices.']);
        }

        if (!$this->id) {
            return Inertia::render('Invoices/Create',[
                'currencies' => config('currencies'),
                'vatPayer' => Settings::get('vat_payer', true),
            ]);
        }

        return Inertia::render('Invoices/Edit', [
            'invoice' => $this->getInvoiceInfo(),
            'products' => $this->getInvoiceProducts(),
            'currencies' => config('currencies'),
            'vatPayer' => Settings::get('vat_payer', true),
        ]);
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

        $this->handleUpdateInvoice($formRequest);
        return redirect()->route('invoices.index')->with(['success' => 'Invoice updated successfully.']);
    }

    private function getInvoiceProducts(): array
    {
        if (blank($this->invoice)) {
            return [];
        }

        return ProductsToInvoice::where('invoice_id', $this->invoice->id)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'product_id' => $product->product_id,
                    'name' => $product->product_name,
                    'type' => $product->product_type,
                    'price' => $product->price,
                    'currency' => $product->currency,
                    'converted_price' => $product->converted_price,
                    'converted_currency' => $product->converted_currency,
                    'quantity' => $product->quantity,
                    'unit' => $product->unit,
                    'vat' => $product->vat,
                    'total_no_vat' => $product->total_no_vat,
                    'total' => $product->total,
                ];
            })->toArray();
    }

    private function getInvoiceInfo(): array
    {
        $client = Clients::find($this->invoice->client_id);
        
        if (blank($client)) {
            return [];
        }

        return [
            'id' => $this->invoice->id,
            'client_id' => $this->invoice->client_id,
            'client_name' => $client->client_name,
            'created_by' => $this->invoice->created_by,
            'currency' => $this->invoice->currency,
            'total' => $this->invoice->total,
            'total_no_vat' => $this->invoice->total_no_vat,
            'vat_amount' => $this->invoice->vat_amount,
            'vat_payer' => $this->invoice->vat_payer,
            'payment_deadline' => $this->invoice->payment_deadline,
        ];
    }

    private function handleCreateInvoice(InvoicesFormRequest $formRequest)
    {
        try {
            DB::beginTransaction();

            $this->createInvoice($formRequest);
            $this->addProductsToInvoice($formRequest);
            $this->updateProductsStockOnCreation($formRequest);

            DB::commit();
        }
        catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with(['error' => 'Failed to create invoice.']);
        }
    }

    private function handleUpdateInvoice(InvoicesFormRequest $formRequest)
    {
        try {
            DB::beginTransaction();

            $this->editInvoice($formRequest);

            $this->updateProductsStockOnUpdate($formRequest);
            
            $this->deleteProductsFromInvoice();
            $this->addProductsToInvoice($formRequest);


            DB::commit();
        }
        catch (\Exception $e) {
            DB::rollBack();

            Log::error('Failed to update invoice', [
                'invoice_id' => $this->invoice->id,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()->with(['error' => 'Failed to update invoice.']);
        }
    }

    private function createInvoice(InvoicesFormRequest $formRequest)
    {
        $client = $this->getClientInfo($formRequest->input('client_id'));

        $invoiceData = [
            'client_id' => $client->id,
            'created_by' => request()->user()->id,
            'currency' => $formRequest->input('currency'),
            'total' => $formRequest->input('total'),
            'total_no_vat' => $formRequest->input('total_no_vat'),
            'vat_amount' => $formRequest->input('vat_amount'),
            'vat_payer' => $client->client_tva,
            'payment_deadline' => $formRequest->input('payment_deadline'),
        ];

        $this->invoice = Invoices::create($invoiceData);
    }

    private function editInvoice(InvoicesFormRequest $formRequest)
    {
        $this->invoice->client_id = $formRequest->input('client_id');
        $this->invoice->currency = $formRequest->input('currency');
        $this->invoice->payment_deadline = $formRequest->input('payment_deadline');
        $this->invoice->total = $formRequest->input('total');
        $this->invoice->total_no_vat = $formRequest->input('total_no_vat');
        $this->invoice->vat_amount = $formRequest->input('vat_amount');

        $this->invoice->save();
    }

    private function deleteProductsFromInvoice()
    {
        if (blank($this->invoice)) {
            return;
        }

        ProductsToInvoice::where('invoice_id', $this->invoice->id)->delete();
    }

    private function addProductsToInvoice(InvoicesFormRequest $formRequest)
    {
        $products = $formRequest->input('products', []);

        foreach ($products as $product) {
            $productElement = [
                'invoice_id' => $this->invoice->id,
                'product_id' => $product['id'] ?? null,
                'product_name' => $product['name'],
                'product_type' => $product['type'],
                'price' => $product['price'],
                'currency' => $product['currency'],
                'converted_price' => $product['converted_price'],
                'converted_currency' => $product['converted_currency'],
                'quantity' => $product['quantity'],
                'unit' => $product['unit'],
                'vat' => $product['vat'],
                'total_no_vat' =>  $product['total_no_vat'],
                'total' => $product['total'],
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

    private function updateProductsStockOnCreation(InvoicesFormRequest $formRequest): void
    {
        $products = $formRequest->input('products', []);

        foreach ($products as $product) {
            if ($product['type'] === 'service') {
                continue;
            }

            $productModel = Products::find($product['id']);

            if (blank($productModel)) {
                continue;
            }

            if ($productModel->quantity < $product['quantity']) {
                throw new \Exception("Not enough stock for product ID {$product['id']}.");
            }

            $productModel->quantity -= $product['quantity'];
            $productModel->save();
        }
    }

    private function updateProductsStockOnUpdate(InvoicesFormRequest $formRequest): void
    {
        $products = $formRequest->input('products', []);

        foreach ($products as $product) {
            if ($product['type'] === 'service') {
                continue;
            }

            if (!isset($product['id'])) {
                continue;
            }

            $productModel = Products::find($product['id']);

            if (blank($productModel)) {
                continue;
            }

            $currentInvoiceProduct = ProductsToInvoice::where('product_id', $product['id'])
                ->where('invoice_id', $this->invoice->id)
                ->first();

            if (blank($currentInvoiceProduct)) {
                continue;
            }

            $currentQuantity = $currentInvoiceProduct->quantity;
            $difference = $product['quantity'] - $currentQuantity;

            $productModel->quantity -= $difference;
            $productModel->save();
        }
    }
}
