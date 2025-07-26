<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\CurrencyConverter;
use Illuminate\Http\Request;

class CurrencyRateController extends Controller
{
    private ?string $currencyCode;
    private ?string $toCurrencyCode;
    private ?float $value;

    public function __construct(private Request $request)
    {
        $this->currencyCode = $request->input('currency_code', null);
        $this->toCurrencyCode = $request->input('to_currency_code', null);
        $this->value = (float) $request->input('value', null);
    }

    public function __invoke()
    {
        if (is_null($this->currencyCode) || is_null($this->toCurrencyCode) || $this->value <= 0) {
            return response()->json([
                'error' => 'Invalid parameters provided.'
            ], 400);
        }

        try {
            $value = round(CurrencyConverter::convert(
                $this->value,
                $this->currencyCode,
                $this->toCurrencyCode
            ), 2);

            return response()->json([
                'value' => $value,
                'currency_code' => $this->toCurrencyCode
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Conversion failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
