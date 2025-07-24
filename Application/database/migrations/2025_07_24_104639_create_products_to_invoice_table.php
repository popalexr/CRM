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
        Schema::create('products_to_invoice', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('invoice_id');
            $table->string('product_name');
            $table->string('product_type');
            $table->decimal('price', 15, 2);
            $table->string('currency');
            $table->decimal('converted_price', 15, 2);
            $table->string('converted_currency');
            $table->decimal('quantity', 15, 2);
            $table->string('unit');
            $table->decimal('vat', 5, 2)->default(0.00);
            $table->decimal('total_no_vat', 15, 2);
            $table->decimal('total', 15, 2);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products_to_invoice');
    }
};
