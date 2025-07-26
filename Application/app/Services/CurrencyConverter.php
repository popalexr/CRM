<?php

namespace App\Services;

use App\Models\BnrCurrency;
use Illuminate\Support\Facades\Http;

class CurrencyConverter
{
    /**
     * Convert the value to a specific currency.
     *
     * @param float $value
     * @param string $currency
     * @param string $to_currency
     * 
     * @return float
     */
    public static function convert(float $value, string $currency, string $to_currency): float
    {
        if ($currency === $to_currency) {
            return $value;
        }

        // If both currencies are not RON, convert to RON first, then to the target currency
        if ($currency !== 'RON' && $to_currency !== 'RON') {
            $exchange = self::getExchangeRate($currency);

            $value = $value * $exchange['rate'] / $exchange['multiplier'];
            $currency = 'RON';
        }

        // Converting from RON to the target currency
        if ($currency === 'RON') {
            $exchange = self::getExchangeRate($to_currency);

            return $value * $exchange['multiplier'] / $exchange['rate'];
        }

        // Converting from one currency to RON
        $exchange = self::getExchangeRate($currency);

        return $value * $exchange['rate'] / $exchange['multiplier'];
    }

    private static function getExchangeRate(string $currency): array
    {
        $currentDate = date('Y-m-d');

        $currency = BnrCurrency::where('published_at', '<=', $currentDate)
            ->where('currency_code', $currency)
            ->orderBy('published_at', 'desc')
            ->first();
        
        if (!$currency) {
            throw new \Exception("Currency {$currency} not found for date {$currentDate}");
        }

        return [
            'rate'       => $currency->exchange_rate,
            'multiplier' => $currency->multiplier,
        ];
    }
}