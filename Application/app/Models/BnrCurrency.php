<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BnrCurrency extends Model
{
    protected $table = 'bnr_currency';

    protected $fillable = [
        'currency_code',
        'exchange_rate',
        'multiplier',
        'published_at',
    ];

    public $timestamps = false;
}
