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
        Schema::create('transports', function (Blueprint $table) {
            $table->id();
            $table->string('vehicle_type');
            $table->string('vehicle_number');
            $table->string('driver_name');
            $table->string('source_location');
            $table->string('destination');
            $table->dateTime('departure_time')->nullable();
            $table->dateTime('arrival_time')->nullable();
            $table->string('status')->default('scheduled');
            $table->text('cargo_description')->nullable();
            $table->decimal('quantity', 10, 2)->nullable();
            $table->decimal('fuel_cost', 8, 2)->nullable();
            $table->unsignedBigInteger('material_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();
            
            // Foreign Keys will be added later
            // $table->foreign('material_id')->references('id')->on('materials')->onDelete('set null');
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transports');
    }
};
