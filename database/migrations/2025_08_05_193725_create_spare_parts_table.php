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
        Schema::create('spare_parts', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // كود قطعة الغيار
            $table->string('name'); // اسم قطعة الغيار
            $table->text('description')->nullable(); // وصف قطعة الغيار
            $table->string('category')->nullable(); // فئة قطعة الغيار
            $table->string('brand')->nullable(); // العلامة التجارية
            $table->string('model')->nullable(); // رقم الموديل
            $table->decimal('unit_price', 10, 2); // سعر الوحدة
            $table->string('unit_type', 50)->default('قطعة'); // نوع الوحدة (قطعة، كيلو، متر، إلخ)
            $table->integer('minimum_stock')->default(0); // الحد الأدنى للمخزون
            $table->string('supplier')->nullable(); // المورد
            $table->string('location_shelf')->nullable(); // موقع الرف في المستودع
            $table->json('specifications')->nullable(); // مواصفات إضافية
            $table->boolean('is_active')->default(true); // حالة قطعة الغيار
            $table->timestamps();
            
            $table->index(['code', 'name']);
            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spare_parts');
    }
};
