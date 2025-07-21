<?php

namespace App\Http\Controllers\Products; 

use App\Http\Controllers\Controller;
use App\Models\Product; 
use Illuminate\Http\Request; 
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect; 

class ProductDetailsController extends Controller
{
    /**
     * Handle the incoming request to show product details.
     */
    public function __invoke(Product $product) 
    {

        $productId = request()->input('id'); 

        if (blank($productId) || !is_numeric($productId)) {
            return Redirect::route('products.index')->with('error', 'ID produs invalid.');
        }

        $product = Product::find($productId); 

        if (blank($product)) {
            return Redirect::route('products.index')->with('error', 'Produsul nu a fost găsit.');
        }

        return Inertia::render('Products/Show', [ 
            'product' => $product, 
        ]);
    }
}