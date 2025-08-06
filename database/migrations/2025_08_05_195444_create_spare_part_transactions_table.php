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
            $table->foreignId('spare_part_id')->constrained()->onDelete('cascade');
            $table->foreignId('location_id')->constrained()->onDelete('cascade'); // المستودع
            $table->enum('type', ['استلام', 'تصدير', 'تحويل', 'جرد', 'إتلاف']); // نوع المعاملة
            $table->integer('quantity'); // الكمية
            $table->decimal('unit_cost', 10, 2)->nullable(); // تكلفة الوحدة
            $table->decimal('total_value', 10, 2); // القيمة الإجمالية
            $table->foreignId('equipment_id')->nullable()->constrained()->onDelete('set null'); // المعدة المستهدفة
            $table->string('recipient')->nullable(); // المستلم
            $table->text('notes')->nullable(); // ملاحظات
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // المستخدم الذي أجرى المعاملة
            $table->timestamps();
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
