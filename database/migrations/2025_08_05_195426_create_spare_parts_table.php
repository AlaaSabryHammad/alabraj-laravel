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
            $table->decimal('price', 10, 2); // سعر الوحدة
            $table->string('category')->nullable(); // الفئة
            $table->text('specifications')->nullable(); // المواصفات
            $table->timestamps();
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
