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
        Schema::table('transports', function (Blueprint $table) {
            // Remove foreign key constraint first
            $table->dropForeign(['material_id']);
            // Then drop the column
            $table->dropColumn('material_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transports', function (Blueprint $table) {
            $table->unsignedBigInteger('material_id')->nullable()->after('external_truck_id');
            $table->foreign('material_id')->references('id')->on('materials')->onDelete('set null');
        });
    }
};
