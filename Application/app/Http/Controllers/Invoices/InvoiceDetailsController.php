<?php

namespace App\Http\Controllers\Invoices;

use App\Http\Controllers\Controller;
use App\Models\Clients;
use App\Models\Invoices;
use App\Models\InvoicesPayments;
use App\Models\ProductsToInvoice;
use App\Models\Settings;
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

        return Inertia::render('Invoices/Show', [
            'invoice' => $this->getInvoiceInfo(),
            'products' => $this->getInvoiceProducts(),
            'companyInfo' => $this->getCompanyInfo(),
            'payments' => $this->getPayments(),
            'currencies' => config('currencies'),
        ]);
    }

    private function getInvoiceInfo(): array
    {
        $client = Clients::find($this->invoice->client_id);

        if (blank($client)) {
            return [];
        }

        return array_merge(
            $this->invoice->toArray(),
            [
                'client_name' => $client->client_name,
                'client_address' => $client->address,
                'client_city' => $client->city,
                'client_county' => $client->county,
                'client_country' => $client->country,
                'client_vat_code' => $client->cui,
                'client_bank' => $client->bank_name,
                'client_iban' => $client->iban,
                'client_phone' => $client->client_phone,
                'client_email' => $client->client_email,
            ]
        );
    }

    private function getInvoiceProducts(): array
    {
        return ProductsToInvoice::where('invoice_id', $this->invoice->id)
            ->orderBy('id', 'asc')
            ->get()
            ->toArray();
    }

    private function getCompanyInfo(): array
    {
        $settings = Settings::all()->keyBy('name');

        return [
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
    }

    private function getPayments(): array
    {
        return InvoicesPayments::where('invoice_id', $this->invoice->id)
            ->orderBy('paid_at', 'desc')
            ->get()
            ->toArray();
    }
}
