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
            // Personal photo
            $table->string('photo')->nullable()->after('address');

            // National ID document
            $table->string('national_id_photo')->nullable()->after('photo');

            // Passport information
            $table->string('passport_number')->nullable()->after('national_id_photo');
            $table->date('passport_issue_date')->nullable()->after('passport_number');
            $table->date('passport_expiry_date')->nullable()->after('passport_issue_date');
            $table->string('passport_photo')->nullable()->after('passport_expiry_date');

            // Work permit information
            $table->string('work_permit_number')->nullable()->after('passport_photo');
            $table->date('work_permit_issue_date')->nullable()->after('work_permit_number');
            $table->date('work_permit_expiry_date')->nullable()->after('work_permit_issue_date');
            $table->string('work_permit_photo')->nullable()->after('work_permit_expiry_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'photo',
                'national_id_photo',
                'passport_number',
                'passport_issue_date',
                'passport_expiry_date',
                'passport_photo',
                'work_permit_number',
                'work_permit_issue_date',
                'work_permit_expiry_date',
                'work_permit_photo'
            ]);
        });
    }
};
