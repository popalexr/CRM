@extends('layouts.invoice')

@section('content')
<div class="rounded-xl border border-gray-200 p-8 shadow-sm bg-white max-w-4xl mx-auto print-no-shadow print-no-border">
    <div class="flex justify-between items-start mb-6 flex-wrap gap-4">
        <div class="flex flex-col items-start justify-center gap-6 flex-1 min-w-[180px] text-left">
            <div>
                <div class="text-3xl font-bold mb-2">INVOICE</div>
                <div class="text-xs text-gray-500 mb-1">No: <span class="font-semibold text-gray-900">#{{ $invoice['id'] }}</span></div>
                <div class="text-xs text-gray-500 mb-1">Data: <span class="font-semibold text-gray-900">{{ $invoice['created_at'] ? \Carbon\Carbon::parse($invoice['created_at'])->format('Y-m-d') : '-' }}</span></div>
                <div class="text-xs text-gray-500 mb-1">Due Date: <span class="font-semibold text-gray-900">{{ $invoice['payment_deadline'] ?? '-' }}</span></div>
            </div>

            <div class="flex flex-col items-start text-left max-w-xs">
                <div class="text-sm font-semibold uppercase text-gray-500 mb-1">Furnizor</div>
                <div class="font-semibold text-gray-900">{{ $companyInfo['company_name'] ?? '-' }}</div>
                <div class="text-xs text-gray-500">Address: {{ $companyInfo['address'] ?? '-' }}</div>
                <div class="text-xs text-gray-500">
                    {{ $companyInfo['city'] ?? '' }}{{ ($companyInfo['city'] && $companyInfo['county']) ? ', ' : '' }}{{ $companyInfo['county'] ?? '' }}{{ (($companyInfo['city'] || $companyInfo['county']) && $companyInfo['country']) ? ', ' : '' }}{{ $companyInfo['country'] ?? '' }}
                </div>
                <div class="text-xs text-gray-500">Email: {{ $companyInfo['email'] ?? '-' }}</div>
                <div class="text-xs text-gray-500">Phone: {{ $companyInfo['phone'] ?? '-' }}</div>
                <div class="text-xs text-gray-500">IBAN: {{ $companyInfo['iban'] ?? '-' }}</div>
                <div class="text-xs text-gray-500">Bank: {{ $companyInfo['bank'] ?? '-' }}</div>
                <div class="text-xs text-gray-500">CUI: {{ $companyInfo['cui'] ?? '-' }}</div>
                <div class="text-xs text-gray-500">Nr. Înreg.: {{ $companyInfo['registration_number'] ?? '-' }}</div>
            </div>
        </div>

        <div class="flex flex-col items-end w-64 min-w-[180px] gap-2 text-left">
            @if($logoUrl)
                <img src="{{ $logoUrl }}" alt="Company Logo" class="w-32 h-32 object-contain" />
            @else
                <img src="/logo.png" alt="Company Logo" class="w-32 h-32 object-contain" />
            @endif
            <div style="margin-top: 0.9rem;" class="flex flex-col items-start text-left max-w-xs w-full">
                <div class="text-sm font-semibold uppercase text-gray-500 mb-1">Cumpărător</div>
                <div class="font-semibold text-gray-900">{{ $invoice['client_name'] ?? '-' }}</div>
                <div class="text-xs text-gray-500">Address: {{ $invoice['client_address'] ?? '-' }}</div>
                <div class="text-xs text-gray-500">
                    {{ $invoice['client_city'] ?? '' }}{{ ($invoice['client_city'] && $invoice['client_county']) ? ', ' : '' }}{{ $invoice['client_county'] ?? '' }}{{ (($invoice['client_city'] || $invoice['client_county']) && $invoice['client_country']) ? ', ' : '' }}{{ $invoice['client_country'] ?? '' }}
                </div>
                <div class="text-xs text-gray-500">
                    @if($invoice['client_type'] === 'individual')
                        CNP: {{ $invoice['client_vat_code'] ?? '-' }}
                    @else
                        CUI: {{ $invoice['client_vat_code'] ?? '-' }}
                    @endif
                </div>
                <div class="text-xs text-gray-500">Nr. Înreg.: {{ $invoice['client_registration_number'] ?? '-' }}</div>
                <div class="text-xs text-gray-500">Bank: {{ $invoice['client_bank'] ?? '-' }}</div>
                <div class="text-xs text-gray-500">IBAN: {{ $invoice['client_iban'] ?? '-' }}</div>
                <div class="text-xs text-gray-500">Phone: {{ $invoice['client_phone'] ?? '-' }}</div>
            </div>
        </div>
    </div>
    
    <div class="mb-4">
        <div class="text-xs font-semibold mb-2">Products/Services</div>
        <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white">
            <table class="min-w-full text-xs">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-2 py-1 text-left">No.</th>
                        <th class="px-2 py-1 text-left">Description</th>
                        <th class="px-2 py-1 text-right">Qty</th>
                        <th class="px-2 py-1 text-right">Unit Price</th>
                        <th class="px-2 py-1 text-right">VAT</th>
                        <th class="px-2 py-1 text-right">Discount</th>
                        <th class="px-2 py-1 text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td class="px-2 py-1">{{ $loop->iteration }}</td>
                            <td class="px-2 py-1">{{ $product['product_name'] }}</td>
                            <td class="px-2 py-1 text-right">{{ $product['quantity'] }}</td>
                            <td class="px-2 py-1 text-right">{{ number_format($product['converted_price'], 2, '.', ',') }}</td>
                            <td class="px-2 py-1 text-right">{{ number_format($product['total'] - $product['total_no_vat'], 2, '.', ',') }}</td>
                            <td class="px-2 py-1 text-right">{{ number_format($product['discount'] ?? 0, 2, '.', ',') }}</td>
                            <td class="px-2 py-1 text-right">{{ number_format($product['total'] - ($product['discount'] ?? 0), 2, '.', ',') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-2 py-1 text-center text-gray-400">No items</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="flex flex-col gap-2 mt-6 text-sm w-full max-w-xs ml-auto items-end">
        <div class="grid grid-cols-2 gap-x-4 w-full">
            <div class="flex flex-col gap-2 items-start">
                <span class="text-gray-500">Subtotal</span>
                <span class="text-gray-500">Discount</span>
                <span class="text-gray-500">VAT</span>
                <span class="text-lg mt-2">Total</span>
            </div>
            <div class="flex flex-col gap-2 items-end">
                <span class="font-semibold text-right">{{ number_format($subtotal, 2, '.', ',') }} {{ $invoice['currency'] }}</span>
                <span class="font-semibold text-right">{{ number_format($totalDiscount, 2, '.', ',') }} {{ $invoice['currency'] }}</span>
                <span class="font-semibold text-right">{{ number_format($totalVat, 2, '.', ',') }} {{ $invoice['currency'] }}</span>
                <span class="font-bold text-red-600 text-right text-lg mt-2">{{ number_format($invoice['total'], 2, '.', ',') }} {{ $invoice['currency'] }}</span>
            </div>
        </div>
    </div>
    
    <div class="flex justify-between items-end mt-8">
        <div class="text-xs text-gray-500">
            <div>Payment Method</div>
            <div>IBAN: {{ $companyInfo['iban'] ?? '-' }}</div>
            <div>Bank: {{ $companyInfo['bank'] ?? '-' }}</div>
        </div>
        <div class="text-xs text-gray-500 flex flex-col items-start">
            <div>Issued by <span class="font-semibold text-gray-900">{{ $companyInfo['company_name'] ?? '-' }}</span></div>
            <div class="mt-4 flex flex-col items-start">
                <span class="font-medium">Signature:</span>
                <span class="block w-40 border-b border-gray-400 mt-2" style="height: 18px;"></span>
            </div>
        </div>
    </div>
</div>
@endsection