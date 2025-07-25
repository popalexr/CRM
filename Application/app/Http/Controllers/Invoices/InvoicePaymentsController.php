<?php
namespace App\Http\Controllers\Invoices;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invoices;
use Illuminate\Support\Facades\DB;

class InvoicePaymentsController extends Controller
{
    public function store(Request $request, $invoiceId)
    {
        $invoice = Invoices::findOrFail($invoiceId);

        // Handle delete payment
        if ($request->input('_method') === 'delete') {
            $index = $request->input('index');
            $payments = $invoice->payments ?? [];
            if (is_numeric($index) && isset($payments[$index])) {
                array_splice($payments, $index, 1);
                $invoice->payments = $payments;
                $invoice->save();
            }
            // Redirect with id as query parameter, matching route
            return redirect()->route('invoices.details', ['id' => $invoice->id]);
        }

        // Add payment
        $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'paid_on' => 'required|date',
        ]);

        $payments = $invoice->payments ?? [];
        $payments[] = [
            'amount' => (float)$request->input('amount'),
            'paid_on' => $request->input('paid_on'),
        ];
        $invoice->payments = $payments;
        $invoice->save();

        // Redirect with id as query parameter, matching route
        return redirect()->route('invoices.details', ['id' => $invoice->id]);
    }
}
