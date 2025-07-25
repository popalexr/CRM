<?php

namespace App\Listeners;

use App\Events\CreatedInvoice;
use App\Models\Invoices;
use App\Models\ProductsToInvoice;
use App\Services\CurrencyConverter;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Queue\InteractsWithQueue;

class CalculateInvoiceValue implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * The number of times the listener should be retried if it fails.
     * 
     * @var int
     */
    public $tries = 5;

    /**
     * Handle the event.
     */
    public function handle(CreatedInvoice $event): void
    {
        $invoiceId = $event->invoiceId;

        $invoice = $this->getInvoice($invoiceId);

        if (!$invoice) {
            return;
        }

        $this->convertProductsPricesToInvoiceCurrency($invoiceId, $invoice->currency);
        
        $products = $this->getProducts($invoiceId);

        // Update the invoice total
        $total = $products->sum(function ($product) {
            return $product->total;
        });

        $invoice->total = $total;
        $invoice->save();
    }

    private function getInvoice(int $invoiceId): Invoices|null
    {
        $invoice = Invoices::find($invoiceId);

        return $invoice;
    }

    private function getProducts(int $invoiceId)
    {
        $invoice = $this->getInvoice($invoiceId);

        if (!$invoice) {
            return (object)[];
        }

        $products = ProductsToInvoice::where('invoice_id', $invoiceId)
            ->get();
        
        return $products;
    }

    private function convertProductsPricesToInvoiceCurrency(int $invoiceId, string $currency)
    {
        $currency = strtolower($currency);

        ProductsToInvoice::where('invoice_id', $invoiceId)
            ->each(function ($product) use ($currency) {
                $product->converted_price = CurrencyConverter::convert($product->price, strtolower($product->currency), $currency);
                $product->converted_currency = $currency; // Update the currency to the invoice's currency

                $product->total = ($product->converted_price + (float)$product->converted_price * (float)$product->vat / 100) * $product->quantity;
                $product->total = round($product->total, 2);

                $product->total_no_vat = $product->converted_price * $product->quantity;
                $product->total_no_vat = round($product->total_no_vat, 2);

                $product->save();
            });
    }
}
