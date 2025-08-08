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
        Schema::table('project_visits', function (Blueprint $table) {
            // Add visitor_name field
            $table->string('visitor_name')->nullable()->after('visitor_id');
            // Make visitor_id nullable to allow either visitor_id or visitor_name
            $table->foreignId('visitor_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_visits', function (Blueprint $table) {
            // Remove visitor_name field
            $table->dropColumn('visitor_name');
            // Make visitor_id required again
            $table->foreignId('visitor_id')->nullable(false)->change();
        });
    }
};
