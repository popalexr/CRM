<?php

namespace App\Http\Controllers\Products; 

use App\Http\Controllers\Controller;
use App\Models\Products; 
use Illuminate\Http\Request; 
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect; 

class ProductDetailsController extends Controller
{
    private int $productId;
    private ?Products $product = null;

    public function __construct(Request $request)
    {
        $this->productId = (int) $request->input('id', 0);

        if ($this->productId > 0) {
            $this->product = Products::find($this->productId);
        }
    }

    /**
     * Handle the incoming request to show product details.
     */
    public function __invoke() 
    {
        if (!$this->product) {
            return Redirect::route('products.index')->with('error', 'Product not found.');
        }

        return Inertia::render('Products/Show', [ 
            'product' => $this->product, 
        ]);
    }
}