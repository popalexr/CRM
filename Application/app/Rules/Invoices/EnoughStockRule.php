<?php

namespace App\Rules\Invoices;

use App\Models\Products;
use App\Models\ProductsToInvoice;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class EnoughStockRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $index = explode('.', $attribute)[1] ?? null;

        $productId = request()->input("products.{$index}.id");
        $productType = request()->input("products.{$index}.type");
        $quantity = request()->input("products.{$index}.quantity");

        if ($productType === 'service') {
            return;
        }

        $product = Products::find($productId);

        if ($this->isCreatingInvoice()) {
            if (!$product) {
                $fail("Product not found.");
                return;
            }

            if ($product->quantity < $quantity) {
                $fail("Not enough stock. Available: {$product->quantity}.");
            }
            
            return;
        }

        if (!$product) {
            return;
        }

        $currentInvoiceProduct = ProductsToInvoice::where('product_id', $productId)
            ->where('invoice_id', request()->input('id'))
            ->first();
        
        if (!$currentInvoiceProduct) {
            throw new \Exception("Product not found in the invoice.");
        }

        $currentQuantity = $currentInvoiceProduct->quantity;

        $difference = $quantity - $currentQuantity;

        if ($product->quantity < $difference) {
            $fail("Not enough stock. Available: {$product->quantity}.");
        }
    }

    private function isCreatingInvoice(): bool
    {
        return !request()->input('id');
    }
}
