<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update fuel_type values from Arabic to English
        DB::table('internal_trucks')
            ->where('fuel_type', 'بنزين')
            ->update(['fuel_type' => 'gasoline']);

        DB::table('internal_trucks')
            ->where('fuel_type', 'ديزل')
            ->update(['fuel_type' => 'diesel']);

        DB::table('internal_trucks')
            ->where('fuel_type', 'هجين')
            ->update(['fuel_type' => 'hybrid']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse the changes - convert back to Arabic
        DB::table('internal_trucks')
            ->where('fuel_type', 'gasoline')
            ->update(['fuel_type' => 'بنزين']);

        DB::table('internal_trucks')
            ->where('fuel_type', 'diesel')
            ->update(['fuel_type' => 'ديزل']);

        DB::table('internal_trucks')
            ->where('fuel_type', 'hybrid')
            ->update(['fuel_type' => 'هجين']);
    }
};
