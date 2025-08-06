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
            $table->date('license_expiry')->nullable();
            $table->date('insurance_expiry')->nullable();
            $table->string('truck_type')->nullable();
            $table->string('color')->nullable();
            $table->decimal('fuel_tank_capacity', 8, 2)->nullable();
            $table->integer('mileage')->nullable();
            $table->date('last_maintenance_date')->nullable();
            $table->decimal('maintenance_cost', 10, 2)->nullable();
            $table->text('notes')->nullable();
            $table->enum('status', ['available', 'in_use', 'maintenance', 'unavailable'])->default('available');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
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
