<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;

class GenerateCurrenciesConfig extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'config:generate-currencies';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches currency data from BNR and generates the config/currencies.php file.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Fetching currency data from BNR...');

        try {
            $response = Http::withoutVerifying()->get('https://www.bnr.ro/nbrfxrates.xml');

            if (!$response->successful()) {
                $this->error('Failed to fetch data from BNR.');
                return 1;
            }

            $xml = simplexml_load_string($response->body());
            if ($xml === false) {
                $this->error('Failed to parse XML data.');
                return 1;
            }

            $currencies = [
                'RON' => 'Leu Romanesc', // Adăugăm manual RON-ul
            ];
            
            $nonCurrencySymbols = ['XAU', 'XDR', 'BGN']; // Excludem aurul, DST și Leva (care e fixă față de EUR)

            foreach ($xml->Body->Cube->Rate as $rate) {
                $currencyCode = (string) $rate['currency'];
                $currencyName = (string) $rate;

                if (in_array($currencyCode, $nonCurrencySymbols)) {
                    continue;
                }

                $currencies[$currencyCode] = $currencyName;
            }

            ksort($currencies); // Sortăm alfabetic după codul valutei

            $content = "<?php\n\n// Generated on " . now()->toDateTimeString() . "\n\nreturn [\n";
            foreach ($currencies as $code => $name) {
                $content .= "    '{$code}' => '{$name}',\n";
            }
            $content .= "];\n";

            File::put(config_path('currencies.php'), $content);

            $this->info('Successfully generated config/currencies.php file.');
            return 0;

        } catch (\Exception $e) {
            $this->error('An error occurred: ' . $e->getMessage());
            return 1;
        }
    }
}