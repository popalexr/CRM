<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StornoInvoices extends Model
{
    protected $table = 'storno_invoices';

    protected $fillable = [
        'original_invoice_id',
        'storno_invoice_id',
    ];

    public $timestamps = false;
}
