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
        Schema::create('equipment_fuel_consumptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('fuel_type', ['diesel', 'engine_oil', 'hydraulic_oil', 'radiator_water']);
            $table->decimal('quantity', 8, 2); // الكمية باللتر
            $table->date('consumption_date');
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['equipment_id', 'consumption_date']);
            $table->index(['equipment_id', 'fuel_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipment_fuel_consumptions');
    }
};
