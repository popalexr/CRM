<?php

namespace App\Services;

use SimpleXMLElement;

class BnrExchange
{
    /**
     * XML Document received from BNR.
     * @var string
     */
    private string $xmlDocument;

    /**
     * BNR Exchange date
     * 
     * @var string
     */
    private string $exchangeDate;

    /**
     * BNR Currencies
     * @var array
     */
    private array $currencies = [];

    /**
     * Constructor to initialize the BnrExchange service.
     */
    public function __construct()
    {
        $this->xmlDocument = file_get_contents('https://www.bnr.ro/nbrfxrates.xml');
        if ($this->xmlDocument === false) {
            throw new \Exception('Failed to fetch XML document from BNR');
        }

        $this->parseXmlDocument();
    }

    /**
     * Get the exchange date.
     *
     * @return string
     */
    public function getExchangeDate(): string
    {
        return $this->exchangeDate;
    }

    /**
     * Get the list of currencies with their exchange rates.
     *
     * @return array
     */
    public function getCurrencies(): array
    {
        return $this->currencies;
    }

    /**
     * Parse the XML document to extract exchange rates.
     */
    private function parseXmlDocument(): void
    {
        $xml = new SimpleXMLElement($this->xmlDocument);

        $this->exchangeDate = (string) $xml->Header->PublishingDate;

        foreach ($xml->Body->Cube->Rate as $rate) {
            $currencyCode = (string) $rate['currency'];
            $exchangeRate = (float) $rate;
            $multiplier = (int) $rate['multiplier'] ?: 1; // Default to 1 if multiplier is not set

            $this->currencies[] = [
                'currency_code' => $currencyCode,
                'exchange_rate' => $exchangeRate,
                'multiplier'    => $multiplier,
            ];
        }
    }
}