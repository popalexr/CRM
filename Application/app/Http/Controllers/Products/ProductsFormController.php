<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Models\Products;
use App\Traits\HasFormLabels;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductsFormController extends Controller
{
    use HasFormLabels;
    private int $productId = 0;
    private ?Products $product = null;

    public function __construct(private Request $request)
    {
        if ($this->request->has('id')) {
            $this->productId = (int) $this->request->input('id');

            $this->product = Products::find($this->productId);
        }
    }
    
    public function __invoke()
    {
        if ($this->productId < 1)
        {
            return Inertia::render('Products/Create', [
                'formLabels' => $this->getFormLabels('products'),
                'productFieldTypes' => config('products.field_types'),
            ]);
        }

        if (blank($this->product))
        {
            return redirect()
                ->route('products.index')
                ->with('error', 'Product not found.');
        }
        
        return Inertia::render('Products/Edit', [
            'product'    => $this->product,
            'formLabels' => $this->getFormLabels('products'),
            'productFieldTypes' => config('products.field_types'),
        ]);
    }
}
