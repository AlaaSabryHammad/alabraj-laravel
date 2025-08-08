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
        Schema::table('spare_part_serials', function (Blueprint $table) {
            $table->unsignedBigInteger('exported_to_employee_id')->nullable()->after('assigned_to_employee_id');
            $table->date('exported_date')->nullable()->after('exported_to_employee_id');
            
            $table->foreign('exported_to_employee_id')->references('id')->on('employees')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('spare_part_serials', function (Blueprint $table) {
            $table->dropForeign(['exported_to_employee_id']);
            $table->dropColumn(['exported_to_employee_id', 'exported_date']);
        });
    }
};
