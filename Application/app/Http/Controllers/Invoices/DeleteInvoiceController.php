<?php

namespace App\Http\Controllers\Invoices;

use App\Http\Controllers\Controller;
use App\Models\Invoice; 

class DeleteInvoiceController extends Controller
{
    /**
     * Handle the incoming request to delete an invoice.
     * Folosim Route Model Binding pentru a primi direct modelul Invoice.
     */
    public function __invoke(Invoice $invoice)
    {
       
        if (!in_array($invoice->status, ['draft', 'paid'])) {
            return redirect()
                ->route('invoices.index') 
                ->with('error', 'Doar facturile cu statusul "draft" sau "paid" pot fi șterse.');
        }

    
        $invoice->delete();

        return redirect()
            ->route('invoices.index')
            ->with('success', 'Factura a fost ștearsă cu succes.');
    }
}