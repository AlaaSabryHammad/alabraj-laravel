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
        Schema::create('warehouse_inventories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('spare_part_id')->constrained('spare_parts')->onDelete('cascade');
            $table->foreignId('location_id')->constrained('locations')->onDelete('cascade'); // المستودع
            $table->integer('current_stock')->default(0); // المخزون الحالي
            $table->integer('reserved_stock')->default(0); // المخزون المحجوز
            $table->integer('available_stock')->default(0); // المخزون المتاح
            $table->decimal('average_cost', 10, 2)->default(0); // متوسط التكلفة
            $table->decimal('total_value', 12, 2)->default(0); // القيمة الإجمالية
            $table->date('last_transaction_date')->nullable(); // تاريخ آخر معاملة
            $table->string('location_shelf')->nullable(); // موقع الرف
            $table->timestamps();
            
            $table->unique(['spare_part_id', 'location_id']); // قطعة غيار واحدة لكل مستودع
            $table->index('location_id');
            $table->index(['current_stock', 'available_stock']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_inventories');
    }
};
