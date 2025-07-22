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
    
    public function index()
    {
        $products = Products::paginate(15);

        return Inertia::render('Products/Index', [
            'products' => $products,
        ]);
    }
    
    public function __invoke()
    {
        $formConfig = [
            'form_structure' => config('products.form_structure'),
            'form_tabs' => config('products.form_tabs'),
            'form_labels' => config('products.form_labels'),
            'form_layout' => config('products.form_layout'),
            'form_fields' => config('products.form_fields'),
            'product_types' => config('products.product_types'),
            'breadcrumbs' => config('products.breadcrumbs'),
            'file_upload' => config('products.file_upload'),
        ];

        if ($this->productId < 1)
        {
            return Inertia::render('Products/Create', [
                'formConfig' => $formConfig,
                'formLabels' => $this->getFormLabels('products'),
                'productTypes' => config('products.product_types'),
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
            'formConfig' => $formConfig,
            'formLabels' => $this->getFormLabels('products'),
            'productTypes' => config('products.product_types'),
            'productFieldTypes' => config('products.field_types'),
        ]);
    }
}
