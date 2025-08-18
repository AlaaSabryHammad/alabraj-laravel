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
        Schema::table('damaged_parts_receipts', function (Blueprint $table) {
            // Drop the existing foreign key constraint
            $table->dropForeign(['warehouse_id']);
            
            // Add the correct foreign key constraint to reference locations table
            $table->foreign('warehouse_id')->references('id')->on('locations')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('damaged_parts_receipts', function (Blueprint $table) {
            // Drop the current foreign key
            $table->dropForeign(['warehouse_id']);
            
            // Restore the original foreign key constraint to warehouses table
            $table->foreign('warehouse_id')->references('id')->on('warehouses')->onDelete('cascade');
        });
    }
};
