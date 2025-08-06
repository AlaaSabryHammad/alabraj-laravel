<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update empty unit fields with unit_of_measure values
        $affected = DB::update("UPDATE materials SET unit = unit_of_measure WHERE (unit IS NULL OR unit = '') AND unit_of_measure IS NOT NULL AND unit_of_measure != ''");

        // Log the changes
        if ($affected > 0) {
            Log::info("Materials unit consistency migration: Updated {$affected} records");
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration cannot be easily reversed as we don't know which units were originally empty
        // We'll just log that the migration was rolled back
        Log::info("Materials unit consistency migration rolled back - manual review may be needed");
    }
};
