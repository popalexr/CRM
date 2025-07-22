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
        Schema::table('products', function (Blueprint $table) {
            $table->string('unit')->nullable()->change();
            $table->decimal('price', 10, 2)->default(0.00)->change();

            $table->unsignedBigInteger('vat_id')->nullable()->after('unit');

            $table->foreign('vat_id')->references('id')->on('vat')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['vat_id']);
            $table->dropColumn('vat_id');
        });
    }
};
