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
            $table->date('birth_date')->nullable()->after('national_id_expiry_date');
            $table->string('nationality')->nullable()->after('birth_date');
            $table->string('medical_insurance_status')->nullable()->after('nationality');
            $table->string('location_type')->nullable()->after('medical_insurance_status');
            $table->json('additional_documents')->nullable()->after('iban');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'birth_date',
                'nationality',
                'medical_insurance_status',
                'location_type',
                'additional_documents'
            ]);
        });
    }
};
