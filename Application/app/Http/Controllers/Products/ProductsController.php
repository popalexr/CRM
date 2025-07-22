<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Products;
use Inertia\Inertia;

class ProductsController extends Controller
{
    public function __invoke()
    {
        $products = Products::whereNull('deleted_at')
            ->get();

        return Inertia::render('Products/Index', [
            'products' => $products, // This should be replaced with actual product data retrieval logic
        ]);
    }
}
