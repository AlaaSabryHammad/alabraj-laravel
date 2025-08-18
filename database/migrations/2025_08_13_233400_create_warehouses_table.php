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
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('اسم المخزن');
            $table->string('code')->unique()->comment('كود المخزن');
            $table->text('description')->nullable()->comment('وصف المخزن');
            $table->text('address')->nullable()->comment('عنوان المخزن');
            $table->string('manager')->nullable()->comment('مدير المخزن');
            $table->string('phone')->nullable()->comment('هاتف المخزن');
            $table->enum('status', ['active', 'inactive'])->default('active')->comment('حالة المخزن');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouses');
    }
};
