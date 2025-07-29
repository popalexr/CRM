<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoices extends Model
{
    public function client()
    {
        return $this->belongsTo(Clients::class, 'client_id');
    }
    protected $table = 'invoices';

    protected $fillable = [
        'client_id',
        'created_by',
        'currency',
        'total',
        'vat_payer',
        'payment_deadline',
        'status',
        'deleted_at',
        'payments',
    ];

    protected $casts = [
        'payments' => 'array',
    ];
}
