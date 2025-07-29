<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        
    ];
     

    public function payments(): HasMany
    {
        return $this->hasMany(InvoicesPayments::class, 'invoice_id');
    }
}
