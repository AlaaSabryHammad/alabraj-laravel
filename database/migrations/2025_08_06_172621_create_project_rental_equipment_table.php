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
        Schema::create('project_rental_equipment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('equipment_type', 100);
            $table->string('equipment_name');
            $table->string('rental_company');
            $table->date('rental_start_date');
            $table->date('rental_end_date')->nullable();
            $table->decimal('daily_rate', 10, 2)->nullable();
            $table->enum('currency', ['SAR', 'USD', 'EUR'])->default('SAR');
            $table->text('notes')->nullable();
            $table->foreignId('recorded_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_rental_equipment');
    }
};
