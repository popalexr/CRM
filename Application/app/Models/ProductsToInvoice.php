<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductsToInvoice extends Model
{
    protected $table = 'products_to_invoice';

    protected $fillable = [
        'invoice_id',
        'product_name',
        'product_type',
        'price',
        'currency',
        'converted_price',
        'converted_currency',
        'quantity',
        'unit',
        'vat',
        'vat_amount',
        'total_no_vat',
        'total'
    ];

    public $timestamps = false;
}
