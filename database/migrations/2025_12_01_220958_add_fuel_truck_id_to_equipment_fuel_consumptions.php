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
        Schema::table('equipment_fuel_consumptions', function (Blueprint $table) {
            $table->unsignedBigInteger('fuel_truck_id')->nullable()->after('equipment_id');
            $table->foreign('fuel_truck_id')->references('id')->on('equipment')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipment_fuel_consumptions', function (Blueprint $table) {
            $table->dropForeign(['fuel_truck_id']);
            $table->dropColumn('fuel_truck_id');
        });
    }
};
