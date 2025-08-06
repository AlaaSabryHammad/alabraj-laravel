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
            $table->foreignId('location_id')->constrained()->onDelete('cascade'); // المستودع
            $table->foreignId('spare_part_id')->constrained()->onDelete('cascade'); // قطعة الغيار
            $table->integer('quantity')->default(0); // الكمية المتوفرة
            $table->decimal('unit_cost', 10, 2); // تكلفة الوحدة الأخيرة
            $table->decimal('total_value', 10, 2); // القيمة الإجمالية
            $table->timestamps();

            // فهرس فريد لضمان عدم تكرار نفس قطعة الغيار في نفس المستودع
            $table->unique(['location_id', 'spare_part_id']);
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
