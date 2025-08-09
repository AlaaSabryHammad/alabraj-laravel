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
        Schema::create('equipment', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category')->nullable();
            $table->string('type')->nullable();
            $table->unsignedBigInteger('type_id')->nullable();
            $table->string('model')->nullable();
            $table->string('manufacturer')->nullable();
            $table->text('manufacturer_description')->nullable();
            $table->string('serial_number')->unique();
            $table->enum('status', ['available', 'in_use', 'maintenance', 'out_of_order'])->default('available');
            $table->string('location')->nullable();
            $table->unsignedBigInteger('location_id')->nullable();
            $table->unsignedBigInteger('driver_id')->nullable();
            $table->unsignedBigInteger('truck_id')->nullable();
            $table->date('purchase_date');
            $table->decimal('purchase_price', 12, 2);
            $table->date('warranty_expiry')->nullable();
            $table->date('last_maintenance')->nullable();
            $table->text('description')->nullable();
            $table->text('notes')->nullable();
            $table->json('images')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();

            // Foreign Keys will be added later
            // $table->foreign('type_id')->references('id')->on('equipment_types')->onDelete('set null');
            // $table->foreign('location_id')->references('id')->on('locations')->onDelete('set null');
            // $table->foreign('driver_id')->references('id')->on('employees')->onDelete('set null');
            // $table->foreign('truck_id')->references('id')->on('internal_trucks')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment');
    }
};
