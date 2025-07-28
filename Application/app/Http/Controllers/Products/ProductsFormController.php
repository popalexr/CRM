<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Products\ProductsFormRequest;
use App\Models\Products;
use App\Models\TemporaryFiles;
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
        if ($this->productId > 0 && blank($this->product)){
            return redirect()
                ->route('products.index')
                ->with('error', 'Product not found.');
        }

        if (! $this->productId) {
            return Inertia::render('Products/Create', [
                'formConfig'        => $this->getFormConfig(),
                'formLabels'        => $this->getFormLabels('products'),
                'productTypes'      => config('products.product_types'),
                'productFieldTypes' => config('products.field_types'),
                'currencies'        => config('currencies'),
            ]);
        }

        return Inertia::render('Products/Edit', [
            'product'           => $this->product,
            'formConfig'        => $this->getFormConfig(),
            'formLabels'        => $this->getFormLabels('products'),
            'productTypes'      => config('products.product_types'),
            'productFieldTypes' => config('products.field_types'),
            'currencies'        => config('currencies'),
        ]);
    }

    /**
     * Handle the POST request.
     */
    public function post(ProductsFormRequest $formRequest)
    {
        if ($this->productId > 0 && blank($this->product)){
            return redirect()
                ->route('products.index')
                ->with('error', 'Product not found.');
        }

        if ($this->productId < 1) {
            return $this->handleCreateProduct($formRequest);
        }

        return $this->handleEditProduct($formRequest);
    }

    /**
     * Handle product creation.
     */
    private function handleCreateProduct(ProductsFormRequest $formRequest)
    {
        $productData = [
            'name'          => $formRequest->input('name'),
            'type'          => $formRequest->input('type'),
            'price'         => $formRequest->input('price'),
            'currency'      => $formRequest->input('currency'),
            'vat_id'        => $formRequest->input('vat_id'),
            'unit'          => $formRequest->input('unit'),
            'quantity'      => (int) $formRequest->input('quantity', 0),
            'description'   => $formRequest->input('description'),
        ];

        $this->product = Products::create($productData);
        $this->product->save();

        if ($this->productHasNewImage($formRequest->input('image_file_id'))) {
            $this->editImage($formRequest->input('image_file_id'));
        }

        return redirect()
            ->route('products.index')
            ->with('success', 'Product created successfully.');
    }

    /**
     * Handle product editing.
     */
    private function handleEditProduct(ProductsFormRequest $formRequest)
    {
        $this->product->name = $formRequest->input('name');
        $this->product->type = $formRequest->input('type');
        $this->product->price = $formRequest->input('price');
        $this->product->currency = $formRequest->input('currency');
        $this->product->vat_id = $formRequest->input('vat_id');
        $this->product->unit = $formRequest->input('unit');
        $this->product->quantity = (int) $formRequest->input('quantity', 0);
        $this->product->description = $formRequest->input('description');

        $this->product->save();

        if ($this->productHasNewImage($formRequest->input('image_file_id'))) {
            $this->editImage($formRequest->input('image_file_id'));
        }

        return redirect()
            ->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    /**
     * Check if product has a new image.
     */
    private function productHasNewImage(?int $fileId): bool
    {
        if (is_null($fileId)) {
            return false;
        }

        if ($fileId < 1) {
            return false;
        }

        $tmp = TemporaryFiles::find($fileId);

        return ! blank ($tmp) && file_exists(storage_path($tmp->file_path));
    }

    /**
     * Edit the product image.
     */
    private function editImage(int $fileId)
    {
        $tmp = TemporaryFiles::find($fileId);

        if (blank($tmp) || ! file_exists(storage_path($tmp->file_path))) {
            return;
        }

        $tmp_storage = storage_path($tmp->file_path);

        $new_storage = storage_path('app/public/products/' . $tmp->file_name);

        if (! file_exists($new_storage)) {
            copy($tmp_storage, $new_storage);
        }

        $this->product->image = '/storage/products/' . $tmp->file_name;

        $this->product->save();
    }

    /**
     * Get the form configuration.
     */
    private function getFormConfig(): array
    {
        return [
            'form_structure' => config('products.form_structure'),
            'form_tabs' => config('products.form_tabs'),
            'form_labels' => config('products.form_labels'),
            'form_layout' => config('products.form_layout'),
            'form_fields' => config('products.form_fields'),
            'product_types' => config('products.product_types'),
            'breadcrumbs' => config('products.breadcrumbs'),
            'file_upload' => config('products.file_upload'),
        ];
    }
}
