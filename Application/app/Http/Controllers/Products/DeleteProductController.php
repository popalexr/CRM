<?php

namespace App\Http\Controllers\Products; // Namespace-ul corect

use App\Http\Controllers\Controller;
use App\Models\Products; // <-- Importa modelul Products (plural)
use Illuminate\Http\Request;
use Illuminate\Support\Carbon; // Pentru Carbon::now()
use Illuminate\Support\Facades\Redirect; // Pentru redirect

class DeleteProductController extends Controller
{
    /**
     * Handle the incoming request to delete a product.
     */
    public function __invoke(Request $request)
    {
        $productId = $request->input('id');

        if (blank($productId) || !is_numeric($productId)) {
            return Redirect::route('products.index')->with('error', 'ID produs invalid.');
        }

        $product = Products::find($productId); 
        if (blank($product)) { 
            return Redirect::route('products.index')->with('error', 'Produsul nu a fost găsit.');
        }
        $product->delete(); 
        return Redirect::route('products.index')->with('success', 'Produsul a fost șters cu succes.');
    }
}