<?php

namespace App\Console\Commands\BNR;

use App\Models\BnrCurrency;
use App\Services\BnrExchange;
use Illuminate\Console\Command;

class CurrencyRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bnr:currency-rates';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get currency rates from BNR';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $bnr = new BnrExchange();

            $exchangeDate = $bnr->getExchangeDate();
            $currencies = $bnr->getCurrencies();

            foreach ($currencies as $currency) {
                $this->saveCurrencyRate(
                    $currency['currency_code'],
                    $currency['exchange_rate'],
                    $currency['multiplier'],
                    $exchangeDate
                );
            }
        } catch (\Exception $e) {
            $this->error("Error fetching currency rates: " . $e->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    /**
     * Save the currency rate to the database.
     */
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
