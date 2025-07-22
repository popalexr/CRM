<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\VAT;

class GetVatController extends Controller
{
    /**
     * Handle the incoming request to get VAT information.
     * 
     * This method will return a JSON response with VAT details.
     */
    public function __invoke()
    {
        $vats = $this->getVatDetails();

        return response()->json([
            'vats' => $vats,
        ]);
    }

    private function getVatDetails(): array
    {
        $vats = VAT::whereNull('deleted_at')
            ->get(['id', 'name', 'rate']);

        return $vats->toArray();
    }
}
