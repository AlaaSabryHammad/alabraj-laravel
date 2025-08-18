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
        Schema::create('fuel_trucks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipment_id')->constrained()->onDelete('cascade');
            $table->enum('fuel_type', ['diesel', 'gasoline', 'engine_oil', 'hydraulic_oil', 'radiator_water', 'brake_oil', 'other']);
            $table->decimal('capacity', 10, 2)->comment('السعة الكلية للخزان');
            $table->decimal('current_quantity', 10, 2)->default(0)->comment('الكمية الحالية المتاحة');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fuel_trucks');
    }
};
