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
        Schema::create('equipment_maintenances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained('equipment')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // المستخدم الذي سجل الصيانة

            // معلومات الصيانة الأساسية
            $table->date('maintenance_date'); // تاريخ دخول الصيانة
            $table->enum('maintenance_type', ['internal', 'external'])->default('internal'); // داخلية أم خارجية
            $table->enum('status', ['in_progress', 'completed', 'cancelled'])->default('in_progress'); // حالة الصيانة

            // معلومات الصيانة الخارجية (تظهر فقط عند اختيار خارجية)
            $table->decimal('external_cost', 10, 2)->nullable(); // تكلفة الصيانة الخارجية
            $table->string('maintenance_center')->nullable(); // اسم مركز الصيانة
            $table->string('invoice_number')->nullable(); // رقم الفاتورة
            $table->string('invoice_image')->nullable(); // صورة الفاتورة

            // معلومات إضافية
            $table->text('notes')->nullable(); // الملاحظات
            $table->text('description')->nullable(); // وصف الأعطال أو الصيانة المطلوبة

            // تواريخ إضافية
            $table->date('expected_completion_date')->nullable(); // تاريخ الإنجاز المتوقع
            $table->date('actual_completion_date')->nullable(); // تاريخ الإنجاز الفعلي

            $table->timestamps();

            // فهرسة للبحث السريع
            $table->index(['equipment_id', 'maintenance_date']);
            $table->index(['maintenance_type', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_maintenances');
    }
};
