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
        Schema::create('material_units', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم الوحدة مثل "متر مكعب", "طن", "لتر"
            $table->string('symbol'); // رمز الوحدة مثل "م³", "طن", "لتر"
            $table->string('type')->default('volume'); // نوع الوحدة: volume, weight, length
            $table->text('description')->nullable(); // وصف الوحدة
            $table->boolean('is_active')->default(true); // حالة الوحدة
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_units');
    }
};
