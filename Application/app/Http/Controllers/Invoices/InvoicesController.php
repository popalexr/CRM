<?php

namespace App\Http\Controllers\Invoices;

use App\Http\Controllers\Controller;
use App\Models\Clients;
use App\Models\Invoices;
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

    public function edit(Invoices $invoice)
    {
        // You may want to load client name, etc. for the form
        $client = Clients::find($invoice->client_id);
        return Inertia::render('Invoices/Edit', [
            'invoice' => [
                'id' => $invoice->id,
                'number' => $invoice->id, // sau alt câmp dacă ai un număr de factură
                'client_name' => $client ? $client->client_name : '',
                'date' => $invoice->created_at ? $invoice->created_at->format('Y-m-d') : '',
                'due_date' => $invoice->payment_deadline,
                'status' => $invoice->status,
                'total' => $invoice->total,
                'currency' => $invoice->currency,
            ]
        ]);
    }

    public function update(Invoices $invoice)
    {
        $data = request()->validate([
            'number' => 'nullable',
            'client_name' => 'nullable',
            'date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'status' => 'nullable|string',
            'total' => 'nullable|numeric',
            'currency' => 'nullable|string',
        ]);

        // Update fields
        // $invoice->number = $data['number']; // dacă ai câmpul în DB
        $invoice->status = $data['status'] ?? $invoice->status;
        $invoice->total = $data['total'] ?? $invoice->total;
        $invoice->currency = $data['currency'] ?? $invoice->currency;
        $invoice->payment_deadline = $data['due_date'] ?? $invoice->payment_deadline;
        // $invoice->created_at = $data['date'] ?? $invoice->created_at;
        $invoice->save();

        return redirect()->route('invoices.details', ['invoice' => $invoice->id]);
    }

    private function getInvoices(): array
    {
        $invoices = Invoices::whereNull('deleted_at')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $invoices->map(function ($invoice) {
            $invoice['client'] = Clients::find($invoice['client_id'])->toArray();
            $invoice['user'] = User::find($invoice['created_by'])->toArray();

            return $invoice;
        });

        return $invoices->toArray();
    }
}
