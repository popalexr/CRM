<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Products;
use Illuminate\Http\Request;

class DeleteProductController extends Controller
{
    private int $productId = 0;
    private ?Products $product = null;

    public function __construct(private Request $request)
    {
        if ($this->request->has('id')) {
            $this->productId = (int) $this->request->input('id');
            $this->product = Products::find($this->productId);
        }
    }
    /**
     * Handle the incoming request to delete a product.
     */
    public function __invoke()
    {
        if (!$this->productId || blank($this->product)) {
            return redirect()
                ->route('products.index')
                ->with('error', 'Product not found.');
        }

        return redirect()
            ->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}