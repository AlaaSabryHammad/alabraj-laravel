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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('department')->nullable();
            $table->string('position')->nullable();

            // Location and Address Fields
            $table->string('address')->nullable();
            $table->unsignedBigInteger('location_id')->nullable();
            $table->string('workplace_location')->nullable();

            // Personal Information
            $table->string('photo')->nullable();
            $table->string('national_id')->nullable();
            $table->string('national_id_photo')->nullable();
            $table->date('national_id_expiry')->nullable();

            // Passport Information
            $table->string('passport_number')->nullable();
            $table->date('passport_issue_date')->nullable();
            $table->date('passport_expiry_date')->nullable();
            $table->string('passport_photo')->nullable();

            // Work Permit Information
            $table->string('work_permit_number')->nullable();
            $table->date('work_permit_issue_date')->nullable();
            $table->date('work_permit_expiry_date')->nullable();
            $table->string('work_permit_photo')->nullable();

            // Driving License Information
            $table->string('driving_license_number')->nullable();
            $table->date('driving_license_expiry')->nullable();
            $table->json('driving_license_types')->nullable();
            $table->string('driving_license_photo')->nullable();

            // Employment Information
            $table->string('role')->nullable();
            $table->string('sponsorship_status')->nullable();
            $table->string('category')->nullable();
            $table->decimal('salary', 10, 2)->nullable();
            $table->date('hire_date')->nullable();
            $table->date('contract_start')->nullable();
            $table->date('contract_end')->nullable();
            $table->integer('working_hours')->default(8);

            // Bank Information
            $table->string('bank_name')->nullable();
            $table->string('bank_account_number')->nullable();
            $table->string('iban')->nullable();

            // Personal Details
            $table->date('birth_date')->nullable();
            $table->string('nationality')->nullable();
            $table->string('marital_status')->nullable();
            $table->integer('children_count')->default(0);
            $table->string('religion')->nullable();
            $table->string('emergency_contact_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('emergency_contact_relationship')->nullable();

            // Management
            $table->unsignedBigInteger('direct_manager_id')->nullable();
            $table->decimal('rating', 3, 2)->nullable();

            // Status and Additional Fields
            $table->enum('status', ['active', 'inactive', 'terminated', 'on_leave'])->default('active');

            // Additional Documents
            $table->string('contract_document')->nullable();
            $table->string('cv_document')->nullable();
            $table->string('certificates_document')->nullable();
            $table->string('other_documents')->nullable();

            // System
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();

            // Foreign Keys will be added later after the referenced tables are created
            // $table->foreign('location_id')->references('id')->on('locations')->onDelete('set null');
            // $table->foreign('direct_manager_id')->references('id')->on('employees')->onDelete('set null');
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
