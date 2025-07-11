<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VAT extends Model
{
    protected $table = 'vat';

    protected $fillable = [
        'name',
        'rate',
        'deleted_at',
    ];
}
