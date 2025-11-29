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
            // Drop the incorrect foreign key constraint
            $table->dropForeign(['warehouse_id']);

            // Add the correct foreign key constraint pointing to locations table
            $table->foreign('warehouse_id')
                ->references('id')
                ->on('locations')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('damaged_parts_receipts', function (Blueprint $table) {
            // Revert back to the incorrect constraint if needed
            $table->dropForeign(['warehouse_id']);

            // Note: Cannot recreate the invalid constraint without a warehouses table
            // This is a breaking change that requires the table structure to be fixed
        });
    }
};
