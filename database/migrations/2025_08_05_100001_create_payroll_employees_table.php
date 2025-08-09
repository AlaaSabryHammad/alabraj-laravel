<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payroll_employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_id')->constrained('payrolls')->onDelete('cascade');
            $table->foreignId('employee_id')->constrained('employees');
            $table->decimal('base_salary', 10, 2)->default(0);
            $table->decimal('total_deductions', 10, 2)->default(0);
            $table->decimal('total_bonuses', 10, 2)->default(0);
            $table->decimal('net_salary', 10, 2)->default(0);

            // Attendance fields
            $table->integer('days_present')->default(0);
            $table->integer('days_absent')->default(0);
            $table->integer('working_days')->default(0);
            $table->decimal('overtime_hours', 8, 2)->default(0);
            $table->decimal('overtime_amount', 10, 2)->default(0);

            $table->boolean('is_eligible')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['payroll_id', 'employee_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('payroll_employees');
    }
};
