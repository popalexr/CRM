<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoicesPayments extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Numele tabelului asociat modelului.
     *
     * @var string
     */
    protected $table = 'invoices_payments';

    /**
     * Atributele care pot fi asignate în masă.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'invoice_id',
        'amount_paid',
        'currency',
        'converted_amount_paid',
        'converted_currency',
        'paid_at',
    ];

    /**
     * Atributele care ar trebui convertite la tipuri native.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'paid_at' => 'datetime',
        'amount_paid' => 'decimal:2',
        'converted_amount_paid' => 'decimal:2',
    ];

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}