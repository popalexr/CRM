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

        $this->invoice->load('payments');

        $invoiceData = $this->invoice->toArray();
        $invoiceData['due_date'] = $this->invoice->payment_deadline;

        $client = $this->invoice->client_id ? \App\Models\Clients::find($this->invoice->client_id) : null;
        $invoiceData['client_name'] = $client?->client_name ?? '';
        $invoiceData['client_address'] = $client?->address ?? '';
        $invoiceData['client_city'] = $client?->city ?? '';
        $invoiceData['client_county'] = $client?->county ?? '';
        $invoiceData['client_country'] = $client?->country ?? '';
        $invoiceData['client_vat_code'] = $client?->cui ?? '';
        $invoiceData['client_bank'] = $client?->bank_name ?? '';
        $invoiceData['client_iban'] = $client?->iban ?? '';
        $invoiceData['client_phone'] = $client?->client_phone ?? '';
        $invoiceData['client_email'] = $client?->client_email ?? '';

        $products = ProductsToInvoice::where('invoice_id', $this->id)
            ->orderBy('id', 'asc')
            ->get()
            ->toArray();

        $settings = \App\Models\Settings::all()->keyBy('key');
        $companyInfo = [
            'company_name' => $settings->get('company_name')?->value ?? '',
            'company_type' => $settings->get('company_type')?->value ?? '',
            'address' => $settings->get('address')?->value ?? '',
            'city' => $settings->get('city')?->value ?? '',
            'county' => $settings->get('county')?->value ?? '',
            'country' => $settings->get('country')?->value ?? '',
            'email' => $settings->get('email')?->value ?? '',
            'phone' => $settings->get('phone')?->value ?? '',
            'iban' => $settings->get('iban')?->value ?? '',
            'bank' => $settings->get('bank')?->value ?? '',
            'swift' => $settings->get('swift')?->value ?? '',
            'vat_payer' => filter_var($settings->get('vat_payer')?->value ?? false, FILTER_VALIDATE_BOOLEAN),
        ];

        return Inertia::render('Invoices/Show', [
            'invoice' => $invoiceData,
            'products' => $products,
            'companyInfo' => $companyInfo,
            'currencies' => config('currencies'),
        ]);
    }
}
