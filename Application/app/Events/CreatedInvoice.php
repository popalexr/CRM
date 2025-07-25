<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CreatedInvoice
{
    use Dispatchable, SerializesModels;

    public int $invoiceId;

    /**
     * Create a new event instance.
     *   
     * @param int $invoiceId
    */
    public function __construct(int $invoiceId)
    {
        $this->invoiceId = $invoiceId;
    }
}
