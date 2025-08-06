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
        Schema::create('spare_part_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spare_part_id')->constrained('spare_parts')->onDelete('cascade');
            $table->foreignId('location_id')->constrained('locations')->onDelete('cascade'); // المستودع
            $table->foreignId('equipment_id')->nullable()->constrained('equipment')->onDelete('set null'); // المعدة (للتصدير)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // المستخدم المسؤول
            $table->enum('transaction_type', ['استلام', 'تصدير', 'تحويل', 'جرد', 'إتلاف']); // نوع المعاملة
            $table->integer('quantity'); // الكمية (موجب للاستلام، سالب للتصدير)
            $table->decimal('unit_price', 10, 2); // سعر الوحدة وقت المعاملة
            $table->decimal('total_amount', 12, 2); // المبلغ الإجمالي
            $table->string('reference_number')->nullable(); // رقم مرجعي (فاتورة، أمر شراء، إلخ)
            $table->text('notes')->nullable(); // ملاحظات
            $table->date('transaction_date'); // تاريخ المعاملة
            $table->json('additional_data')->nullable(); // بيانات إضافية
            $table->timestamps();
            
            $table->index(['spare_part_id', 'location_id']);
            $table->index(['transaction_type', 'transaction_date']);
            $table->index('equipment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spare_part_transactions');
    }
};
