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
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable()->after('name');
            $table->string('phone')->nullable()->after('password');
            $table->date('birth_date')->nullable()->after('phone');
            $table->string('address')->nullable()->after('birth_date');
            $table->string('city')->nullable()->after('address');
            $table->string('county')->nullable()->after('city');
            $table->string('country')->nullable()->after('county');
            $table->string('permissions')->after('country')->default('[]');
            $table->boolean('is_admin')->default(false)->after('permissions');
            
            $table->softDeletes()->after('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['avatar', 'phone', 'birth_date', 'address', 'city', 'county', 'country', 'permissions', 'is_admin']);
            $table->dropSoftDeletes();
        });
    }
};
