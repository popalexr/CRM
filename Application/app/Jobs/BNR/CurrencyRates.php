<?php

namespace App\Jobs\BNR;

use App\Models\BnrCurrency;
use App\Services\BnrExchange;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class CurrencyRates implements ShouldQueue
{
    use Queueable;

    /**
     * Max attempts before the job is buried.
     */
    public int $tries = 15;

    /**
     * Seconds to wait before retrying the job.
     */
    public int $backoff = 60;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $bnr = new BnrExchange();

        $exchangeDate = $bnr->getExchangeDate();
        $currencies = $bnr->getCurrencies();

        if ($exchangeDate !== date('Y-m-d')) {
            throw new \Exception("Exchange date is not today: {$exchangeDate}");
        }

        foreach ($currencies as $currency) {
            $this->saveCurrencyRate(
                $currency['currency_code'],
                $currency['exchange_rate'],
                $currency['multiplier'],
                $exchangeDate
            );
        }
    }

    private function saveCurrencyRate(string $currencyCode, float $exchangeRate, int $multiplier, string $exchangeDate): void
    {
        BnrCurrency::create([
            'currency_code' => $currencyCode,
            'exchange_rate' => $exchangeRate,
            'multiplier'    => $multiplier,
            'published_at'  => $exchangeDate,
        ]);
    }
}
