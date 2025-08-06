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
            $table->string('type');
            $table->string('model')->nullable();
            $table->string('serial_number')->unique();
            $table->enum('status', ['available', 'in_use', 'maintenance', 'out_of_order'])->default('available');
            $table->string('location')->nullable();
            $table->date('purchase_date');
            $table->decimal('purchase_price', 12, 2);
            $table->date('warranty_expiry')->nullable();
            $table->date('last_maintenance')->nullable();
            $table->timestamps();
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
