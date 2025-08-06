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
        Schema::create('location_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // اسم نوع الموقع
            $table->string('description')->nullable(); // وصف نوع الموقع
            $table->string('color', 7)->default('#3B82F6'); // لون للتمييز (hex color)
            $table->string('icon')->default('ri-map-pin-line'); // أيقونة للنوع
            $table->boolean('is_active')->default(true); // حالة النشاط
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('location_types');
    }
};
