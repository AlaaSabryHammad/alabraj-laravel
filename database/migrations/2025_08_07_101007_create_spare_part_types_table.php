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
        Schema::create('spare_part_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم نوع القطعة
            $table->text('description')->nullable(); // وصف النوع
            $table->string('category'); // الفئة (engine, brakes, electrical...)
            $table->boolean('is_active')->default(true); // حالة النشاط
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spare_part_types');
    }
};
