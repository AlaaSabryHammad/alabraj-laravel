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
        Schema::table('internal_trucks', function (Blueprint $table) {
            $table->date('warranty_expiry')->nullable()->after('purchase_price');
            $table->date('last_maintenance')->nullable()->after('warranty_expiry');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('internal_trucks', function (Blueprint $table) {
            $table->dropColumn(['warranty_expiry', 'last_maintenance']);
        });
    }
};
