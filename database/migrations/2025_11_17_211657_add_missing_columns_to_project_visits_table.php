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
            // Add missing columns for project visits
            if (!Schema::hasColumn('project_visits', 'duration_hours')) {
                $table->decimal('duration_hours', 5, 2)->nullable()->comment('Duration of visit in hours');
            }
            if (!Schema::hasColumn('project_visits', 'purpose')) {
                $table->string('purpose')->nullable()->comment('Purpose of the visit');
            }
            if (!Schema::hasColumn('project_visits', 'notes')) {
                $table->text('notes')->nullable()->comment('Additional notes about the visit');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('project_visits', function (Blueprint $table) {
            $table->dropColumn(['duration_hours', 'purpose', 'notes']);
        });
    }
};
