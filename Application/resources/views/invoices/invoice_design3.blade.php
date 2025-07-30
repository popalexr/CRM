@extends('layouts.invoice')

@section('content')
<div class="rounded-2xl shadow-2xl border border-gray-200 bg-white text-black max-w-4xl mx-auto p-0 print-no-shadow print-no-border">
    <div class="flex flex-col md:flex-row md:justify-between md:items-center p-10 gap-8 border-b border-gray-200">
        <div class="flex flex-col gap-2">
            <div class="text-3xl font-mono font-bold uppercase tracking-tight">INVOICE</div>
            <div class="flex flex-row gap-6 mt-2">
                <div class="text-xs">No: <span class="font-bold">{{ $invoice['id'] }}</span></div>
                <div class="text-xs">Date: <span class="font-bold">{{ $invoice['created_at'] ? \Carbon\Carbon::parse($invoice['created_at'])->format('Y-m-d') : '-' }}</span></div>
                <div class="text-xs">Due: <span class="font-bold">{{ $invoice['payment_deadline'] }}</span></div>
            </div>
        </div>
        <div class="w-16 h-16 flex items-center justify-center bg-gray-300 text-white text-2xl font-bold uppercase rounded-full overflow-hidden">
            @if($logoUrl)
                <img src="{{ $logoUrl }}" alt="Company Logo" class="w-full h-full object-contain" />
            @else
                <img src="/logo.png" alt="Company Logo" class="w-full h-full object-contain" />
            @endif
        </div>
    </div>
    <div class="flex flex-col md:flex-row gap-10 p-10 border-b border-gray-200">
        <div class="flex-1">
            <div class="font-bold">Furnizor</div>
            <div class="text-xs">{{ $companyInfo['company_name'] ?? '-' }}</div>
            <div class="text-xs">{{ $companyInfo['address'] ?? '-' }}</div>
            <div class="text-xs">Email: {{ $companyInfo['email'] ?? '-' }}</div>
            <div class="text-xs">IBAN: {{ $companyInfo['iban'] ?? '-' }}</div>
            <div class="text-xs">Bank: {{ $companyInfo['bank'] ?? '-' }}</div>
            <div class="text-xs">CUI: {{ $companyInfo['cui'] ?? '-' }}</div>
            <div class="text-xs">Nr. Înreg.: {{ $companyInfo['registration_number'] ?? '-' }}</div>
            <div class="text-xs">Telefon: {{ $companyInfo['phone'] ?? '-' }}</div>
        </div>
        <div class="flex-1 flex flex-col items-start md:items-end md:text-right">
            <div class="font-bold">Cumpărător</div>
            <div class="text-xs">{{ $invoice['client_name'] ?? '-' }}</div>
            <div class="text-xs">{{ $invoice['client_address'] ?? '-' }}</div>
            <div class="text-xs">
                @if($invoice['client_type'] === 'individual')
                    CNP: {{ $invoice['client_vat_code'] ?? '-' }}
                @else
                    CUI: {{ $invoice['client_vat_code'] ?? '-' }}
                @endif
            </div>
            <div class="text-xs">Nr. Înreg.: {{ $invoice['client_registration_number'] ?? '-' }}</div>
            <div class="text-xs">Bank: {{ $invoice['client_bank'] ?? '-' }}</div>
            <div class="text-xs">IBAN: {{ $invoice['client_iban'] ?? '-' }}</div>
            <div class="text-xs">Telefon: {{ $invoice['client_phone'] ?? '-' }}</div>
        </div>
    </div>

    <div class="p-10">
        <div class="text-xs font-bold mb-2 uppercase tracking-widest">Products / Services</div>
        <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white">
            <table class="min-w-full text-xs">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-3 py-2 text-left">#</th>
                        <th class="px-3 py-2 text-left">Description</th>
                        <th class="px-3 py-2 text-right">Qty</th>
                        <th class="px-3 py-2 text-right">Unit Price</th>
                        <th class="px-3 py-2 text-right">VAT</th>
                        <th class="px-3 py-2 text-right">Discount</th>
                        <th class="px-3 py-2 text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td class="px-3 py-2">{{ $loop->iteration }}</td>
                            <td class="px-3 py-2">{{ $product['product_name'] }}</td>
                            <td class="px-3 py-2 text-right">{{ $product['quantity'] }}</td>
                            <td class="px-3 py-2 text-right">{{ number_format($product['converted_price'], 2, '.', ',') }}</td>
                            <td class="px-3 py-2 text-right">{{ number_format($product['total'] - $product['total_no_vat'], 2, '.', ',') }}</td>
                            <td class="px-3 py-2 text-right">{{ number_format($product['discount'] ?? 0, 2, '.', ',') }}</td>
                            <td class="px-3 py-2 text-right">{{ number_format($product['total'] - ($product['discount'] ?? 0), 2, '.', ',') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-3 py-2 text-center text-gray-400">No items</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="flex flex-col md:flex-row gap-8 p-10">
        <div class="flex-1 flex flex-col gap-1 text-sm">
            <span>Subtotal: <span class="font-bold">{{ number_format($subtotal, 2, '.', ',') }} {{ $invoice['currency'] }}</span></span>
            <span>Discount: <span class="font-bold">{{ number_format($totalDiscount, 2, '.', ',') }} {{ $invoice['currency'] }}</span></span>
            <span>VAT: <span class="font-bold">{{ number_format($totalVat, 2, '.', ',') }} {{ $invoice['currency'] }}</span></span>
            <span class="text-xl mt-2">Total: <span class="font-black">{{ number_format($invoice['total'], 2, '.', ',') }} {{ $invoice['currency'] }}</span></span>
        </div>
        <div class="flex-1 flex flex-col gap-1 items-start md:items-end text-xs">
            <div>Issued by <span class="font-bold">{{ $companyInfo['company_name'] ?? '-' }}</span></div>
            <div>Bank: {{ $companyInfo['bank'] ?? '-' }}</div>
            <div>IBAN: {{ $companyInfo['iban'] ?? '-' }}</div>
            <div class="mt-4">Signature</div>
            <div class="block w-40 h-10 border-b border-gray-400 mt-1"></div>
        </div>
    </div>
</div>
@endsection