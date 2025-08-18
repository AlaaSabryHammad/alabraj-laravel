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
        Schema::table('equipment_movement_history', function (Blueprint $table) {
            // Change from_location_text and to_location_text from string to text for larger JSON storage
            $table->text('from_location_text')->nullable()->change();
            $table->text('to_location_text')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('equipment_movement_history', function (Blueprint $table) {
            // Revert back to string type
            $table->string('from_location_text')->nullable()->change();
            $table->string('to_location_text')->nullable()->change();
        });
    }
};
