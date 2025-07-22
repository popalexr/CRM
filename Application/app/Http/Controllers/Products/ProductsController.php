<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductsController extends Controller
{
    public function __invoke()
    {
        return Inertia::render('Products/Index', [
            'products' => [], // This should be replaced with actual product data retrieval logic
        ]);
    }
}
