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
        Schema::table('payroll_employees', function (Blueprint $table) {
            $table->integer('working_days')->default(0)->after('base_salary');
            $table->integer('absent_days')->default(0)->after('working_days');
            $table->decimal('overtime_hours', 5, 2)->default(0)->after('absent_days');
            $table->decimal('total_working_hours', 6, 2)->default(0)->after('overtime_hours');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payroll_employees', function (Blueprint $table) {
            $table->dropColumn(['working_days', 'absent_days', 'overtime_hours', 'total_working_hours']);
        });
    }
};
