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
        return [
            'company_name' => Settings::get('company_name', ''),
            'company_type' => Settings::get('company_type', ''),
            'address' => Settings::get('address', ''),
            'city' => Settings::get('city', ''),
            'county' => Settings::get('county', ''),
            'country' => Settings::get('country', ''),
            'email' => Settings::get('email', ''),
            'phone' => Settings::get('phone', ''),
            'iban' => Settings::get('iban', ''),
            'bank' => Settings::get('bank', ''),
            'swift' => Settings::get('swift', ''),
            'vat_payer' => Settings::get('vat_payer', true),
            'cui' => Settings::get('cui', ''),
            'registration_number' => Settings::get('registration_number', ''),
            'logo' => Settings::get('logo'),
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
