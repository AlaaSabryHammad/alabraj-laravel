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
        Schema::table('custodies', function (Blueprint $table) {
            // First drop the existing status column
            $table->dropColumn('status');

            // Then add it back with proper length
            $table->string('status', 20)->default('pending')->after('notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('custodies', function (Blueprint $table) {
            $table->dropColumn('status');
            $table->string('status', 10)->default('pending')->after('notes');
        });
    }
};
