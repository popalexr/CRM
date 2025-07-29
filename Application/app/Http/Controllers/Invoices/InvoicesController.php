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
        return Inertia::render('Invoices/Index', [
            'invoices' => $this->getInvoices(),
        ]);
    }

    private function getInvoices(): array
    {
        $invoices = Invoices::whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $invoices->map(function ($invoice) {
            $invoice['client'] = Clients::find($invoice['client_id'])->toArray();
            $invoice['user'] = User::find($invoice['created_by'])->toArray();

            $invoice['storno'] = StornoInvoices::where('original_invoice_id', $invoice['id'])->orWhere('storno_invoice_id', $invoice['id'])->first();

            return $invoice;
        });

        return $invoices->toArray();
    }
}
