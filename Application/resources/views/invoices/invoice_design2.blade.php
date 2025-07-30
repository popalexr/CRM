@extends('layouts.invoice')

@section('content')
<div class="rounded-xl shadow-2xl border border-gray-200 bg-white text-black w-full max-w-4xl mx-auto p-0 print-no-shadow print-no-border">
    <div class="bg-orange-500 h-4 w-full rounded-t-xl"></div>
    <div class="flex justify-between items-center px-10 pt-10 pb-2">
        <div class="flex flex-col gap-1">
            <div class="w-16 h-16 flex items-center justify-center bg-gray-300 text-white text-2xl font-bold uppercase rounded-full overflow-hidden mb-2">
                @if($logoUrl)
                    <img src="{{ $logoUrl }}" alt="Logo" class="w-full h-full object-contain" />
                @else
                    <img src="/logo.png" alt="Logo" class="w-full h-full object-contain" />
                @endif
            </div>
            <span class="font-bold text-lg">{{ $companyInfo['company_name'] ?? '-' }}</span>
        </div>
        <div class="flex flex-col items-end gap-1">
            <span class="uppercase tracking-widest text-2xl font-bold">INVOICE</span>
            <span class="text-xs text-gray-500">Date: {{ $invoice['created_at'] ? \Carbon\Carbon::parse($invoice['created_at'])->format('Y-m-d') : '-' }}</span>
        </div>
    </div>
    <div class="flex justify-between items-center bg-orange-100 px-10 py-2 mt-2">
        <div class="text-xs font-semibold text-orange-700">Invoice #{{ $invoice['id'] }}</div>
        <div class="text-xs font-semibold text-orange-700">Due: {{ $invoice['payment_deadline'] }}</div>
    </div>
    <div class="flex flex-row justify-between px-10 py-4 gap-8">
        <div class="flex-1 text-xs">
            <div class="font-bold text-sm mb-1 text-gray-500">Furnizor</div>
            <div class="font-semibold">{{ $companyInfo['company_name'] ?? '-' }}</div>
            <div>{{ $companyInfo['address'] ?? '-' }}</div>
            <div>IBAN: {{ $companyInfo['iban'] ?? '-' }}</div>
            <div>Bank: {{ $companyInfo['bank'] ?? '-' }}</div>
            <div>CUI: {{ $companyInfo['cui'] ?? '-' }}</div>
            <div>Nr. Înreg.: {{ $companyInfo['registration_number'] ?? '-' }}</div>
            <div>Email: {{ $companyInfo['email'] ?? '-' }}</div>
            <div>Phone: {{ $companyInfo['phone'] ?? '-' }}</div>
        </div>
        <div class="flex-1 text-xs text-right">
            <div class="font-bold text-sm mb-1 text-gray-500">Cumpărător</div>
            <div class="font-semibold">{{ $invoice['client_name'] ?? '-' }}</div>
            <div>{{ $invoice['client_address'] ?? '-' }}</div>
            <div>
                @if($invoice['client_type'] === 'individual')
                    CNP: {{ $invoice['client_vat_code'] ?? '-' }}
                @else
                    CUI: {{ $invoice['client_vat_code'] ?? '-' }}
                @endif
            </div>
            <div>Nr. Înreg.: {{ $invoice['client_registration_number'] ?? '-' }}</div>
            <div>Bank: {{ $invoice['client_bank'] ?? '-' }}</div>
            <div>IBAN: {{ $invoice['client_iban'] ?? '-' }}</div>
            <div>Phone: {{ $invoice['client_phone'] ?? '-' }}</div>
        </div>
    </div>

    <div class="px-10">
        <table class="w-full text-xs border-t border-gray-200">
            <thead>
                <tr class="bg-orange-100 text-orange-700">
                    <th class="py-2 px-2 text-left font-bold">#</th>
                    <th class="py-2 px-2 text-left font-bold">Item Description</th>
                    <th class="py-2 px-2 text-right font-bold">Price</th>
                    <th class="py-2 px-2 text-right font-bold">Qty</th>
                    <th class="py-2 px-2 text-right font-bold">Total</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr class="border-b border-gray-100">
                        <td class="py-2 px-2">{{ $loop->iteration }}</td>
                        <td class="py-2 px-2">{{ $product['product_name'] }}</td>
                        <td class="py-2 px-2 text-right">{{ number_format($product['converted_price'], 2, '.', ',') }}</td>
                        <td class="py-2 px-2 text-right">{{ $product['quantity'] }}</td>
                        <td class="py-2 px-2 text-right">{{ number_format($product['total'] - ($product['discount'] ?? 0), 2, '.', ',') }}</td>
                    </tr>
                @empty
                     <tr>
                        <td colspan="5" class="py-2 px-2 text-center text-gray-400">No items</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="flex flex-row justify-end px-10 py-4">
        <div class="w-full max-w-xs ml-auto">
            <div class="flex justify-between text-sm mb-1">
                <span class="text-gray-600">Sub Total</span>
                <span class="font-semibold">{{ number_format($subtotal, 2, '.', ',') }} {{ $invoice['currency'] }}</span>
            </div>
            <div class="flex justify-between text-sm mb-1">
                <span class="text-gray-600">Discount</span>
                <span class="font-semibold">{{ number_format($totalDiscount, 2, '.', ',') }} {{ $invoice['currency'] }}</span>
            </div>
            <div class="flex justify-between text-sm mb-1">
                <span class="text-gray-600">VAT</span>
                <span class="font-semibold">{{ number_format($totalVat, 2, '.', ',') }} {{ $invoice['currency'] }}</span>
            </div>
            <div class="flex justify-between text-base font-bold bg-orange-100 text-orange-700 rounded px-2 py-1 mt-2">
                <span>Total</span>
                <span>{{ number_format($invoice['total'], 2, '.', ',') }} {{ $invoice['currency'] }}</span>
            </div>
        </div>
    </div>
    <div class="flex justify-between items-center px-10 py-4 border-t border-gray-200 mt-2">
        <div class="text-xs text-gray-500">
            <div>Payment info: {{ $companyInfo['iban'] ?? '-' }} / {{ $companyInfo['bank'] ?? '-' }}</div>
            <div>Terms & Conditions</div>
        </div>
        <div class="text-xs text-right font-semibold">Authorised Sign</div>
    </div>
    <div class="bg-orange-500 h-6 w-full flex items-center justify-center text-white text-xs font-semibold tracking-wider rounded-b-xl"></div>
</div>
@endsection