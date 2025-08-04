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
            // Driving license information
            $table->string('driving_license_number')->nullable()->after('work_permit_photo');
            $table->date('driving_license_issue_date')->nullable()->after('driving_license_number');
            $table->date('driving_license_expiry_date')->nullable()->after('driving_license_issue_date');
            $table->string('driving_license_photo')->nullable()->after('driving_license_expiry_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'driving_license_number',
                'driving_license_issue_date',
                'driving_license_expiry_date',
                'driving_license_photo'
            ]);
        });
    }
};
