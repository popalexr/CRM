<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\ProductStockFormRequest;
use App\Models\Products;
use Illuminate\Http\RedirectResponse;

class HandleProductStockController extends Controller
{
    public function __invoke(ProductStockFormRequest $request, $id): RedirectResponse
    {
        $product = Products::find($id);
        if (!$product) {
            return redirect()->route('products.details', ['id' => $id])->with(['error' => 'Product not found.']);
        }

        $value = (int) $request->input('value');
        $action = $request->input('action');

        switch ($action) {
            case 'increment':
                $this->handleIncrement($product, $value);
                break;
            case 'subtract':
                $this->handleSubtract($product, $value);
                break;
            case 'modify':
                $this->handleModify($product, $value);
                break;
            default:
                return redirect()->route('products.details', ['id' => $id])->with(['error' => 'Invalid action.']);
        }

        $product->save();
        return redirect()->route('products.details', ['id' => $id])->with(['success' => 'Stock saved.']);
    }

    private function handleIncrement(Products $product, int $value): void
    {
        $product->stock += $value;
    }

    private function handleSubtract(Products $product, int $value): void
    {
        $product->stock = max(0, $product->stock - $value);
    }

    private function handleModify(Products $product, int $value): void
    {
        $product->stock = $value;
    }
}
