<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    protected $table = 'products';

    protected $fillable = [
        'name',
        'image',
        'type',
        'price',
        'currency',
        'quantity',
        'unit',
        'vat_id',
        'description',
    ];

    public $timestamps = true;
}
