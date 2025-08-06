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
        Schema::create('manager_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id'); // الموظف الذي تم تعيين مدير له
            $table->unsignedBigInteger('manager_id'); // المدير المعيّن
            $table->unsignedBigInteger('assigned_by'); // من قام بالتعيين
            $table->timestamp('assigned_at'); // تاريخ التعيين
            $table->text('notes')->nullable(); // ملاحظات
            $table->string('assignment_type', 50); // نوع التعيين (تعيين، إزالة، تغيير)
            $table->timestamps();

            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('manager_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('assigned_by')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manager_assignments');
    }
};
