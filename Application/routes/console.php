<?php

use App\Jobs\BNR\CurrencyRates;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

app(Schedule::class)
    ->job(CurrencyRates::class)
    ->dailyAt('13:00')
    ->timezone(config('app.schedule_timezone', 'Europe/Bucharest'));
