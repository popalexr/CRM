<?php

namespace App\Http\Controllers\Invoices;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class InvoiceDetailsController extends Controller
{
    public function __invoke(Request $request, $id)
    {
        if ((int)$id <= 0) {
            abort(404, 'Invoice not found.');
        }

        $invoice = DB::table('invoices')->where('id', $id)->first();
        if (!$invoice) {
            abort(404, 'Invoice not found.');
        }

        $invoiceData = (array) $invoice;

        $storno = DB::table('storno_invoices')->where('invoice_id', $id)->first();
        $stornoData = $storno ? (array) $storno : null;

        $products = DB::table('products_to_invoices')->where('invoice_id', $id)->get();

        return response()->json([
            'invoice' => $invoiceData,
            'storno' => $stornoData,
            'products' => $products,
        ]);
    }
}
