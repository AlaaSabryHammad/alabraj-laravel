<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('payroll_bonuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_employee_id')->constrained('payroll_employees')->onDelete('cascade');
            $table->string('type');
            $table->decimal('amount', 10, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payroll_bonuses');
    }
};
