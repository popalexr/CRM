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
        Schema::table('products_to_invoice', function (Blueprint $table) {
            $table->decimal('vat_amount', 15, 2)->default(0.00)->after('vat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products_to_invoice', function (Blueprint $table) {
            $table->dropColumn('vat_amount');
        });
    }
};
