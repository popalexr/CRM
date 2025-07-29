<?php

namespace App\Http\Controllers\Invoices;

use App\Http\Controllers\Controller;
use App\Models\Clients;
use App\Models\Invoices;
use App\Models\StornoInvoices;
use App\Models\User;
use Inertia\Inertia;

class InvoicesController extends Controller
{
    const PER_PAGE = 10;

    public function __invoke()
    {
        $invoices = $this->getInvoices();

        return Inertia::render('Invoices/Index', [
            'invoices' => $invoices['data'],
            'meta' => [
                'current_page' => $invoices['current_page'],
                'per_page' => $invoices['per_page'],
                'total' => $invoices['total'],
                'last_page' => $invoices['last_page'],
            ],
        ]);
    }

    private function getInvoices(): array
    {
        $invoices = Invoices::whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->paginate(self::PER_PAGE);
        
        $invoices->map(function ($invoice) {
            $invoice['client'] = Clients::find($invoice['client_id'])->toArray();
            $invoice['user'] = User::find($invoice['created_by'])->toArray();

            $invoice['storno'] = StornoInvoices::where('original_invoice_id', $invoice['id'])->orWhere('storno_invoice_id', $invoice['id'])->first();

            return $invoice;
        });

        return $invoices->toArray();
    }
}
