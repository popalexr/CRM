<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('invoices_payments', function (Blueprint $table) {
            $table->id();
            
            // Cheia externă către tabelul 'invoices'
            // Se va șterge în cascadă: dacă se șterge o factură, se șterg și plățile asociate.
            $table->foreignId('invoice_id')->constrained('invoices')->onDelete('cascade');
            
            $table->decimal('amount_paid', 10, 2);
            $table->string('currency', 3); 
            
            $table->decimal('converted_amount_paid', 10, 2);
            $table->string('converted_currency', 3);
            
            $table->dateTime('paid_at');
            
            $table->timestamps(); 
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices_payments');
    }
};