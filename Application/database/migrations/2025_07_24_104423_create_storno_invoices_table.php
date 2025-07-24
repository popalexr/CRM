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
        Schema::create('storno_invoices', function (Blueprint $table) {
            $table->unsignedBigInteger('original_invoice_id');
            $table->unsignedBigInteger('storno_invoice_id');

            $table->foreign('original_invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->foreign('storno_invoice_id')->references('id')->on('invoices')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('storno_invoices');
    }
};
