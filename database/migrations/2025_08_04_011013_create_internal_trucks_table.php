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
        Schema::create('internal_trucks', function (Blueprint $table) {
            $table->id();
            $table->string('plate_number')->unique();
            $table->string('model');
            $table->string('brand');
            $table->integer('year');
            
            $table->string('engine_number')->nullable();
            $table->string('chassis_number')->nullable();
            $table->decimal('load_capacity', 8, 2); // بالطن
            $table->string('fuel_type')->default('ديزل');
            $table->string('license_expiry')->nullable();
            $table->string('insurance_expiry')->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['متاح', 'قيد الاستخدام', 'تحت الصيانة', 'غير متاح'])->default('متاح');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // المستخدم الذي أضاف الشاحنة
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('internal_trucks');
    }
};
