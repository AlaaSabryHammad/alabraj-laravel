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
        Schema::table('employees', function (Blueprint $table) {
            // Add missing fields that are in the form but not in database
            $table->date('driving_license_issue_date')->nullable()->after('driving_license_expiry');
            
            // Add alias for national_id_expiry_date (for form compatibility)
            // The form uses 'national_id_expiry_date' but DB has 'national_id_expiry'
            // We'll keep the database field name and update the form instead
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['driving_license_issue_date']);
        });
    }
};
