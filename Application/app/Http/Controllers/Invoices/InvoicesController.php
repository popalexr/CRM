<?php

namespace App\Http\Controllers\Invoices;

use App\Http\Controllers\Controller;
use App\Models\Invoices;
use Inertia\Inertia;

class InvoicesController extends Controller
{
    public function __invoke()
    {
        $invoices = Invoices::with(['client', 'user'])
            ->whereNull('deleted_at')
            ->get()
            ->map(function ($invoice) {
                return [
                    'id' => $invoice->id,
                    'client' => $invoice->client?->name,
                    'user' => $invoice->user?->name,
                    'value' => $invoice->value,
                    'currency' => $invoice->currency,
                    'status' => $invoice->status,
                    'payment_deadline' => $invoice->payment_deadline,
                    'created_at' => $invoice->created_at->toDateTimeString(),
                ];
            });

        return Inertia::render('Invoices/Index', [
            'invoices' => $invoices,
        ]);
    }
}
