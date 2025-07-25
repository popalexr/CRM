<?php

namespace App\Http\Controllers\Invoices;

use App\Http\Controllers\Controller;
use App\Models\Invoices;
use Illuminate\Http\Request;

class DeleteInvoiceController extends Controller
{
    const STATUS_WHITELIST = ['draft', 'paid'];

    private int $id;
    private ?Invoices $invoice = null;
    
    public function __construct(private Request $request)
    {
        $this->id = (int) $request->input('id', 0);

        $this->invoice = Invoices::find($this->id);
    }

    public function __invoke()
    {
        if (! $this->id || blank($this->invoice)) {
            return redirect()->route('invoices.index')->with(['error' => 'Invoice not found.']);
        }

        if (!in_array($this->invoice->status, self::STATUS_WHITELIST)) {
            return redirect()
                ->route('invoices.index') 
                ->with('error', 'You cannot delete this invoice.');
        }

    
        $this->invoice->deleted_at = now();
        $this->invoice->save();

        return redirect()
            ->route('invoices.index')
            ->with('success', 'Invoice deleted successfully.');
    }
}