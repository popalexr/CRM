<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Products;
use Illuminate\Http\Request;

class GetProductsController extends Controller
{
    const LIMIT = 5;

    private string $searchQuery;

    public function __construct(private Request $request) {}

    public function __invoke()
    {
        $this->searchQuery = $this->request->input('q', '');

        //

        return response()->json([
            'products' => $this->getProducts(),
            'services' => $this->getServices(),
        ]);
    }

    /**
     * Get products based on the search query.
     */
    private function getProducts(): array
    {
        $products = Products::query()
            ->whereNull('deleted_at')
            ->where('type', 'product')
            ->where('name', 'like', '%' . $this->searchQuery . '%')
            ->limit(self::LIMIT)
            ->get()
            ->toArray();

        return $products;
    }

    /**
     * Get services based on the search query.
     */
    private function getServices(): array
    {
        $services = Products::query()
            ->whereNull('deleted_at')
            ->where('type', 'service')
            ->where('name', 'like', '%' . $this->searchQuery . '%')
            ->limit(self::LIMIT)
            ->get()
            ->toArray();

        return $services;
    }
}
