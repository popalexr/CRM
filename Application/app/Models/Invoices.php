<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
    protected $table = 'invoices';

    protected $fillable = [
        'client_id',
        'created_by',
        'currency',
        'total',
        'vat_payer',
        'status',
        'deleted_at',
    ];
}
