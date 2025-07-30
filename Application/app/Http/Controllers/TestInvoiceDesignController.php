<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class TestInvoiceDesignController extends Controller
{
   
    private function getMockData(): array
    {
        $companyInfo = [
            'company_name' => 'Creative Solutions SRL',
            'logo' => 'https://tailwindui.com/img/logos/mark.svg?color=indigo&shade=600', 
            'address' => 'Str. Principală, Nr. 123',
            'city' => 'București',
            'county' => 'Sector 1',
            'country' => 'România',
            'email' => 'contact@creativesolutions.ro',
            'phone' => '0722 123 456',
            'iban' => 'RO49 AAAA 1B31 0075 9384 0000',
            'bank' => 'Banca Transilvania',
            'cui' => 'RO12345678',
            'registration_number' => 'J40/123/2022',
            'company_type' => 'srl'
        ];

        $invoice = [
            'id' => 2024001,
            'number' => 'CS-001',
            'created_at' => now()->toDateTimeString(),
            'due_date' => now()->addDays(15)->format('Y-m-d'),
            'currency' => 'RON',
            'client_name' => 'Client Exemplu SA',
            'client_address' => 'Bvd. Libertății, Nr. 10',
            'client_city' => 'Cluj-Napoca',
            'client_county' => 'Cluj',
            'client_country' => 'România',
            'client_vat_code' => 'RO87654321', 
            'client_registration_number' => 'J12/456/2020',
            'client_bank' => 'ING Bank',
            'client_iban' => 'RO08 INGB 0000 9999 1234 5678',
            'client_phone' => '0745 987 654',
            'client_type' => 'srl' 
        ];

        $products = [
            [
                'id' => 1,
                'product_name' => 'Web Design Services',
                'quantity' => 1,
                'converted_price' => 2500.00,
                'total_no_vat' => 2500.00,
                'total' => 2975.00, 
                'discount' => 100.00
            ],
            [
                'id' => 2,
                'product_name' => 'Monthly SEO Package',
                'quantity' => 3,
                'converted_price' => 840.34,
                'total_no_vat' => 2521.02,
                'total' => 3000.01, 
                'discount' => 0
            ],
            [
                'id' => 3,
                'product_name' => 'Logo Design',
                'quantity' => 1,
                'converted_price' => 500.00,
                'total_no_vat' => 500.00,
                'total' => 595.00, 
                'discount' => 50.00
            ]
        ];

       
        $subtotal = array_reduce($products, fn($sum, $p) => $sum + $p['total_no_vat'], 0);
        $totalDiscount = array_reduce($products, fn($sum, $p) => $sum + ($p['discount'] ?? 0), 0);
        $totalVat = array_reduce($products, fn($sum, $p) => $sum + ($p['total'] - $p['total_no_vat']), 0);
        $total = array_reduce($products, fn($sum, $p) => $sum + $p['total'] - ($p['discount'] ?? 0), 0);
        
        $invoice['total'] = $total;

        
        $logoUrl = $companyInfo['logo'] ?? null;
        $companyInitial = !empty($companyInfo['company_name']) ? strtoupper($companyInfo['company_name'][0]) : '?';


        return compact('companyInfo', 'invoice', 'products', 'subtotal', 'totalVat', 'totalDiscount', 'logoUrl', 'companyInitial');
    }

    public function design1(): View
    {
        return view('invoices.invoice_design1', $this->getMockData());
    }



    public function design2(): View
    {
        return view('invoices.invoice_design2', $this->getMockData());
    }

    public function design3(): View
    {
        return view('invoices.invoice_design3', $this->getMockData());
    }
}