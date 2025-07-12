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
        Schema::create('temporary_files', function (Blueprint $table) {
            $table->id();
            $table->string('file_name')->comment('Name of the temporary file');
            $table->string('file_path')->comment('Path to the temporary file');
            $table->timestamp('created_at')->useCurrent()->comment('Timestamp when the file was created');
            $table->timestamp('uploaded_at')->nullable()->comment('Timestamp when the file was uploaded');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temporary_files');
    }
};
