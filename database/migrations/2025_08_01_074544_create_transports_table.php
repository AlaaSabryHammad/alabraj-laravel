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
            $table->string('destination');
            $table->dateTime('departure_time');
            $table->dateTime('arrival_time')->nullable();
            $table->string('status')->default('scheduled');
            $table->text('cargo_description')->nullable();
            $table->decimal('distance', 8, 2)->nullable();
            $table->decimal('fuel_cost', 8, 2)->nullable();
            $table->timestamps();
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
