<?php

namespace App\Http\Controllers\Invoices;

use App\Http\Controllers\Controller;
use App\Models\Clients;
use App\Models\Invoices;
use App\Models\ProductsToInvoice;
use App\Models\Settings;
use Illuminate\Http\Request;
use Spatie\LaravelPdf\Enums\Format;

use function Spatie\LaravelPdf\Support\pdf;

class PDFInvoiceController extends Controller
{
    private int $invoiceId;
    private int $designId;
    private ?Invoices $invoice = null;
    private ?Clients $client = null;

    public function __construct(private Request $request)
    {
        $this->invoiceId = (int) $request->input('id', 0);
        $this->designId = (int) $request->input('designId', 1);

        $this->invoice = Invoices::find($this->invoiceId);

        if ($this->invoice) {
            $this->client = Clients::find($this->invoice->client_id);
        }
    }

    public function __invoke()
    {
        if (!$this->invoiceId || blank($this->invoice)) {
            return redirect()->route('invoices.index')->with(['error' => 'Invoice not found.']);
        }

        if ($this->designId < 1 || $this->designId > 3) {
            return redirect()->route('invoices.index')->with(['error' => 'Invalid invoice design selected.']);
        }

        $data = $this->getInvoiceData();

        return pdf()->view('invoices.invoice_design' . $this->designId, $data)
            ->format(Format::A4)
            ->download('invoice_' . $this->invoice->id . '.pdf');
    }

    private function getInvoiceData(): array
    {
        $companyInfo = $this->getCompanyInfo();

        return [
            'companyInfo' => $companyInfo,
            'invoice' => $this->getInvoice(),
            'products' => $this->getInvoiceProducts(),
            'subtotal' => $this->invoice->total_no_vat,
            'totalDiscount' => 0,
            'totalVat' => $this->invoice->vat_amount,
            'total' => $this->invoice->total,
            'logoUrl' => $companyInfo['logo'] ?? null,
            'companyInitial' => $companyInfo['company_name'][0] ?? '-'
        ];
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

    private function getInvoice(): array
    {
        return array_merge(
            $this->invoice->toArray(),
            [
                'client_name' => $this->client->client_name,
                'client_address' => $this->client->address,
                'client_city' => $this->client->city,
                'client_county' => $this->client->county,
                'client_country' => $this->client->country,
                'client_vat_code' => $this->client->cui,
                'client_bank' => $this->client->bank_name,
                'client_iban' => $this->client->iban,
                'client_phone' => $this->client->client_phone,
                'client_email' => $this->client->client_email,
                'client_type' => $this->client->client_type,
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
}
